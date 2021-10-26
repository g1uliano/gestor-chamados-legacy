<?php
define("_SEGURO",1);
include("../config.php");

session_start();

if (!isset($_SESSION['nivel'])) exit;
if (($_GET['ano'] == '') && ($_GET['mes']=='')) exit;

$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao) or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");

	if (($_GET['tecnico']=='todos') && ($_GET['cliente']=="todos")) {
		evento('relatórios de serviço','relatório de serviço: '.$_GET['mes'].'/'.$_GET['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE `data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_GET['ano']."-".$_GET['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_GET['ano']."-".$_GET['mes']."-31')) ORDER BY `ordenador` ASC";
	} else if (($_GET['tecnico']!='todos') && ($_GET['cliente']=="todos")) {
		evento('relatórios de serviço','relatório de serviço ['.$_GET['tecnico'].']: '.$_GET['mes'].'/'.$_GET['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_GET['ano']."-".$_GET['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_GET['ano']."-".$_GET['mes']."-31'))) AND `tecnico` = '".$_GET['tecnico']."' ORDER BY `ordenador` ASC";
	} else if (($_GET['tecnico']=='todos') && ($_GET['cliente']=!"todos")) {
		evento('relatórios de serviço','relatório de serviço ['.$_GET['cliente'].']: '.$_GET['mes'].'/'.$_GET['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_GET['ano']."-".$_GET['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_GET['ano']."-".$_GET['mes']."-31'))) AND `cliente` = '".$_GET['cliente']."' ORDER BY `ordenador` ASC";
        } else {
		evento('relatórios de serviço','relatório de serviço ['.$_GET['tecnico'].']: '.$_GET['mes'].'/'.$_GET['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_GET['ano']."-".$_GET['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_GET['ano']."-".$_GET['mes']."-31'))) AND ((`tecnico` = '".$_GET['tecnico']."') AND  (`cliente` = '".$_GET['cliente']."')) ORDER BY `ordenador` ASC";
       } 

$qry = mysql_query($sql);
$count = mysql_num_rows($qry);
?>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<body onload="window.print();">
&nbsp;<font face="Verdana" size="2"><b>Relatório de Serviço</b> (<?php echo $_GET['mes']."/".$_GET['ano'];?>)</font>
<br /><br />
<table border="1">
<tr>
<td><font size=1 face="Verdana"><center><b>Abertura</b></center></font></td>
<td><font size=1 face="Verdana"><center><b>Setor</b></center></font></td>
<td><font size=1 face="Verdana"><center><b>Solicitante</b></center></font></td>
<td><font size=1 face="Verdana"><center><b>Motivo</b></center></font></td>
<td><font size=1 face="Verdana"><center><b>Fechamento</b></center></font></td>
<td><font size=1 face="Verdana"><center><b>Técnico designado</b></center></font></td>
</tr>
<?
while($row = mysql_fetch_array($qry, MYSQL_ASSOC)) {
echo "<tr>";
echo "<td><font size=1 face=\"Verdana\">".$row['data_abertura']."</font></td>";
echo "<td><font size=1 face=\"Verdana\">".$row['cliente']."</font></td>";
echo "<td><font size=1 face=\"Verdana\">".ucwords(strtolower($row['solicitante']))."</font></td>";
echo "<td><font size=1 face=\"Verdana\">".ucfirst(strtolower($row['motivo']))."</font></td>";
echo "<td><font size=1 face=\"Verdana\">".$row['data']."</font></td>";
echo "<td><font size=1 face=\"Verdana\">".ucwords(strtolower($row['tecnico']))."</font></td>";
echo "</tr>";  
 }
echo "</table><br />";
echo "<font size=1 face=\"Verdana\">Total de Chamados: <b>$count</b></font>";
?>

</body>
</html>
