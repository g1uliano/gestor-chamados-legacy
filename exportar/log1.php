<?php
define("_SEGURO",1);
include("../config.php");

session_start();

if (!isset($_SESSION['nivel'])) exit;

$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao) or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
if ($_GET['tecnico'] != '') {
	if ($_GET['tecnico']== 'todos') 
	$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' ORDER BY `id` ASC");
		else 
	$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' AND `tecnico` = '".$_GET['tecnico']."' ORDER BY `id` ASC");
	$num_rows = mysql_num_rows($yes);
?>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<body onload="window.print();">
<br />
<font face="Verdana" size="2">
Constam <?php echo $num_rows;?> chamado(s) para <?php echo $_GET['tecnico'];?>. </font><br /><br />
<table border="1" style="border-style:hidden">
<tr>
<td><font size=1 face="Verdana"><center>id</center></font></td>
<td><font size=1 face="Verdana"><center>abertura</center></font></td>
<td><font size=1 face="Verdana"><center>hora</center></font></td>
<td><font size=1 face="Verdana"><center>técnico</center></font></td>
<td><font size=1 face="Verdana"><center>cliente</center></font></td>
<td><font size=1 face="Verdana"><center>prioridade</center></font></td>
<td><font size=1 face="Verdana"><center>motivo</center></font></td>
<td><font size=1 face="Verdana"><center>fechamento</center></font></td>
</tr>
<?php
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>
<tr>
<td><font size=1 face="Verdana"><?echo $row['id'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['data_abertura'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['hora_abertura'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['tecnico'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['cliente'];?></font></td>
<td><font size=1 face="Verdana"><?
switch($row['prioridade']) {
 case 1:
 echo 'baixa';
 break;
 case 2:
 echo 'abaixo do normal';
 break;
 case 3:
 echo 'normal';
 break;
 case 4:
 echo 'acima do normal';
 break;
 case 5:
 echo '<font color=red>alta</font>';
 break;
 case 6:
 echo '<b><font color=red>ômega</font></b>';
 break;
}
?></font></td>
<td><font size=1 face="Verdana"><?echo $row['motivo'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['data'];?></font></td>
</tr>
<?
	}
  }  
}
?>
<?php
if ($_GET['cliente'] != '') {
	if ($_GET['cliente']== 'todos') 
		$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' ORDER BY `id` ASC");
	else 
		$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' AND `cliente` = '".$_GET['cliente']."' ORDER BY `id` ASC");
$num_rows = mysql_num_rows($yes);
?>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<body onload="window.print();">
<br />
Constam <?php echo $num_rows;?> chamado(s) atendidos no cliente "<?php echo $_GET['cliente'];?>". <br />
<table border="1" style="border-style:hidden">
<tr>
<td><font size=1 face="Verdana"><center>id</center></font></td>
<td><font size=1 face="Verdana"><center>abertura</center></font></td>
<td><font size=1 face="Verdana"><center>hora</center></font></td>
<td><font size=1 face="Verdana"><center>técnico</center></font></td>
<td><font size=1 face="Verdana"><center>cliente</center></font></td>
<td><font size=1 face="Verdana"><center>prioridade</center></font></td>
<td><font size=1 face="Verdana"><center>motivo</center></font></td>
<td><font size=1 face="Verdana"><center>fechamento</center></font></td>
</tr>
<?php
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>
<tr>
<td><font size=1 face="Verdana"><?echo $row['id'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['data_abertura'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['hora_abertura'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['tecnico'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['cliente'];?></font></td>
<td><font size=1 face="Verdana"><?
switch($row['prioridade']) {
 case 1:
 echo 'baixa';
 break;
 case 2:
 echo 'abaixo do normal';
 break;
 case 3:
 echo 'normal';
 break;
 case 4:
 echo 'acima do normal';
 break;
 case 5:
 echo '<font color=red>alta</font>';
 break;
 case 6:
 echo '<b><font color=red>ômega</font></b>';
 break;
}
?></font></td>
<td><font size=1 face="Verdana"><?echo $row['motivo'];?></font></td>
<td><font size=1 face="Verdana"><?echo $row['data'];?></font></td>
</tr>
<?
	}
  }  
}
?>

</table>
</body></html>

