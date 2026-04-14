<?php

namespace app\controllers;


use Yii;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\Controller;
use app\models\Person;
use app\models\Product;
use app\models\Customer;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ProductStock;
use app\services\ConexaService;
use app\services\ImportService;
use app\dtos\ErrorResponseDTO;
use app\dtos\AuthDTO;
use app\dtos\SaleDTO;
use app\dtos\ProductDTO;
use app\dtos\ProductsDTO;
use app\dtos\PaginationDTO;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'products', 'sales', 'persons', 'add-stock', 'stock-history', 'search-products', 'search-customers', 'search-persons', 'store-sale', 'import-products', 'import-customers', 'import-persons', 'import-sales'],
                'rules' => [
                    [
                        'actions' => ['logout', 'products', 'sales', 'persons', 'add-stock', 'stock-history', 'search-products', 'search-customers', 'search-persons', 'store-sale', 'import-products', 'import-customers', 'import-persons', 'import-sales'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionProducts()
    {
        $limit = (int)Yii::$app->request->get('limit', 10);
        $offset = (int)Yii::$app->request->get('offset', 0);

        $query = Product::find();
        $totalCount = $query->count();
        $models = $query->offset($offset)->limit($limit)->all();
        
        $data = [];
        foreach ($models as $model) {
            $data[] = new ProductDTO([
                'productId' => $model->product_id,
                'name' => $model->name,
                'description' => $model->description,
                'price' => $model->price,
                'active' => (bool)$model->active,
                'isCustomerConsumable' => (bool)$model->is_customer_consumable,
                'categoryId' => $model->category_id,
                'companyId' => $model->company_id,
                'costCenterId' => $model->cost_center_id,
                'nfseDescription' => $model->nfse_description,
            ]);
        }
        
        $hasNext = ($offset + $limit) < $totalCount;
        $pagination = new PaginationDTO([
            'limit' => $limit,
            'offset' => $offset,
            'hasNext' => $hasNext
        ]);
        
        $productsDtoObj = new ProductsDTO($data, $pagination);
        $productsObj = $productsDtoObj->toObject();

        return $this->render('products', [
            'products' => $productsObj,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionSales()
    {
        $limit = (int)Yii::$app->request->get('limit', 10);
        $offset = (int)Yii::$app->request->get('offset', 0);

        $query = \app\models\Sale::find()->orderBy(['created_at' => SORT_DESC]);
        $totalCount = $query->count();
        $models = $query->offset($offset)->limit($limit)->all();
        
        $data = [];
        foreach ($models as $model) {
            $data[] = new SaleDTO([
                'saleId' => $model->sale_id,
                'status' => $model->status,
                'discountValue' => $model->discount_value,
                'amount' => $model->amount,
                'quantity' => $model->quantity,
                'notes' => $model->notes,
                'productId' => $model->product_id,
                'customerId' => $model->customer_id,
                'requesterId' => $model->requester_id,
                'sellerId' => $model->seller_id,
                'contractId' => $model->contract_id,
                'recurringSaleId' => $model->recurring_sale_id,
                'originalAmount' => $model->original_amount,
                'referenceDate' => $model->reference_date,
                'createdAt' => $model->created_at,
                'updatedAt' => $model->updated_at,
                // Passing product as array for ProductDTO::fromArray
                'product' => $model->product ? [
                    'name' => $model->product->name
                ] : null
            ]);
        }
        
        $hasNext = ($offset + $limit) < $totalCount;
        $pagination = new PaginationDTO([
            'limit' => $limit,
            'offset' => $offset,
            'hasNext' => $hasNext
        ]);
        
        $salesDtoObj = new \app\dtos\SalesDTO($data, $pagination);
        $salesObj = $salesDtoObj->toObject();

        return $this->render('sales', [
            'sales' => $salesObj,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Displays persons page.
     *
     * @return string
     */
    public function actionPersons()
    {
        $limit = (int)Yii::$app->request->get('limit', 10);
        $offset = (int)Yii::$app->request->get('offset', 0);

        $query = \app\models\Person::find();
        $totalCount = $query->count();
        $models = $query->offset($offset)->limit($limit)->all();
        
        $data = [];
        foreach ($models as $model) {
            $data[] = new \app\dtos\PersonDTO([
                'personId' => $model->person_id,
                'name' => $model->name,
                'cpf' => $model->cpf,
                'cellNumber' => $model->cell_number,
                'isActive' => (bool)$model->is_active,
            ]);
        }
        
        $hasNext = ($offset + $limit) < $totalCount;
        $pagination = new PaginationDTO([
            'limit' => $limit,
            'offset' => $offset,
            'hasNext' => $hasNext
        ]);
        
        $personsDTO = new \app\dtos\PersonsDTO($data, $pagination);
        $personsObj = $personsDTO->toObject();

        return $this->render('persons', [
            'persons' => $personsObj,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function actionStockHistory($product_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $models = ProductStock::find()
                ->where(['product_id' => $product_id])
                ->andWhere(['is', 'deleted_at', new Expression('NULL')])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(50)
                ->all();
            
            $data = array_map(function($m) {
                return [
                    'date' => Yii::$app->formatter->asDatetime($m->created_at, 'short'),
                    'type' => $m->qtd > 0 ? 'Entrada' : 'Saída',
                    'qtd' => abs($m->qtd),
                    'observation' => $m->observation ?: '-'
                ];
            }, $models);
            
            return ['success' => true, 'data' => $data];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Action to add stock via modal
     */
    public function actionAddStock()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $model = new ProductStock();
            $model->product_id = $request->post('product_id');
            $model->qtd = $request->post('qtd');
            $model->observation = $request->post('observation');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Estoque lançado com sucesso!');
            } else {
                $errors = current($model->getFirstErrors());
                Yii::$app->session->setFlash('error', 'Erro ao salvar estoque: ' . $errors);
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['site/products']);
    }

    /**
     * Action to search products for Select2 Ajax
     */
    public function actionSearchProducts($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $query = Product::find();
            
            if ($q) {
                $query->andWhere("MATCH(name, description) AGAINST(:q IN BOOLEAN MODE)", [':q' => $q . '*']);
            }

            $models = $query->limit(100)->all();
            
            $productIds = array_column($models, 'product_id');
            $stocks = ProductStock::find()
                ->select(['product_id', 'SUM(qtd) as balance'])
                ->where(['product_id' => $productIds])
                ->andWhere(['is', 'deleted_at', new \yii\db\Expression('NULL')])
                ->groupBy('product_id')
                ->asArray()
                ->all();
            
            $stockMap = [];
            foreach ($stocks as $s) {
                $stockMap[$s['product_id']] = $s['balance'];
            }

            $results = array_map(function($p) use ($stockMap) {
                $balance = $stockMap[$p->product_id] ?? 0;
                return [
                    'id' => $p->product_id, 
                    'text' => $p->name . ' (Estoque: ' . (int)$balance . ')'
                ];
            }, $models);
            
            return ['results' => $results];
        } catch (\Exception $e) {
            return ['results' => []];
        }
    }

    /**
     * Action to search customers for Select2 Ajax
     */
    public function actionSearchCustomers($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $query = Customer::find();
            if ($q) {
                $query->andWhere("MATCH(name, trade_name) AGAINST(:q IN BOOLEAN MODE)", [':q' => $q . '*']);
            }
            $models = $query->limit(100)->all();
            
            $results = array_map(function($c) {
                $name = $c->name ?? '';
                $tradeName = $c->trade_name ?? '';
                
                $text = $name;
                if ($tradeName && $tradeName !== $name) {
                    $text .= ' (' . $tradeName . ')';
                }
                
                return [
                    'id' => $c->customer_id, 
                    'text' => $text
                ];
            }, $models);
            
            return ['results' => $results];
        } catch (\Exception $e) {
            return ['results' => []];
        }
    }

    /**
     * Action to search persons for Select2 Ajax
     */
    public function actionSearchPersons($q = null, $customer_id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(empty($customer_id)){
            return ['results' => []];
        }
        
        try {
            $query = Person::find();

            if ($q) {
                $query->andWhere(new Expression('(MATCH(name) AGAINST(:q IN BOOLEAN MODE) OR MATCH(cpf) AGAINST(:q IN BOOLEAN MODE) OR MATCH(rg) AGAINST(:q IN BOOLEAN MODE))'), [':q' => $q . '*']);
            }

            if ($customer_id) {
                $query->andWhere(['customer_id' => $customer_id]);
            }

            $models = $query->limit(100)->all();
            
            $results = array_map(function($p) {
                return [
                    'id' => $p->person_id, 
                    'text' => $p->name
                ];
            }, $models);
            
            return ['results' => $results];
        } catch (\Exception $e) {
            return ['results' => []];
        }
    }

    /**
     * Action to store a sale and decrement stock
     */
    public function actionStoreSale()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return $this->redirect(Yii::$app->request->referrer ?: ['site/sales']);  
        }

        $productId = (int) $request->post('product_id');
        $customerId = (int) $request->post('customer_id');
        $requesterId = (int) $request->post('requester_id');
        $qtd = (int) $request->post('qtd');
        $observation = $request->post('observation');

        //var_dump($productId, $customerId, $requesterId, $qtd, $observation);
        //die;

        $stockBalance = ProductStock::getStockBalance($productId);

        if ($qtd > $stockBalance) {
            Yii::$app->session->setFlash('error', "Quantidade solicitada da venda ($qtd) é maior que o saldo em estoque ($stockBalance).");
            return $this->redirect(Yii::$app->request->referrer ?: ['site/sales']);
        }

        try {
            $conexaService = new ConexaService();
            
            $saleDto = new SaleDTO([
                'productId' => $productId,
                'customerId' => $customerId,
                'requesterId' => $requesterId,
                'quantity' => $qtd,
                'notes' => $observation ?: 'Venda lançada via Painel'
            ]);

            
            
            // Dispara requisição para a API Conexa
            $postResponse = $conexaService->storeSale($saleDto);

            if ($postResponse instanceof ErrorResponseDTO) {
                return $this->redirect(Yii::$app->request->referrer ?: ['site/sales']);
            }

            // Salva na base de dados de estoque local com o ID da venda retornada pela API
            $model = new ProductStock();
            $model->product_id = $productId;
            $model->qtd = -$qtd;
            $model->observation = $observation ?: 'Venda lançada';
            $model->sale_id = $postResponse->id;
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Venda lançada com sucesso! O estoque foi reduzido.');
            } else {
                $errors = current($model->getFirstErrors());
                Yii::$app->session->setFlash('error', 'Erro ao salvar venda localmente: ' . $errors);
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Erro na API ao salvar venda: ' . $e->getMessage());
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['site/sales']);
    }

    /**
     * Action to import products from Conexa API asynchronously (triggered via AJAX)
     */
    public function actionImportProducts()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Note: For long-running processes in PHP without a queue, 
        // we can try to let it run after closing the connection.
        if (function_exists('fastcgi_finish_request')) {
            echo json_encode(['success' => true, 'message' => 'Importação iniciada em segundo plano.']);
            fastcgi_finish_request();
        }

        try {
            $importService = new ImportService();
            $result = $importService->importProducts();
            
            if (!$result['success']) {
                Yii::error("Import error: " . $result['error']);
                if (!function_exists('fastcgi_finish_request')) {
                    return ['success' => false, 'message' => $result['error']];
                }
            }

            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => true, 'data' => $result];
            }
        } catch (\Exception $e) {
            Yii::error("ActionImportProducts Exception: " . $e->getMessage());
            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
    }

    /**
     * Action to import customers from Conexa API asynchronously (triggered via AJAX)
     */
    public function actionImportCustomers()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (function_exists('fastcgi_finish_request')) {
            echo json_encode(['success' => true, 'message' => 'Importação de clientes iniciada em segundo plano.']);
            fastcgi_finish_request();
        }

        try {
            $importService = new ImportService();
            $result = $importService->importCustomers();
            
            if (!$result['success']) {
                Yii::error("Import customers error: " . $result['error']);
                if (!function_exists('fastcgi_finish_request')) {
                    return ['success' => false, 'message' => $result['error']];
                }
            }

            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => true, 'data' => $result];
            }
        } catch (\Exception $e) {
            Yii::error("ActionImportCustomers Exception: " . $e->getMessage());
            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
    }

    /**
     * Action to import persons from Conexa API asynchronously (triggered via AJAX)
     */
    public function actionImportPersons()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (function_exists('fastcgi_finish_request')) {
            echo json_encode(['success' => true, 'message' => 'Importação de pessoas iniciada em segundo plano.']);
            fastcgi_finish_request();
        }

        try {
            $importService = new ImportService();
            $result = $importService->importPersons();
            
            if (!$result['success']) {
                Yii::error("Import persons error: " . $result['error']);
                if (!function_exists('fastcgi_finish_request')) {
                    return ['success' => false, 'message' => $result['error']];
                }
            }

            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => true, 'data' => $result];
            }
        } catch (\Exception $e) {
            Yii::error("ActionImportPersons Exception: " . $e->getMessage());
            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
    }

    /**
     * Action to import sales from Conexa API asynchronously (triggered via AJAX)
     */
    public function actionImportSales()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (function_exists('fastcgi_finish_request')) {
            echo json_encode(['success' => true, 'message' => 'Importação de vendas iniciada em segundo plano.']);
            fastcgi_finish_request();
        }

        try {
            $importService = new ImportService();
            $result = $importService->importSales();
            
            if (!$result['success']) {
                Yii::error("Import sales error: " . $result['error']);
                if (!function_exists('fastcgi_finish_request')) {
                    return ['success' => false, 'message' => $result['error']];
                }
            }

            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => true, 'data' => $result];
            }
        } catch (\Exception $e) {
            Yii::error("ActionImportSales Exception: " . $e->getMessage());
            if (!function_exists('fastcgi_finish_request')) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
    }
}
