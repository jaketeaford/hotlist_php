<?php
    class vbAPI
    {
        // existing values from vBulletin backend:
        private $apiURL = "http://ec2-54-151-5-248.us-west-1.compute.amazonaws.com/api.php";
        private $apiKey = "bxHhcpa8";
        private $uniqueID = "FireDashDev0";
        private $clientName = "IAManger-Dev";
        private $clientVer = "0.1";
        private $platformName = "example";
        private $platformVer = "0.1";

        // retrieved during api_init:
        private $accessToken = "";
        private $secret = "";
        private $clientID = "";
        private $apiVer = "";

        /*
            vbAPI Class Constructor
            Purpose:
                Does initial api_init call to the vBulletin API and stores the API Access Token,
                API "secret", API Client ID, and API Version so subsequent API calls can be made
                from the same instance. This also allows for multiple API sessions to be used in
                tandem by creating multiple instances of the class.
        */
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


        /*
            createRequestSig
            Input:
                Array of request parameters
            Output:
                A properly-formatted MD5 request signature for the given parameters generated
                using said parameters alongside stored information from the initial api_init call
            Purpose:
                Every vBulletin API request after api_init (done in the constructor) requires a
                request signature, this function builds a for any given array of request
                parameters.
        */
        private function createRequestSig($params){
            ksort($params);
            $sigString = http_build_query($params, '', '&');
            $sig = md5($sigString.$this->accessToken.$this->clientID.$this->secret.$this->apiKey);
            return $sig;
        }


        /*
            execRequest
            Input:
                Array of request parameters (see example for format)
            Output:
                An array containing decoded JSON response data
            Purpose:
                Takes a basic array of request parameters in a specific format, builds the necessary
                request signature, executes the desired API request, and returns the decoded JSON
                response data in an array object (mostly for debugging).

            Input format example:
                array(
                    'api_m' => 'login_login',
                    'vb_login_username' => 'apiTestUser',
                    'vb_login_md5password' => md5('api123test')
                )

                NOTE: The first key must always be "api_m" or else the entire request will fail!
        */
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