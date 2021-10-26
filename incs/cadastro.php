<script language="javascript">
function validaForm(){
d = document.cadastro;
if (d.cliente.value== ""){
                   alert('Pelo menos o campo "Nome do Cliente" deve ser preenchido.');
                   d.cliente.focus();
                   return 0;
      }
}
</script>
<h2>cadastro de clientes</h2>
<h3>formulário de cadastro</h3>
<?php
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");

 if ($_POST['registrar']) {
   $ehneh = mysql_query("SELECT * FROM `clientes` WHERE `cliente` = '".$_POST['cliente']."'");
   $myx = mysql_fetch_array($ehneh, MYSQL_ASSOC);
   if ($myx['cliente'] == "") {
   $ox = mysql_query("SELECT *FROM `clientes`  ORDER BY `clientes`.`id` DESC");
   $mow = mysql_fetch_array($ox, MYSQL_ASSOC);
   $mow['id']++;
    if(mysql_query("INSERT INTO `".$sql['base']."`.`clientes` ( `id` , `cliente` , `cnpj_cpf` , `endereco` , `telefone`, `obs`) VALUES ( '".$mow['id']."', '".$_POST['cliente']."', '".$_POST['cpf_cnpj']."', '".$_POST['endereco']."', '".$_POST['telefone']."', '".$_POST['obs']."');",$conexao)) {
	    echo "<font color=red><b>O cadastro foi inserido com sucesso</b></font><br />";
	    evento('cadastrar cliente','cliente inserido: '.$_POST['cliente']);
	}
    
   }
 } else {
	   evento('cadastrar cliente','formulário de cadastro de cliente');
 }
?>
<table>
<form name="cadastro" action="?m=cadastro" method="post">
<tr>
<td>Nome do Cliente</td>
</tr><tr>
<td><input name="cliente" type="text" size="60"></td>
</tr><tr><td>Endereço</td>
</tr><tr><td><input name="endereco" type="text" size="60"></td>
</tr><tr><td>Telefone(s)</td>
</tr><tr><td><input name="telefone" type="text" size="60"></td>
</tr><tr><td>Observações</td>
</tr><tr><td>
<textarea name="obs" rows="10" cols="53">
</textarea>
</td></tr>
<tr>
<td>
<input type="submit" name="registrar" onclick="if (validaForm()==0) return false; else return true;" onkeypress="if (validaForm()==0) return false; else return true;" value="Salvar Registro" />
</td>
</tr>
</form>
</table>

