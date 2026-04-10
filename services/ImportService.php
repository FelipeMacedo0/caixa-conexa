<?php

namespace app\services;

use Yii;
use app\services\ConexaService;
use app\models\Product;
use app\models\Customer;
use app\dtos\ProductDTO;
use app\dtos\CustomerDTO;
use app\dtos\ErrorResponseDTO;
use Exception;

class ImportService
{
    private $conexaService;

    public function __construct()
    {
        $this->conexaService = new ConexaService();
    }

    /**
     * Imports all products from Conexa API until hasNext is false
     * 
     * @return array Summary of import
     */
    public function importProducts()
    {
        $limit = 100;
        $offset = 0;
        $totalImported = 0;
        $pages = 0;

        try {
            do {
                $productsDto = $this->conexaService->products($limit, $offset);

                if ($productsDto instanceof ErrorResponseDTO) {
                    throw new Exception("Error during products fetch at offset $offset: " . json_encode($productsDto->toArray()));
                }

                $data = $productsDto->getData();
                foreach ($data as $productDto) {
                    $this->saveProduct($productDto);
                    $totalImported++;
                }

                $pagination = $productsDto->getPagination();
                $hasNext = $pagination ? $pagination->getHasNext() : false;
                $offset += $limit;
                $pages++;

                // Safety break to prevent infinite loops if API is misbehaving
                if ($pages > 1000) {
                    break;
                }

            } while ($hasNext);

            return [
                'success' => true,
                'total' => $totalImported,
                'pages' => $pages
            ];

        } catch (Exception $e) {
            Yii::error("ImportProducts Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'total' => $totalImported,
                'pages' => $pages
            ];
        }
    }

    /**
     * Imports all customers from Conexa API until hasNext is false
     * 
     * @return array Summary of import
     */
    public function importCustomers()
    {
        $limit = 100;
        $offset = 0;
        $totalImported = 0;
        $pages = 0;

        try {
            do {
                $customersDto = $this->conexaService->customers($limit, $offset);

                if ($customersDto instanceof ErrorResponseDTO) {
                    throw new Exception("Error during customers fetch at offset $offset: " . json_encode($customersDto->toArray()));
                }

                $data = $customersDto->getData();
                foreach ($data as $customerDto) {
                    $this->saveCustomer($customerDto);
                    $totalImported++;
                }

                $pagination = $customersDto->getPagination();
                $hasNext = $pagination ? $pagination->getHasNext() : false;
                $offset += $limit;
                $pages++;

                // Safety break
                if ($pages > 1000) {
                    break;
                }

            } while ($hasNext);

            return [
                'success' => true,
                'total' => $totalImported,
                'pages' => $pages
            ];

        } catch (Exception $e) {
            Yii::error("ImportCustomers Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'total' => $totalImported,
                'pages' => $pages
            ];
        }
    }

    /**
     * Maps ProductDTO to Product model and saves it
     */
    private function saveProduct(ProductDTO $dto)
    {
        $model = Product::findOne(['product_id' => $dto->productId]) ?: new Product();
        
        $model->product_id = $dto->productId;
        $model->name = $dto->name;
        $model->description = $dto->description;
        $model->price = $dto->price;
        $model->active = $dto->active ? 1 : 0;
        $model->is_customer_consumable = $dto->isCustomerConsumable ? 1 : 0;
        $model->category_id = $dto->categoryId;
        $model->company_id = $dto->companyId;
        $model->cost_center_id = $dto->costCenterId;
        $model->nfse_description = $dto->nfseDescription;
        
        // Handle dates if they exist in DTO
        $model->created_at = $dto->toArray()['createdAt'] ?? null;
        $model->updated_at = $dto->toArray()['updatedAt'] ?? null;

        if (!$model->save()) {
            $errors = json_encode($model->getErrors());
            throw new Exception("Failed to save product {$dto->productId}: $errors");
        }
    }

    /**
     * Maps CustomerDTO to Customer model and saves it
     */
    private function saveCustomer(CustomerDTO $dto)
    {
        $model = Customer::findOne(['customer_id' => $dto->customerId]) ?: new Customer();

        $model->customer_id = $dto->customerId;
        $model->name = $dto->name;
        $model->trade_name = $dto->tradeName;
        $model->pronunciation = $dto->pronunciation;
        $model->field_of_activity = $dto->fieldOfActivity;
        $model->notes = $dto->notes;
        $model->cell_number = $dto->cellNumber;
        $model->website = $dto->website;
        $model->has_login_access = $dto->hasLoginAccess ? 1 : 0;
        $model->login = $dto->login;
        $model->password = $dto->password;
        $model->company_id = $dto->companyId;

        if (!$model->save()) {
            $errors = json_encode($model->getErrors());
            throw new Exception("Failed to save customer {$dto->customerId}: $errors");
        }
    }
}
