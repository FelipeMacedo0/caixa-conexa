<?php
namespace app\services;

use Yii;
use Exception;
use app\dtos\AuthDTO;

class ConexaService {
    private string $urlApi = "";

    public function __construct(){
        $this->urlApi = $_ENV['URL_API_CONEXA'];
    }

    public function login(array $params){
       

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
}