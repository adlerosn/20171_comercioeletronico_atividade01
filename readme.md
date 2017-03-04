7º período \ Comércio Eletronico \ 2017-1 \ Atividade 01
=============================================

##Como executar numa única máquina local:

1. Inicie uma instância do Apache com o php instalado ([download](https://www.apachefriends.org/)), ou qualquer servidor Web de sua preferência devidamente configurado.
 * Copie o conteúdo do repositório para a pasta servida.
 * Acesse o conteúdo servido (geralmente é [http://localhost/](http://localhost/) ou algum subdiretório)


##Como executar em 3 máquinas distintas:
As pastas `bootstrap-4.0.0-alpha.6-dist`, `jquery` e `tether-1.3.3` devem estar em todas as máquinas, todas executando um software de servidor HTTP de preferência do administrador de cada máquina.

### Máquina 1:
 * `api.php`
 * `pedidos.json`

Nenhuma alteração necessária.

### Máquina 2:
 * `bootstrap-4.0.0-alpha.6-dist`
 * `jquery`
 * `tether-1.3.3`
 * `criapedido.php`

Altere a linha 19 de `criarpedido.php` para que a variável `$restApiFullUrl` receba o endereço da API sendo executada pela **máquina 1**. Exemplo:
```php
$restApiFullUrl = 'http://192.168.0.4/api.php';
```

### Máquina 3:
 * `bootstrap-4.0.0-alpha.6-dist`
 * `jquery`
 * `tether-1.3.3`
 * `visualizapedido.html`
 * `visualizapedido.js`

Altere a linha 12 de `visualizapedido.html` para que a variável `restApi` receba o endereço da API sendo executada pela **máquina 1**. Exemplo:
```js
var restApi = 'http://192.168.0.4/api.php';
```

Finalmente, de uma quarta máquina (ou uma das três acima mencionadas) um usuário acessa, com um navegador de internet, o serviço provido pelas máquinas 2 e/ou 3.

---

Instruções solicitadas pelo professor ao término da última aula.
