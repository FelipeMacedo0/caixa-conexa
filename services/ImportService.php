<?php

namespace app\services;

use Yii;
use app\services\ConexaService;
use app\models\Product;
use app\models\Customer;
use app\models\Person;
use app\models\Sale;
use app\dtos\ProductDTO;
use app\dtos\CustomerDTO;
use app\dtos\PersonDTO;
use app\dtos\SaleDTO;
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
     * Imports all persons from Conexa API until hasNext is false
     * 
     * @return array Summary of import
     */
    public function importPersons()
    {
        $limit = 100;
        $offset = 0;
        $totalImported = 0;
        $pages = 0;

        try {
            do {
                $personsDto = $this->conexaService->persons($limit, $offset);

                if ($personsDto instanceof ErrorResponseDTO) {
                    throw new Exception("Error during persons fetch at offset $offset: " . json_encode($personsDto->toArray()));
                }

                $data = $personsDto->getData();
                foreach ($data as $personDto) {
                    $this->savePerson($personDto);
                    $totalImported++;
                }

                $pagination = $personsDto->getPagination();
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
            Yii::error("ImportPersons Error: " . $e->getMessage());
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

    /**
     * Maps PersonDTO to Person model and saves it
     */
    private function savePerson(PersonDTO $dto)
    {
        $model = Person::findOne(['person_id' => $dto->personId]) ?: new Person();

        $model->person_id = $dto->personId;
        $model->customer_id = $dto->customerId;
        $model->company_id = $dto->companyId;
        $model->is_foreign = $dto->isForeign ? 1 : 0;
        $model->name = $dto->name;
        $model->rg = $dto->rg;
        $model->issuing_authority = $dto->issuingAuthority;
        $model->cpf = $dto->cpf;
        $model->birth_date = $dto->birthDate ? $dto->birthDate->format('Y-m-d') : null;
        $model->marital_status = $dto->maritalStatus;
        $model->sex = $dto->sex;
        $model->nationality = $dto->nationality;
        $model->place_of_birth = $dto->placeOfBirth;
        $model->notes = $dto->notes;
        $model->is_company_partner = $dto->isCompanyPartner ? 1 : 0;
        $model->is_guarantor = $dto->isGuarantor ? 1 : 0;
        $model->profession = $dto->profession;
        $model->cell_number = $dto->cellNumber;
        $model->job_title = $dto->jobTitle;
        $model->photo = $dto->photo;
        $model->resume = $dto->resume;
        $model->is_individual_customer = $dto->isIndividualCustomer ? 1 : 0;
        $model->has_login_access = $dto->hasLoginAccess ? 1 : 0;
        $model->is_active = $dto->isActive ? 1 : 0;
    
        if (!$model->save()) {
            $errors = json_encode($model->getErrors());
            throw new Exception("Failed to save person {$dto->personId}: $errors");
        }
    }

    /**
     * Imports all sales from Conexa API until hasNext is false
     * 
     * @return array Summary of import
     */
    public function importSales()
    {
        $limit = 100;
        $offset = 0;
        $totalImported = 0;
        $pages = 0;

        try {
            do {
                $salesDto = $this->conexaService->sales($limit, $offset);

                if ($salesDto instanceof ErrorResponseDTO) {
                    throw new Exception("Error during sales fetch at offset $offset: " . json_encode($salesDto->toArray()));
                }

                $data = $salesDto->getData();
                foreach ($data as $saleDto) {
                    $this->saveSale($saleDto);
                    $totalImported++;
                }

                $pagination = $salesDto->getPagination();
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
            Yii::error("ImportSales Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'total' => $totalImported,
                'pages' => $pages
            ];
        }
    }

    /**
     * Maps SaleDTO to Sale model and saves it
     */
    private function saveSale(SaleDTO $dto)
    {
        $model = Sale::findOne(['sale_id' => $dto->saleId]) ?: new Sale();

        $model->sale_id = $dto->saleId;
        $model->status = $dto->status;
        $model->discount_value = $dto->discountValue;
        $model->amount = $dto->amount;
        $model->quantity = $dto->quantity;
        $model->notes = $dto->notes;
        $model->product_id = $dto->productId;
        $model->customer_id = $dto->customerId;
        $model->requester_id = $dto->requesterId;
        $model->seller_id = $dto->sellerId;
        $model->contract_id = $dto->contractId;
        $model->recurring_sale_id = $dto->recurringSaleId;
        $model->original_amount = $dto->originalAmount;
        
        $model->reference_date = $dto->referenceDate ? $dto->referenceDate->format('Y-m-d H:i:s') : null;
        $model->created_at = $dto->createdAt ? $dto->createdAt->format('Y-m-d H:i:s') : null;
        $model->updated_at = $dto->updatedAt ? $dto->updatedAt->format('Y-m-d H:i:s') : null;

        if (!$model->save()) {
            $errors = json_encode($model->getErrors());
            throw new Exception("Failed to save sale {$dto->saleId}: $errors");
        }
    }
}
