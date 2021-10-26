<?php if (!defined("_SEGURO")) die("Acesso Negado");
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
    evento('orçamentos','tela de orçamento');
    
?>
<script language="javascript"> 
    $().ready(function () {     
        var i=0;
        var valorTotal = 0.0;
        $('#ValorTotal').text(valorTotal);
        $('select[name=cliente]').hide();  
        $('input[name=cliente]').focus();
        $('input[name=selecionar_cliente]').click(function() {            
            $('input[name=cliente]').toggle();
            $('select[name=cliente]').toggle();
            $('input[name=selecionar_cliente]').toggle()
        });
        $('select[name=cliente]').change(function() {
             $('input[name=cliente]').val($(('select[name=cliente] option:selected')).text());
             $('input[name=cliente]').toggle();
             $('select[name=cliente]').toggle();
             $('input[name=selecionar_cliente]').toggle()

        });
        
        $('input[value=remover]').click(function () {
            window.alert('teste');
        })

        $('input[name=adicionar1]').click(function() {           
            preco = $(('select[name=equipamento] option:selected')).val();
            desc =  "Orçamento - "+$(('select[name=equipamento] option:selected')).text();
            qtd= $('input[name=qtd1]').val();
            (qtd==0||qtd==''||qtd<0)?qtd=1:qtd;
            unid = parseFloat(preco);
            preco = (parseFloat(preco)*parseFloat(qtd));            
            $('#relacao_orcamento').append("<tr><td>"+desc+"</td><td><span id=qtd"+(++i)+">"+qtd+"</span></td><td>R$ "+unid+" </td><td>R$ <span id=prc"+(i)+">"+preco+"</span></td><td><input type=button id=remover"+(i)+" value=remover preco="+preco+"></td>");
            valorTotal += preco;
            $('#ValorTotal').text(valorTotal);
            $("#remover"+(i)).on('click', function() { 
               valorTotal -= parseFloat($(this).attr('preco'));
               $('#ValorTotal').text(valorTotal);
               $(this).parent().parent().remove();                               
            });                              
        })

        $('input[name=adicionar2]').click(function() {           
            preco = $(('select[name=servico] option:selected')).val();
            desc =  $(('select[name=servico] option:selected')).text();           
            qtd= $('input[name=qtd2]').val();
            (qtd==0||qtd==''||qtd<0)?qtd=1:qtd;
            unid = parseFloat(preco);
            preco = (parseFloat(preco)*parseFloat(qtd));            
            $('#relacao_orcamento').append("<tr><td>"+desc+"</td><td><span id=qtd"+(++i)+">"+qtd+"</span></td><td>R$ "+unid+" </td><td>R$ <span id=prc"+(i)+">"+preco+"</span></td><td><input type=button id=remover"+(i)+" value=remover preco="+preco+"></td>");
            valorTotal += preco;
            $('#ValorTotal').text(valorTotal);
            $("#remover"+(i)).on('click', function() { 
               valorTotal -= parseFloat($(this).attr('preco'));
               $('#ValorTotal').text(valorTotal);
               $(this).parent().parent().remove();                               
            });                              
        })

    })    
</script>
<h2>orçamento</h2>
<br />
<div id="specialcontent">
<h3>Nome do Cliente </h3>
<input  name="cliente" size="55"></input> <input name="selecionar_cliente" type="button" value="+preencher">
<select name="cliente">
    <?php
    ////R$ " . number_format($show['preco'], 2, ',', '.');
    $yes = @mysql_query("select * from clientes order by cliente;");
    if ($yes) {
        while($show = mysql_fetch_array($yes, MYSQL_ASSOC)) {
            echo "<option>";
            echo $show['cliente'];
            echo "</option>";
            
        }
    }
    ?>
</select>
<h3>Tipo de Equipamento </h3>
<select name="equipamento"  style="width:350px;">
    <?php
    $yes = @mysql_query("select * from servicos where tipo = 'P' order by descricao;");
    if ($yes) {
        while($show = mysql_fetch_array($yes, MYSQL_ASSOC)) {
            echo "<option value=".$show['preco'].">";
            echo utf8_encode($show['descricao']);
            echo "</option>";
            
        }
    }
    ?>
</select> Qtd <input name="qtd1" value="1" style="width: 25px;"></input>&nbsp;
<input type="button" name="adicionar1" value="+adicionar">
<h3>Serviço executado </h3>
<select name="servico"  style="width:350px;">
    <?php
    $yes = @mysql_query("select * from servicos where tipo = 'S' order by descricao;");
    if ($yes) {
        while($show = mysql_fetch_array($yes, MYSQL_ASSOC)) {
            echo "<option value=".$show['preco'].">";
            echo utf8_encode($show['descricao']);
            echo "</option>";
            
        }
    }
    ?>
</select> Qtd <input name="qtd2" value="1" style="width: 25px;"></input>&nbsp;
<input type="button" name="adicionar2" value="+adicionar">
<h3>Detalhamento </h3>
<table border="0" id="relacao_orcamento">
    <tr>     
    <td width="300">Serviço</td>
    <td width="40px">Qtd</td>
    <td width="60px">Unid</td>
    <td width="60px">Valor</td>
    <td></td>
    </tr>
    </table>
<h3>Valor Total R$ <span id="ValorTotal"></span></h3>
</div>
