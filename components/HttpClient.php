<?php
namespace app\components;

use yii\base\Component;
use yii\base\Exception;

class HttpClient extends Component
{
    /**
     * Faz requisição GET
     */
    public function get($url, $headers = [])
    {
        return $this->request('GET', $url, null, $headers);
    }
    
    /**
     * Faz requisição POST
     */
    public function post($url, $data = [], $headers = [])
    {
        return $this->request('POST', $url, $data, $headers);
    }
    
    /**
     * Requisição genérica
     */
    private function request($method, $url, $data = null, $headers = [])
    {
        $ch = curl_init();
        
        // Configurações básicas
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Apenas para testes
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        // Método HTTP
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    $jsonData = json_encode($data);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                    $headers['Content-Type'] = 'application/json';
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        
        // Headers
        if (!empty($headers)) {
            $headerArray = [];
            foreach ($headers as $key => $value) {
                $headerArray[] = "$key: $value";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
        }
        
        // Executa
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        return (object) [
            'statusCode' => $httpCode,
            'data' => json_decode($response, true),
            'raw' => $response
        ];
    }
}