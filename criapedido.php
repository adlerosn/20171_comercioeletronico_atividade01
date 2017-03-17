<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function arrayGetFallback(array $arr, $key, $fallback){
    if(array_key_exists($key,$arr)){
        return $arr[$key];
    } else {
        return $fallback;
    }
}

$sn = explode('/',$_SERVER['SCRIPT_NAME']);
array_pop($sn);
$sn = implode('/',$sn);

$restApi = 'api.php';
$restApiFullUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$sn.'/'.$restApi;

$items = [];
if(array_key_exists('items',$_REQUEST)){
    $items = json_decode($_REQUEST['items'],true);
}
if(arrayGetFallback($_REQUEST,'action','')=='adcItem'){
    $novoItem = [
        'cod' => arrayGetFallback($_REQUEST,'coditem',''),
        'descr' => arrayGetFallback($_REQUEST,'descr',''),
        'marca' => arrayGetFallback($_REQUEST,'marca',''),
        'qtd' => arrayGetFallback($_REQUEST,'qtd',''),
    ];
    $items[]=$novoItem;
}
if(arrayGetFallback($_REQUEST,'action','')=='criarPedido'){
    $data=[
        'noped'=>arrayGetFallback($_REQUEST,'noped',''),
        'cliente'=>arrayGetFallback($_REQUEST,'cliente',''),
        'items'=>$items,
    ];
	$options = [
		'http' => [
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query(['pedido'=>json_encode($data)]),
			'timeout' => 5,
		]
	];
    $context = stream_context_create($options);
    $apiUseReply = file_get_contents($restApiFullUrl, false, $context);
    $items = [];
    $_GET = [];
    $_POST = [];
    $_REQUEST = [];
}

include('criapedido.view.php');
