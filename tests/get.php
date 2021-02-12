<?php

    ini_set('display_errors',1) ;
    error_reporting(E_ALL) ;

    require_once(realpath(dirname(__FILE__)).'/../vendor/autoload.php') ;
    require_once(realpath(dirname(__FILE__)).'/../config.inc.php') ;

    $ApidaeMetadata = new \PierreGranger\ApidaeMetadata(array_merge($configApidaeMetadata,Array('debug'=>true,'timer'=>true))) ;

    $ApidaeMetadata->start(__FILE__) ;
    $metadonnee = $ApidaeMetadata->get(4683815,'test-pg') ;
    echo '<pre>'.print_r($metadonnee,true).'</pre>' ;
    $ApidaeMetadata->stop(__FILE__) ;

    $ApidaeMetadata->timer() ;
