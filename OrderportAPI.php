<?php
class OrderportAPI {

    private $api_url = 'https://wwwapps.orderport.net/api'; // Replace with your API endpoint URL

    public function __construct() {
        // Constructor code, if needed
    }

    public function callListOfAllProductsApi() {
        // Initialize cURL

       $token= $this->generateAuthorizeBearer();
       if($token){
             $curl = curl_init();

              curl_setopt_array($curl, array(
                  CURLOPT_URL => $this->api_url.'/catalog/webstore-catalog',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  CURLOPT_HTTPHEADER => array(
                    'api-version: 1.0.0',
                    'Content-Type: text/plain',
                    'Authorization: Bearer '.$token
                  ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }else{
            
             $arrayOfData=["error"=>"Invalid Token! Try Again","code"=>404];
             return json_encode($arrayOfData);
            
        }
    }

    public function generateAuthorizeBearer() {
        $order_port_client_id = get_option('order_port_client_id');
        $order_port_access_key = get_option('order_port_access_key');
        $order_port_token = get_option('order_port_token');

        if($order_port_client_id && $order_port_access_key && $order_port_token ){
            $arrayOfData=["ClientId"=> $order_port_client_id,"ApiKey"=> $order_port_access_key,"ApiToken"=> $order_port_token];
            $jsonObj=json_encode($arrayOfData);
            // generate Bearer token
            return base64_encode($jsonObj);
        }
    }
}
// Initialize the API integration
$api_integration = new OrderportAPI();
