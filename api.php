<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function arrayGetFallback(array $arr, $key, $fallback){
    if($arr && is_array($arr) && array_key_exists($key,$arr)){
        return $arr[$key];
    } else {
        return $fallback;
    }
}

$reqMethod = arrayGetFallback($_SERVER,'REQUEST_METHOD','GET');

$dbFn = './pedidos.json';
$db = json_decode(file_get_contents($dbFn),true);

$return = [];

if($reqMethod==='GET'){
    $offset = intval(arrayGetFallback($_REQUEST,'pedido','-1'));

    if(array_key_exists($offset,$db)){
        $return = $db[$offset];
    }else{
        $return=[];
        foreach($db as $offset=>$pedido){
            $return[$offset]=[
                'apiid'=>$offset,
                'noped'=>$pedido['noped'],
                'cliente'=>$pedido['cliente'],
            ];
        }
    }
}
else if($reqMethod==='POST'){
    $return = false;
    $pedido = json_decode(arrayGetFallback($_REQUEST,'pedido','null'),true);
    if($pedido){
        $db[]=$pedido;
        file_put_contents($dbFn,json_encode($db));
        $return = true;
    }
}

header('Content-Type: application/json');
echo json_encode($return);
