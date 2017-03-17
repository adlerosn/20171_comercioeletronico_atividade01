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
