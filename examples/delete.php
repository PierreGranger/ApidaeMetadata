<?php

    ini_set('display_errors',1) ;
    error_reporting(E_ALL) ;

    require_once(realpath(dirname(__FILE__)).'/../vendor/autoload.php') ;
    require_once(realpath(dirname(__FILE__)).'/../config.inc.php') ;

    $ApidaeMetadata = new \PierreGranger\ApidaeMetadata(array_merge($configApidaeMetadata,Array('debug'=>true))) ;
    
    echo '<h1>Avant...</h1>' ;
    try {
        echo '<pre>'.print_r($ApidaeMetadata->get(4683815,'test-pg'),true).'</pre>' ;
    } catch ( Exception $e ) { echo $e ; }

    echo '<h1>Delete...</h1>' ;
    try {
        $retour = $ApidaeMetadata->delete($offre,$noeud) ;
    } catch ( Exception $e ) { echo $e ; }

    echo '<h1>Après...</h1>' ;
    try {
        echo '<pre>'.print_r($ApidaeMetadata->get(4683815,'test-pg'),true).'</pre>' ;
    } catch ( Exception $e ) { echo $e ; }
