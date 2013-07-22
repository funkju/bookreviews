<?php
//    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/BookReviews.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/stamps.com/nusoap.php");

    //@TODO WRITE TESTER
    class Postage {
        const WSDLPATH = 'https://streaming.stat.iastate.edu/journalReviews/resources/stamps.com/swsimv10.wsdl';
        const INTEGRATIONID = '804C4D67-37ED-4735-8F99-B73FC1DD796F';
        const USERNAME = 'IAStateU';
        const PASSWORD = 'password1';
        
        
        const FromZIPCode = '50011';
        const IMAGE_TYPE = 'Pdf';

        public static $FROM_ADDRESS = array(
            'FullName' => 'Department of Statistics',
            'Address1' => 'Snedecor Hall',
            'City'     => 'Ames',
            'State'    => 'IA',
            'ZIPCode'  => 50011
        );
        
        private $authenticator;

        private $client;
        public $error;
        public  $last_debug;
        public  $last_request;
        public  $last_response;



        /** 
         * __construct
         *
         * The constructor creates a soapclient instance
         * and assigns it to the client class variable
         *
         * Then it tries to authenticate the user.
         *
         * @return void
         */
        public function __construct(){
            $this->client = new soapclient(self::WSDLPATH,true);
            
            $this->authenticateUser();
        }


        /**
         * authenticateUser
         *
         * Calls STAMPS.COM 'AuthenticateUser' method
         * uses the INTEGRATIONID, USERNAME, and PASSWORD
         * class constances for credentials
         *
         * @return boolean
         */
        private function authenticateUser(){
            $result = $this->call('AuthenticateUser', array('Credentials' =>
                                                          array('IntegrationID' => self::INTEGRATIONID,
                                                                'Username'      => self::USERNAME,
                                                                'Password'      => self::PASSWORD)));
            return true;
        }

        /**
         * cleaseAddress
         *
         * Calls STAMPS.COM 'CleaseAddress' method
         * 
         * @param  array  $address  A STAMPS.COM Address Data Array
         * @return array            A STAMPS.COM Cleansed Address Response Array
         */
        public function cleanseAddress($address){
            $result = $this->call('CleanseAddress', array('Address'=> $address));

            //Do not return Authenticator String
            unset($result['Authenticator']);

            return $result;
        }
  

        /**
         * getRates
         *
         * Calls STAMPS.COM 'GetRates' method
         *
         * @param  array  $rate   A STAMPS.COM Rate Data Array
         * @return array          A STAMPS.COM Rate or Array of Rates
         */
        public function getRates($rate){
            $result = $this->call('GetRates', array('Rate'=>$rate));
        
            if(isset($result['Rates']['Rate']) && !isset($result['Rates']['Rate']['Amount'])){
                usort($result['Rates']['Rate'], array("Postage","sortRates"));
            }

            return $result['Rates'];
        }


        /**
         * createIndicium
         *
         * Calls STAMPS.COM 'CreateIndicium' method
         *
         * @param   array   $rate         A single Rate from getRates method
         * @param   array   $fromAddr     An Address from cleaseAddress method
         * @param   array   $toAddr       An Address from cleaseAddress method
         * @param   string  $imageType    A STAMPS.COM ImageType 
         * @return  array                 A STAMPS.COM CreateIndiciumResponse Data array
         */
        public function createIndicium($rate, $fromAddr, $toAddr, $imageType){

            unset($fromAddr['Country']);
            unset($toAddr['Country']);

            $arr =  array('IntegratorTxID'       => $this->genIntegratorTxID(),
                          'Rate'                 => $rate,
                          'From'                 => $fromAddr,
                          'To'                   => $toAddr,
                          'ImageType'            => $imageType
                          );

            $result = $this->call('CreateIndicium', $arr);
 

            return $result;
        }

        
        /**
         * genIntegratorTxID
         *
         * Generates a unique integrator id to be used
         * by this class and STAMPS.COM to track and 
         * manage postage orders
         *
         * MD5 sum of the current authenticator and UNIX Timestamp
         *
         * @return string
         */
        private function genIntegratorTxID() {
            return md5($this->authenticator).time();
        }

        /**
         * call
         *
         * Workhorse of the Postage class
         *
         * Makes calls to the soapclient to the
         * specified method with the specified
         * parameters.
         *
         * @throws Exception
         * @return array  
         */
        private function call($method, $params){
            //to prevent recursion
            if($method != "AuthenticateUser"){
                //Verify that we are authenticated
                $this->checkAuthenticator();
            }

            //Fill Authenticator in the params
            $params['Authenticator'] = $this->authenticator;

            //Make the call
            $response = $this->client->call($method, $params);


            //throw exception on fault
            if($this->client->fault){
                throw new Exception($response['faultstring']);
            } else {
                $err = $this->client->getError();

                //throw exception on error
                if($err){
                    throw new Exception($err);
                }
            }

            //Save variables from response
            $this->authenticator = $response['Authenticator'];
            $this->last_debug = $this->client->debug_str;
            $this->last_request = $this->client->request;
            $this->last_response = $this->client->response;


            //Return response
            return $response;
        }



        /**
         * checkAuthenticator
         *
         * Checks the expiration of the 
         * authenticator that we have in store.
         * If it has expired- or the authenticator
         * is invalid -> then reauthenticate.
         *
         * @return void
         */
        private function checkAuthenticator(){
            $axer = $this->authenticator;

            //Split authenticator into parts
            $parts = explode("&",$axer);

            //If there are no parts, the authenticator is invalid
            if(count($parts) > 1){
                   
                //if the first part has exp= then pull out the expiration
                //else it is invalid
                if(strpos($parts[0],"exp=") !== false){
                    $exp = str_replace("exp=","",$parts[0]);
                } else {
                    $this->authenticateUser();
                }

                //if the expiration timestamp has passed, reauthenticate
                if(time() > $exp) $this->authenticateUser();
            } else {
                $this->authenticateUser();
            }
        }



        static function sortRates($a, $b){

            return $a['Amount'] > $b['Amount'];
        }
    }














































?>
