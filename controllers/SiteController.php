<?php

namespace app\controllers;

use app\services\ConexaService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

        return $this->render('products', [
            'model' => $model,
            'products' => $products->toObject(),
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

        return $this->render('sales', [
            'sales' => $sales->toObject(),
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
            $model = new \app\models\ProductStock();
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $conexaService = new ConexaService();
        
        try {
            $productsDto = $conexaService->products(100, 0);
            $data = $productsDto->getData();
            
            $results = [];
            foreach ($data as $p) {
                $pArray = $p->toArray();
                if (empty($q) || stripos($pArray['name'], $q) !== false) {
                    $results[] = ['id' => $pArray['productId'], 'text' => $pArray['name']];
                }
            }
            
            return ['results' => array_values($results)];
        } catch (\Exception $e) {
            return ['results' => []];
        }
    }

    /**
     * Action to launch a sale and decrement stock
     */
    public function actionLaunchSale()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $productId = $request->post('product_id');
            $qtd = (int) $request->post('qtd');
            $observation = $request->post('observation');

            $balance = \app\models\ProductStock::getStockBalance($productId);

            if ($qtd > $balance) {
                Yii::$app->session->setFlash('error', "Quantidade solicitada da venda ($qtd) é maior que o saldo em estoque ($balance).");
            } else {
                $model = new \app\models\ProductStock();
                $model->product_id = $productId;
                $model->qtd = -$qtd;
                $model->observation = $observation ?: 'Venda lançada';
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Venda lançada com sucesso! O estoque foi reduzido.');
                } else {
                    $errors = current($model->getFirstErrors());
                    Yii::$app->session->setFlash('error', 'Erro ao salvar venda: ' . $errors);
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['site/sales']);
    }
}
