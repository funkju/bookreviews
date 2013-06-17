<?php


 class Logger {

    const DEBUG  = 3;
    const HIGH   = 2;
    const NORMAL = 1;

    private static $instance;

    private $fh;

    private function __construct(){
        $this->fh = fopen($_SERVER['DOCUMENT_ROOT'].URI."/resources/log/messages","a");
    }

    public function __destruct(){
        fclose($this->fh);
    }

    static $format = "%T%, %I%, %C%::%F%, %L%, %U%, %M%";


    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new Logger();
        }

        return self::$instance;
    }



    public function log($msg, $level, $include_dump = false){
        if(isset($_SESSION['JR']) && $level <= BookReviews::LOG_LEVEL){

            $time = date("Y/m/d H:i:s");

            $trace = debug_backtrace();
            $class = $trace[1]['class']; 
            $function = $trace[1]['function'];
            $lineno  = $trace[1]['line'];

            $ip = $_SERVER['REMOTE_ADDR'];

            $user = $_SESSION['JR']->getUser();
            $user = $user['username'];


    
            $line = Logger::$format;
            $line = str_replace("%T%",$time, $line);
            $line = str_replace("%I%",$ip, $line);
            $line = str_replace("%C%",$class, $line);
            $line = str_replace("%F%",$function, $line);
            $line = str_replace("%L%",$lineno, $line);
            $line = str_replace("%U%",$user, $line);
            $line = str_replace("%M%",$msg, $line);
        
            if($include_dump){ 
                $debug = $this->compress_trace();
                $line .=  ", $debug";
            }

            $line .= "\n";

            fwrite($this->fh, $line);
        }
    }


    
    
    private function compress_trace(){
        $arr = debug_backtrace();
        //Only keep first 2 
        $arr = array($arr[0],$arr[1]);
        $str = print_r($arr,true);

        $gzencode = gzdeflate($str,9);
        $base64 = base64_encode($gzencode);

        return $base64;
    }

    //More for reference than anything
    private function decompress_trace($str){
        $gz = base64_decode($str);
        $trace = gzinflate($gz);

        return $trace;
    }










 }






?>
