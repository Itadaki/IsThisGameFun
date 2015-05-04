<?php

include_once './config.php';
include_once './conexion.php';
include_once './getGames.php';

function respuesta($resultados, $plantilla) {
    $file = $plantilla;
    $html = file_get_contents($file);
    foreach ($resultados as $key1 => $valor1)
        {
if (count($valor1) > 1) {
            foreach ($valor1 as $key2 => $valor2) {
                $cadena = "{" . $key1 . " " . $key2 . "}";
                $html = str_replace($cadena, $valor2, $html);
            }
        } else {
            $cadena = '{' . $key1 . '}';
            $html = str_replace($cadena, $valor1, $html);
        }
}
    return $html;
}
////////////


$conexion = conexion();

$best_games = getBestGames();

//var_dump($best_games);
$pastillas['pastillas']='';
foreach ($best_games as $game) {
    $platforms = getPlatforms($game['id']);
    $plat='';
    foreach ($platforms as $platform) {
        $datos = array(
            'id'=>$platform['id'],
            'name'=>$platform['name'],
            'short_name'=>$platform['short_name'],
            'icon'=>$platform['icon']
        );
        $plantilla =  'test_platform.html';
        $plat .= respuesta($datos, $plantilla);
    }
    if($game['totalVotes']!=0){
    $totalVotes = $game['totalVotes'];
    $pPositivos = number_format($game['totalPositiveVotes']*100/$game['totalVotes'], 0);
    $pNegativos = 100 - $pPositivos;
    } else {
        $pPositivos = 50;
        $pNegativos = 50;
    }
    $datos = array(
        'name'=>$game['name'],
        'cover'=>$game['cover'],
        'positivos' =>$pPositivos,
        'negativos'=>$pNegativos,
        'platforms'=>$plat
    );
    $plantilla =  'test_pastilla.html';
    $pastillas['pastillas'] .= respuesta($datos, $plantilla);
    
    
}

    
    print(respuesta($pastillas, 'test.html'));