<?php
    $dh = opendir(".");

    while( false !== ($f = readdir($dh))) {
        $file_in = $f;
        $file_out = str_replace(".js","",$file_in).".min.js";
        if(strpos($file_in, ".min.js") === false && strpos($file_in,'.js') !== false && strpos($file_in, '.swp') === false) {
            print "$file_in -> $file_out : ";
            $in = filesize($file_in);
            exec("java -jar ./yuicompressor-2.4.6/build/yuicompressor-2.4.6.jar --type=js -o $file_out $file_in");
            $out = filesize($file_out);

            print round((($in-$out)/$in)*100,2) . "% compression\n";
        }
    }

?>
