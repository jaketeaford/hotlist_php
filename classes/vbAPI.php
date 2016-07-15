<?php
    class vbAPI
    {
        private $apiURL = "http://ec2-54-151-5-248.us-west-1.compute.amazonaws.com/api.php";
        private $apiKey = "bxHhcpa8";
        private $uniqueID = "FireDashDev0";
        private $clientName = "IAManger-Dev";
        private $clientVer = "0.1";
        private $platformName = "example";
        private $platformVer = "0.1";
        private $accessToken = "";
        private $secret = "";
        private $clientID = "";
        private $apiVer = "";

        function __construct(){
            $content = "";

            $request = $this->apiURL.
                '?api_m=api_init'.
                '&clientname='.$this->clientName.
                '&clientversion='.$this->clientVer.
                '&platformname='.$this->platformName.
                '&platformversion='.$this->platformVer.
                '&uniqueid='.$this->uniqueID;

            //execute http request and read response content
            $fp = fopen($request, 'r');

            // parse content
            while ($line = fread($fp, 1024)) {
                $content .= $line;
            }

            $data = json_decode($content);
            $this->accessToken = urlencode($data->apiaccesstoken);
            $this->secret = urlencode($data->secret);
            $this->clientID = urlencode($data->apiclientid);
            $this->apiVer = urlencode($data->apiversion);
        }

        private function createRequestSig($params){
            ksort($params);
            $sigString = http_build_query($params, '', '&');
            $sig = md5($sigString.$this->accessToken.$this->clientID.$this->secret.$this->apiKey);
            return $sig;
        }

        public function execRequest($requestParams){
            $signature = $this->createRequestSig($requestParams);
            $requestString = http_build_query($requestParams);

            $newRequest = '&api_c='.$this->clientID.
                '&api_s='.$this->accessToken.
                '&api_sig='.$signature.
                '&api_v='.$this->apiVer.
                $requestString;

            $requestURL = $this->apiURL.'?api_m='.$requestParams['api_m'];

            $context_options = array(
                'http' => array(
                    'method' => 'POST',
                    'header'=> "Content-type: application/x-www-form-urlencoded",
                    'content' => $newRequest
                )
            );
            $context = stream_context_create($context_options);

            // execute request
            $fp = fopen($requestURL.$newRequest, 'r', false, $context);

            // parse response
            $response = "";
            while ($line = fread($fp, 1024)) {
                $response .= $line;
            }
            fclose($fp);
            $decodedResponse = json_decode($response);

            return $decodedResponse;
        }
    }
?>