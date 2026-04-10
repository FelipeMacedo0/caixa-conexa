<?php

namespace app\controllers;


use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\dtos\SaleDTO;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ProductStock;
use app\services\ConexaService;
use app\services\ImportService;
use app\dtos\ErrorResponseDTO;
use app\dtos\AuthDTO;

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
                'only' => ['logout', 'products', 'sales', 'add-stock', 'search-products', 'search-customers', 'store-sale', 'import-products'],
                'rules' => [
                    [
                        'actions' => ['logout', 'products', 'sales', 'add-stock', 'search-products', 'search-customers', 'store-sale', 'import-products'],
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

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        $conexaService = new ConexaService();

        $products = $conexaService->products($limit, $offset);
        
        $productsObj = ($products instanceof ErrorResponseDTO) 
            ? (object)['data' => [], 'pagination' => null] 
            : $products->toObject();

        return $this->render('products', [
            'model' => $model,
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

        $conexaService = new ConexaService();
        $sales = $conexaService->sales($limit, $offset);

        $salesObj = ($sales instanceof ErrorResponseDTO) 
            ? (object)['data' => [], 'pagination' => null] 
            : $sales->toObject();

        return $this->render('sales', [
            'sales' => $salesObj,
            'limit' => $limit,
            'offset' => $offset,
        ]);
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
        $conexaService = new ConexaService();
        
        try {
            $productsDto = $conexaService->products(100, 0, $q);
            $data = $productsDto->getData();
            
            $results = array_map(function($p) {
                $pArray = $p->toArray();
                return [
                    'id' => $pArray['productId'], 
                    'text' => $pArray['name']
                ];
            }, $data);
            
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
        $conexaService = new ConexaService();
        
        try {
            $customersDto = $conexaService->customers(100, 0, $q);
            $data = $customersDto->getData();
            
            $results = array_map(function($c) {
                $cArray = $c->toArray();
                $name = $cArray['name'] ?? '';
                $tradeName = $cArray['tradeName'] ?? '';
                
                $text = $name;
                if ($tradeName && $tradeName !== $name) {
                    $text .= ' (' . $tradeName . ')';
                }
                
                return [
                    'id' => $cArray['customerId'], 
                    'text' => $text
                ];
            }, $data);
            
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

        $productId = $request->post('product_id');
        $customerId = $request->post('customer_id');
        $qtd = (int) $request->post('qtd');
        $observation = $request->post('observation');

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
}
