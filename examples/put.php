<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(realpath(dirname(__FILE__)) . '/../vendor/autoload.php');
require_once(realpath(dirname(__FILE__)) . '/../config.inc.php');

$ApidaeMetadata = new \PierreGranger\ApidaeMetadata(array_merge($configApidaeMetadata, array('debug' => true, 'timer' => true)));

$ApidaeMetadata->start(__FILE__);

$noeud = 'test-pg';
$offre = 866995;

$mdCpt = 1;
function showMd()
{
    global $ApidaeMetadata, $mdCpt, $offre, $noeud;
    $ApidaeMetadata->start('get ' . $mdCpt);
    try {
        $metadonnee = $ApidaeMetadata->get($offre, $noeud);
    } catch (\Exception $e) {
        echo '<pre>' . print_r($e, true) . '</pre>';
    }
    echo '<pre style="background:#498248;color:white;">' . print_r($metadonnee, true) . '</pre>';
    $ApidaeMetadata->stop('get ' . $mdCpt++);
}

echo '<h1>Valeur origine</h1>';
showMd();

$ApidaeMetadata->start('delete');
echo '<h1>Delete...</h1>';
$ApidaeMetadata->delete($offre, $noeud);
showMd();
$ApidaeMetadata->stop('delete');

// Insert single
$ApidaeMetadata->start('insert single');
echo '<hr /><h1>Insert general (single)</h1>';
$general = <<<'JSON'
        {"infoGenerale":"Ceci est une information a portée générale"}
    JSON;

try {
    $res = $ApidaeMetadata->put($offre, $noeud, http_build_query(array('general' => $general)));
} catch (\Exception $e) {
    echo '<h1>' . __FILE__ . ':' . __LINE__ . '</h1>';
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    return;
}
$ApidaeMetadata->stop('insert single');

showMd();

// Insert single
$ApidaeMetadata->start('insert single membre');
echo '<hr /><h1>Insert membre (single)</h1>';
$membre = <<<'JSON'
        {"infoGenerale":"Info Single membre 1157"}
    JSON;

try {
    $res = $ApidaeMetadata->put($offre, $noeud, http_build_query(array('membres.membre_1157' => $membre)));
} catch (\Exception $e) {
    echo '<h1>' . __FILE__ . ':' . __LINE__ . '</h1>';
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    return;
}
$ApidaeMetadata->stop('insert single membre');

showMd();


$ApidaeMetadata->start('insert membres');
// Insert type membres
echo '<hr /><h1>Insert membres</h1>';
$membres = <<<'JSON'
    [
        {
            "targetId":21,
            "jsonData":"{\"info Membre\":\"Ceci est une information à destination du membre 21\"}"
        },
        {
            "targetId":1157,
            "jsonData":"{\"info Membre\":\"Ceci est une information à destination du membre 1157 (Apidae)\"}"
        }
    ]
    JSON;

try {
    $res = $ApidaeMetadata->put($offre, $noeud, http_build_query(array('membres' => $membres)));
} catch (\Exception $e) {
    echo '<h2>' . __FILE__ . ':' . __LINE__ . '</h2>';
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    return;
}
$ApidaeMetadata->stop('insert membres');

showMd();

// Insert type projet

$ApidaeMetadata->start('insert projet');
echo '<hr /><h1>Insert projet</h1>';
$projets = <<<'JSON'
    [
        {
            "targetId":1337,
            "jsonData":"{\"info Projet\":\"Ceci est une information à destination du projet 1337\"}"
        },
        {
            "targetId":5321,
            "jsonData":"{\"info Projet\":\"Ceci est une information à destination du projet 5321 (Apidae MD test lecture)\"}"
        }
    ]
    JSON;

try {
    $res = $ApidaeMetadata->put($offre, $noeud, http_build_query(array('projets' => $projets)));
} catch (\Exception $e) {
    echo '<h2>' . __FILE__ . ':' . __LINE__ . '</h2>';
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    return;
}

$ApidaeMetadata->stop('insert projet');

showMd();

// Insert multiple

$ApidaeMetadata->start('insert multiple');
echo '<hr /><h1>Insert multiple</h1>';
$node = <<<'JSON'
    {
        "general" : "{\"info\":\"général par insertion multiple\"}",
        "membres": [
            {
                "targetId":21,
                "jsonData":"{\"info\":\"Membre 21 par insertion multiple\"}"
            },
            {
                "targetId":1157,
                "jsonData":"{\"info\":\"membre 1157 par insertion multiple\"}"
            }
        ],
        "projets" : [
            {
                "targetId":1337,
                "jsonData":"{\"info\":\"Projet 1337 par insertion multiple\"}"
            },
            {
                "targetId":5321,
                "jsonData":"{\"info\":\"projet 5321 par insertion multiple\"}"
            }
        ]
    }
    JSON;

echo '<pre><code>' . $node . '</code></pre>';

try {
    $res = $ApidaeMetadata->put($offre, $noeud, http_build_query(array('node' => $node)));
} catch (\Exception $e) {
    echo '<h2>' . __FILE__ . ':' . __LINE__ . '</h2>';
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    return;
}

$ApidaeMetadata->stop('insert multiple');

showMd();

$ApidaeMetadata->stop(__FILE__);
$ApidaeMetadata->timer();

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">