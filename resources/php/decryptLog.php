<?php
    if(isset($argv[1])){
        $str = $argv[1];
        $gz = base64_decode($str);
        $trace = gzinflate($gz);

        print $trace;
    }
?>
