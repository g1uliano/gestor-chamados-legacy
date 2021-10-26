<?php if (!defined("_SEGURO")) die("Acesso Negado");
 
 ?>
 <h2>módulo de cobrança</h2>
 
  <?php
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
    
    if ($_POST['salvar']) {
	if (mysql_query("UPDATE `cobranca` SET `cliente` = '".$_POST['cliente']."',`mensagem` = '".$_POST['mensagem']."' WHERE `mac` = '".$_POST['mac']."';")) {
		echo "<br><b>Registro ".$_POST['mac']." atualizado com sucesso.</b><br />";
		 evento('cobrancas','registro atualizado: '.$_POST['mac']);
	}
    }
  if ($_GET['excluir']!='') {
	echo "<br />O registro ".$_GET['excluir']." foi excluído com sucesso.";
	$sql = "DELETE FROM `cobranca` WHERE `mac` = '".$_GET['excluir']."'";
	mysql_query($sql);
	evento('cobrancas','excluir registro: '.$_GET['excluir']);    
    }
if ($_GET['editar']=='') {
?>
<h3> clientes instalados</h3>
<?php
    $q = mysql_query("SELECT * FROM `cobranca`");
    $row = mysql_fetch_array($q, MYSQL_ASSOC);
    if ($row['mac']!="") {
    evento('cobrancas','listar clientes instalados');
?>
 
 <table border=1 style="border-style:hidden">
 <tr>
 <td><center>mac / id</center></td>
 <td><center>cliente</center></td>
 <td><center>ação</center></td>
 </tr>
 <?php 
	$q = mysql_query("SELECT * FROM `cobranca`");
	while ($row = mysql_fetch_array($q, MYSQL_ASSOC)) {
		if ($row['cliente']=='') {
			$row['cliente']="CLIENTE NÃO DEFINIDO";
		}
		echo "<tr>";
		echo "<td><center>".$row['mac']."</center></td>";
		echo "<td><center>".$row['cliente']."</center></td>";
		echo "<td><center><a href=\"?m=cobranca&editar=".$row['mac']."\">editar</a> / <a onclick=\"if(window.confirm('Voce tem certeza que deseja deletar este registro?')) return true; else return false;\" href=\"?m=cobranca&excluir=".$row['mac']."\">excluir</a></td></center></td>";
		echo "</tr>";
	}
 ?>
 </table>
<?php 
 } else {
  evento('cobrancas','nenhum cliente encontrado');
  echo "Não existem clientes instalados.<br />";
 }
} else {
evento('cobrancas','editar mensagem: '.$_GET['editar']);
?>
<h3> editar mensagem #<?=$_GET['editar']?> </h3>
<form action="?m=cobranca" method="post" >
<table>
<tr>
<td>Cliente</td>
<td><select name="cliente">
<?php
$qyes = mysql_query("SELECT * FROM `cobranca` WHERE `mac` = '".$_GET['editar']."'");
$now = mysql_fetch_array($qyes, MYSQL_ASSOC);
$yes = mysql_query("SELECT * FROM `clientes` ORDER BY `cliente` ASC");
  if ($yes) {
  while($xow = mysql_fetch_array($yes, MYSQL_ASSOC)) {
   echo "<option value=\"".$xow['cliente']."\"";
    if ($now['cliente']==$xow['cliente']) {
     echo " selected=\"selected\"";
    }
   echo ">".$xow['cliente']."</option>";
   }
  }
?>
</select>
</td>
</tr>
</table>
<br />
<textarea name=mensagem rows=15 cols=60><?=$now['mensagem']?></textarea>
<br />
<input type=hidden name=mac value="<?=$_GET['editar']?>">
<input type="submit" name="salvar" value="salvar">
</form>
</font>
<br />
<?php
}
?>
