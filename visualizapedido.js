//Polyfilling

if (!String.prototype.trim) { //https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
    String.prototype.trim = function () {
        return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
    };
}

if (!Array.prototype.swap) {
    Array.prototype.swap = function(a,b){
        //don't attempt to swap if indexes invalid
        if(Math.min(a,b)<0 || Math.max(a,b)>=this.length)
        return this;
        //indexes are valid, then swap
        var tmp = this[a];
        this[a] = this[b];
        this[b] = tmp;
        return this;
    }
}

if (!Array.prototype.remove) {
    Array.prototype.remove = function(a){
        //don't attempt to swap if index is invalid
        if(a<0 || a>=this.length)
        return undefined;
        //index is valid, then remove
        return this.splice(a, 1)[0];
    }
}

if (!Array.prototype.contains) {
    Array.prototype.contains = function(a){
        var ret = false
        this.forEach(function(elem){
            if(elem===a)
            ret = true;
        });
        return ret;
    }
}

if (!String.prototype.escapeHtml) { // http://stackoverflow.com/a/6234804
    String.prototype.escapeHtml = function () {
        return this
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    };
}

// End of Polyfilling

function escapeHtml(unsafe){ // http://stackoverflow.com/a/6234804
    return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function ensureArray(variable){
    if(
        variable !== null
        &&
        typeof(variable) === "object"
        &&
        (
            variable.constructor === Array
            ||
            (
                variable.prop
                &&
                variable.prop.constructor === Array
            )
        )
    )
    return variable;
    else
    return new Array();
}

var pedidos = [];
var pedidoCache = {};

function viewUpdate(apiid){
    var v = '';
    if(typeof(apiid)!=='number'){
        v+='<p><a href="javascript:viewUpdate()" class="btn btn-outline-secondary">&lt; Voltar</a></p>';
        v+='<table class="table table-bordered table-striped">';
        v+='<thead class="thead-default">';
        v+='<tr>';
        v+='<th>Número do pedido</th>';
        v+='<th>Cliente</th>';
        v+='<th>Visualizar</th>';
        v+='</tr>';
        v+='</thead>';
        v+='<tbody>';
        ensureArray(pedidos).forEach(function(pedido){
            v+='<tr>';
            v+='<th scope="row">'+(pedido.noped.escapeHtml())+'</th>';
            v+='<td>'+(pedido.cliente.escapeHtml())+'</td>';
            v+='<td><a href="javascript:viewUpdate('+pedido.apiid+')">Visualizar</a></td>';
            v+='</tr>';
        });
        v+='</tbody>';
        v+='</table>';
    }else{
        if(typeof(pedidoCache[apiid])==='undefined'){
            jQuery.get(
                restApi,
                {
                    'pedido':apiid,
                },
                function(data, textStatus, jqXHR){
                    pedidoCache[apiid] = data;
                    viewUpdate(apiid);
                },
                'json'
            );
            return
        }else{
            var pedido = pedidoCache[apiid];
            v+='<p><a href="javascript:viewUpdate()" class="btn btn-info">&lt; Voltar</a></p>';
            v+='<p><b>Número do pedido:</b> <span>'+pedido.noped.escapeHtml()+'</span></p>';
            v+='<p><b>Cliente:</b> <span>'+pedido.cliente.escapeHtml()+'</span></p>';
            v+='<p><b>Itens:</b></p>';
            v+='<table class="table table-bordered table-striped">';
            v+='<thead class="thead-default">';
            v+='<tr>';
            v+='<th>Código do item</th>';
            v+='<th>Descrição</th>';
            v+='<th>Marca</th>';
            v+='<th>Quantidade</th>';
            v+='</tr>';
            v+='</thead>';
            v+='<tbody>';
            if(pedido.items.length){
                pedido.items.forEach(function(item){
                    v+='<tr>';
                    v+='<th scope="row">'+item.cod.escapeHtml()+'</th>';
                    v+='<td>'+item.descr.escapeHtml()+'</td>';
                    v+='<td>'+item.marca.escapeHtml()+'</td>';
                    v+='<td>'+item.qtd.escapeHtml()+'</td>';
                    v+='</tr>';
                });
            }else{
                v+='<tr class="text-muted" style="text-align: center; font-style: italic;"><td colspan="4">Nenhuma linha para exibir</td></tr>';
            }
            v+='</tbody>';
            v+='</table>';
        }
    }
    $('#content').html(v);
    return
};
viewUpdate();

jQuery.get(
    restApi,
    {},
    function(data, textStatus, jqXHR){
        pedidos = data;
        viewUpdate();
    },
    'json'
);
