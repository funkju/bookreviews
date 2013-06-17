<?php

function specialTex($str){
   if(is_string($str)){


      $chars = array('À','Á','Â','Ã','Ä','Å');
      $rep = array("\`{A}","\'{A}","\^{A}","\~{A}","\\\"{A}","\\r{A}");

      
      $chars2 = array('È','É','Ê','Ë');
      $rep2 = array("\`{E}","\'{E}","\^{E}","\\\"{E}");


      $chars3 = array('Ì','Í','Î','Ï');
      $rep3 = array("\`{I}","\'{I}","\^{I}","\\\"{I}");

      $chars4 = array("Ñ");
      $rep4 = array("\~{N}");

      $chars5 = array("Ò","Ó","Ô","Õ","Ö");
      $rep5 = array("\`{O}","\'{O}","\^{O}","\~{O}","\\\"{O}");

      $chars6 = array("Ù","Ú","Û","Ü");
      $rep6 = array("\`{U}","\'{U}","\^{U}","\\\"{U}");

      $chars7 = array("Ý");
      $rep7 = array("\'{Y}");

      $chars8 = array("à","á","â","ã","ä","å");
      $rep8 = array("\`{a}","\'{a}","\^{a}","\~{a}","\\\"{a}","\\r{a}");

      $chars9 = array("è","é","ê","ë");
      $rep9 = array("\`{e}","\'{e}","\^{e}","\\\"{e}");

      $chars10 = array("ì","í","î","ï");
      $rep10 = array("\`{i}","\'{i}","\^{i}","\\\"{i}");

      $chars11 = array("ñ");
      $rep11 = array("\~{n}");

      $chars12 = array("ò","ó","ô","õ","ö");
      $rep12 = array("\`{o}","\'{o}","\^{o}","\~{o}","\\\"{o}");

      $chars13 = array("ù","ú","û","ü");
      $rep13 = array("\`{u}","\'{u}","\^{u}","\\\"{u}");

      $chars14 = array("ý","ÿ");
      $rep14 = array("\'{y}","\\\"{y}");

      $chars = array_merge($chars, $chars2, $chars3,$chars4,$chars5,$chars6,$chars7,$chars8,$chars9,$chars10,$chars11,$chars12,$chars13,$chars14);
      $rep = array_merge($rep,$rep2,$rep3,$rep4,$rep5,$rep6,$rep7,$rep8,$rep9,$rep10,$rep11,$rep12,$rep13,$rep14);
      
      $str = str_replace($chars, $rep, $str);
    }
  
    return $str;
}

?>
