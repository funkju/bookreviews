<?php
    include "Postage.class.php";

    $postage = new Postage();

    $fromAddr = array(
        'FullName'   => "Justin Funk",
        'Department' => "Department of Statistics",
        'Company'    => "Iowa State University",
        'Address1'   => "2123b Snedecor Hall",
        'City'       => "Ames",
        'State'      => "IA",
        'ZIPCode'    => 50010
    );
       
    $to = array(
        'FullName'   => "Jason Funk",
        'Company'    => "Palisade Systems",
        'Address1'   => "400 Locust Street",
        'Address2'   => "Suite 700",
        'City'       => "Des Moines",
        'State'      => 'IA',
        'ZIPCode'    => '50039'
    );
    $toAddr = $postage->cleanseAddress($to);
    $toAddr = $toAddr['Address'];
   

    $rate = array(
        'FromZIPCode' => $fromAddr['ZIPCode'],
        'ToZIPCode'   => $toAddr['ZIPCode'],
        'WeightOz'    => 16,
        'PackageType' => 'Package',
        'ShipDate'    => date('Y-m-d'),
        'ServiceType' => 'US-MM'

    );
    $rates = $postage->getRates($rate);

    $rate = array();
    //This means that only one rate was given
    if(isset($rates['Rate']['ServiceType'])){
        $rate = $rates['Rate'];
    } else {
        foreach($rates['Rate'] as $r){
            if(!isset($rate['Amount']) || $rate['Amount'] > $r['Amount']){
                $rate = $r;
            }
        }
    }

    //We don't want any addons.
    $rate['AddOns'] = array();
  
    $indicium = $postage->createIndicium($rate, $fromAddr, $toAddr, 'Pdf');


    if(isset($indicium['URL'])){
        error_reporting(E_ERROR);
        $contents = file_get_contents($indicium['URL']);
        error_reporting(E_NOTICE);

        $h = fopen("../labels/label_".$indicium['StampsTxID'].".pdf","w");
        fwrite($h, $contents);
        fclose($h);
    }

    echo "Label is <a href='../labels/label_".$indicium['StampsTxID'].".pdf'>here</a>.<br>";
    


?>
