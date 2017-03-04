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

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="jquery/jquery-3.1.1.min.js"></script>
    <script src="tether-1.3.3/dist/js/tether.min.js"></script>
    <link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap-theme.min.css">
    <script src="bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"></script>
    <style>
    .font1rem {
        font-size: 1rem;
    }
    </style>
    <title>
        Pedido
    </title>
</head>
<body>
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">
                Pedido
            </h1>
        </div>
    </div>
    <div class="container">
        <form method="get">
            <div class="form-group">
                <label for="noped">&#8470; pedido:</label>
                <input id="noped" name="noped" type="number" min="0" class="form-control" value="<?php echo htmlspecialchars(strval(arrayGetFallback($_REQUEST,'noped',time()))); ?>">
            </div>
            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <input id="cliente" name="cliente" type="text" class="form-control" value="<?php echo htmlspecialchars(strval(arrayGetFallback($_REQUEST,'cliente',''))); ?>">
            </div>
            <input id="items" name="items" class="form-control" type="hidden" value="<?php echo htmlspecialchars(json_encode($items)); ?>">
            <div class="container">
                <table class="table table-bordered table-striped">
                    <thead class="thead-default">
                        <tr>
                            <th>Código do item</th>
                            <th>Descrição</th>
                            <th>Marca</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item):?>
                            <tr>
                                <th scope="row"><?php echo $item['cod']?></th>
                                <td><?php echo $item['descr']?></td>
                                <td><?php echo $item['marca']?></td>
                                <td><?php echo $item['qtd']?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <div class="blockquote">
                <p>Adicionar item ao pedido</p>
                <div class="container font1rem">
                    <div class="form-group">
                        <label for="coditem">Código do item:</label>
                        <input id="coditem" name="coditem" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="descr">Descrição:</label>
                        <input id="descr" name="descr" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input id="marca" name="marca" type="text" class="form-control">
                    </div>
                    <label for="qtd">Quantidade:</label>
                    <div class="form-group">
                        <input id="qtd" name="qtd" min="0" value="1" type="number" class="form-control">
                    </div>
                    <button class="btn btn-info" name="action" value="adcItem">Adicionar</button>
                </div>
            </div>
            <div class="form-group">
                <p>
                    <button class="btn btn-success"  name="action" value="criarPedido">
                        Finalizar criação do pedido
                    </button>
                </p>
                <p>
                    <a class="btn btn-danger" href="?">
                        Cancelar este pedido
                    </a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
