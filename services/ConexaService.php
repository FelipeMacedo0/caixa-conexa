<?php
namespace app\services;

use Yii;
use Exception;
use app\dtos\AuthDTO;
use app\dtos\SaleDTO;
use app\dtos\SalesDTO;
use app\dtos\ProductsDTO;
use app\dtos\CustomersDTO;
use app\dtos\PaginationDTO;
use app\dtos\PostResponseDTO;

class ConexaService {
    private string $urlApi = "";

    public function __construct(){
        $this->urlApi = $_ENV['URL_API_CONEXA'] ?? "";
    }

    public function login(array $params){
       
        if(empty($params["username"])){
            throw new Exception('Username is empty');
        }

        if(empty($params["password"])){
            throw new Exception('Password is empty');
        }

        try{
            // GET request
            $response = Yii::$app->http->post($this->urlApi . "/auth", [
                "username" => $params["username"],
                "password" => $params["password"]
            ]);

            return AuthDTO::fromArray(array_merge($response['data'], ['statusCode' => $response['statusCode']]));

        }catch(Exception $e){
            throw new Exception("Erro ao logar usuario", 1, $e);
        }
    }

    public function products(int $limit=10, int $offset=0, ?string $name = null){
        $token = Yii::$app->user->identity->access_token;

        if(empty($token)){
            throw new Exception("Usuário não autorizado!");
        }

        try{
            $uri = "/products";

            $params = [
                'limit' => $limit,
                'offset' => $offset
            ];
            
            if ($name !== null && trim($name) !== '') {
                $params['name'] = $name;
            }

            $query = http_build_query($params);

            $endpoint = $uri . '?' . $query;

            // GET request
            $response = Yii::$app->http->get($this->urlApi . $endpoint, [
                "Authorization" => "Bearer " . $token,
            ]);

            $data = $response['data'];

            if(empty($data['pagination'])){
                throw new Exception('Pagination is empty');
            }

            $pagination = PaginationDTO::fromArray($data['pagination']);

            return new ProductsDTO($data['data'], $pagination);

        }catch(Exception $e){
            throw new Exception("Error trying to get products", 1, $e);
        }
    }

    public function customers(int $limit=10, int $offset=0, ?string $name = null){
        $token = Yii::$app->user->identity->access_token;

        if(empty($token)){
            throw new Exception("Usuário não autorizado!");
        }

        try{
            $uri = "/customers";

            $params = [
                'limit' => $limit,
                'offset' => $offset
            ];

            if ($name !== null && trim($name) !== '') {
                $params['name'] = $name;
            }

            $query = http_build_query($params);

            $endpoint = $uri . '?' . $query;

            // GET request
            $response = Yii::$app->http->get($this->urlApi . $endpoint, [
                "Authorization" => "Bearer " . $token,
            ]);

            $data = $response['data'];

            if(empty($data['pagination'])){
                throw new Exception('Pagination is empty');
            }

            $pagination = PaginationDTO::fromArray($data['pagination']);

            return new CustomersDTO($data['data'], $pagination);

        }catch(Exception $e){
            throw new Exception("Error trying to get customers", 1, $e);
        }
    }

    public function sales(int $limit=10, int $offset=0){
        $token = Yii::$app->user->identity->access_token;

        if(empty($token)){
            throw new Exception("Usuário não autorizado!");
        }

        try{
            $uri = "/sales";

            $query = http_build_query([
                'limit' => $limit,
                'offset' => $offset
            ]);

            $endpoint = $uri . '?' . $query;

            // GET request
            $response = Yii::$app->http->get($this->urlApi . $endpoint, [
                "Authorization" => "Bearer " . $token,
            ]);

            $data = $response['data'];

            if(empty($data['pagination'])){
                throw new Exception('Pagination is empty');
            }

            $pagination = PaginationDTO::fromArray($data['pagination']);

            return new SalesDTO($data['data'], $pagination);

        }catch(Exception $e){
            throw new Exception("Error trying to get sales", 1, $e);
        }
    }

    public function storeSale(SaleDTO $sale){
        $token = Yii::$app->user->identity->access_token;

        if(empty($token)){
            throw new Exception("Usuário não autorizado!");
        }

        try{
            $uri = "/sale";

            // POST request
            $response = Yii::$app->http->post($this->urlApi . $uri, [
                    "productId" => $sale->productId,
                    "quantity" => $sale->quantity,
                    "customerId" => $sale->customerId,
                    "notes" => $sale->notes
                ],[
                "Authorization" => "Bearer " . $token,
            ]);

            return PostResponseDTO::fromArray($response['data']);

        }catch(Exception $e){
            throw new Exception("Erro ao salvar venda", 1, $e);
        }
    }
}