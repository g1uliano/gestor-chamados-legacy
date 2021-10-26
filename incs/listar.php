<?php
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");

 if ($_GET['del']!='') {
   evento('listar clientes','cliente excluido: '.$_GET['del']);
   mysql_query("DELETE FROM `clientes` WHERE `id` = '".$_GET['del']."'");
 }

  if ($_POST['registrar']) {
  mysql_query("UPDATE `".$sql['base']."`.`clientes` SET `id` = '".$_POST['id']."', `cliente` = '".$_POST['cliente']."', `cnpj_cpf` = '".$_POST['cpf_cnpj']."', `endereco` = '".$_POST['endereco']."', `telefone` = '".$_POST['telefone']."', `obs` = '".$_POST['obs']."' WHERE CONVERT( `clientes`.`id` USING utf8 ) = '".$_POST['id']."' ;");
  evento('listar clientes','cliente alterado: '.$_POST['cliente']);
 }

?>
<script type="text/javascript" src="incs/wz_tooltip/wz_tooltip.js"></script>  
<h2>listar clientes</h2>

<?php
if ($_GET['ed']=='') {
evento('listar clientes','listar todos os clientes');
?>
<h3>lista de clientes cadastrados</h3>
<table border="1" style="border-style:hidden">
<tr>
<td><center><b>id</b></center></td>
<td><center><b>cliente</b></center></td>
<td><center><b>ação</b></center></td>
</tr>
<?php
function trimall($str, $charlist = "\t\n\r\0\x0B")
{
  return str_replace(str_split($charlist), '', $str);
}

$yes = @mysql_query("SELECT * FROM `clientes` ORDER BY `id` ASC");
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>
<tr onmouseover="Tip('<b><?=$row['cliente'];?></b><br /><br /><b>Endereço: </b><?php echo $row['endereco'];?><br /><b>Telefone:</b> <?php echo $row['telefone'];?><br /><u><b>Observações:</b></u><br /><?=trimall(nl2br(strip_tags($row['obs'])));?>', BGCOLOR, '#FFFFFF', BORDERCOLOR, '#000000', FADEIN, 500, FADEOUT, 500, FONTSIZE, '12pt', FONTCOLOR, '#000000');" onmouseout="UnTip();">
<td>
<?php printf("%02d",$row['id']);?>
</td>
<td>
<?php echo $row['cliente'];?>
</td>
<td>
<a href="?m=listar&ed=<?php echo $row['id']?>">editar</a> / <a onclick="if (window.confirm('Deseja realmente apagar este registro?')) window.location='?m=listar&del=<?php echo $row['id']?>'">apagar</a>
</td>
</tr>
<?php }
}
 ?>
 </table>
<?php
 } else {
  evento('listar clientes','editar cliente: '.$_GET['ed']);
  $edt = mysql_query("SELECT * FROM `clientes` WHERE `id` = ".$_GET['ed']);
  if ($edt) {
  $xow = mysql_fetch_array($edt, MYSQL_ASSOC);
  }
?>
<h3>editar cliente</h3>
<table>
<form name="cadastro" action="?m=listar" method="post">
<input type="hidden" name="id" value="<?php echo $xow['id'];?>">
<tr>
<td>Nome do cliente</td>
</tr><tr>
<td><input name="cliente" value="<?php echo $xow['cliente'];?>" type="text" size="60"></td>
</tr><tr><td>Endereço</td>
</tr><tr><td><input name="endereco" value="<?php echo $xow['endereco'];?>" type="text" size="60"></td>
</tr><tr><td>Telefone(s)</td>
</tr><tr><td><input name="telefone" value="<?php echo $xow['telefone'];?>" type="text" size="60"></td>
</tr><tr><td>Observações</td>
</tr><tr><td>
<textarea name="obs" rows="10" cols="53">
<?php 
	if ($mobile) 
	echo strip_tags($xow['obs']);
	else $xow['obs'];
?>
</textarea>
</td></tr>
<tr>
<td>
<input type="submit" name="registrar" onclick="if (validaForm()==0) return false; else return true;" onkeypress="if (validaForm()==0) return false; else return true;" value="Salvar Registro" />
</td>
</tr>
</form>
</table>


<?php } ?>
