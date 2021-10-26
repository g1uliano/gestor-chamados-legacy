<?
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao) or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
?>
<h2>relatórios de serviço</h2>
<h3>selecione o mês e ano</h3>
<table border="0">
<form name="relatorio" action="?m=relatorios" method="post" >
<tr>
<td>Técnico</td><td><select name="tecnico">
<?php
$yes = @mysql_query("SELECT * FROM `usuarios` WHERE ((`id` <> 1) AND (`nivel` <> 3)) ORDER BY `id` ASC");
  if ($yes) {
  echo "<option value=\"todos\">TODOS OS TÉCNICOS</option>";
  while($xow = mysql_fetch_array($yes, MYSQL_ASSOC)) {
   echo "<option value=\"".$xow['nomedousuario']."\"";
    if ($_POST['tecnico']==$xow['nomedousuario']) {
     echo " selected=\"selected\"";
    }
   echo ">".$xow['nomedousuario']."</option>";
   }
  }
?>
</select>
</td>
</tr>
<tr>
<td>Cliente</td>
<td><select name="cliente">
<?php
$yes = @mysql_query("SELECT * FROM `clientes` ORDER BY `cliente` ASC");
  if ($yes) {
  echo "<option value=\"todos\">TODOS OS CLIENTES</option>";
  while($xow = mysql_fetch_array($yes, MYSQL_ASSOC)) {
   echo "<option value=\"".$xow['cliente']."\"";
    if ($_POST['cliente']==$xow['cliente']) {
     echo " selected=\"selected\"";
    }
   echo ">".$xow['cliente']."</option>";
   }
  }
?>
</select>
</td>
</tr>
<tr>
<td>Mês</td><td>
<?
function mes_ver($p,$m) {
if ($p != '') {
 if ($m == $p) {
  echo "selected=\"yes\"";
 }
} else {
 if ($m == date("m")) {
  echo "selected=\"yes\"";
 }
}
}
?>
<select name="mes">
<option value="1" <?php mes_ver($_POST['mes'],1); ?>>JANEIRO</option>
<option value="2" <?php mes_ver($_POST['mes'],2); ?>>FEVEREIRO</option>
<option value="3" <?php mes_ver($_POST['mes'],3); ?>>MARÇO</option>
<option value="4" <?php mes_ver($_POST['mes'],4); ?>>ABRIL</option>        
<option value="5" <?php mes_ver($_POST['mes'],5); ?>>MAIO</option>
<option value="6" <?php mes_ver($_POST['mes'],6); ?>>JUNHO</option>
<option value="7" <?php mes_ver($_POST['mes'],7); ?>>JULHO</option>
<option value="8" <?php mes_ver($_POST['mes'],8); ?>>AGOSTO</option>
<option value="9" <?php mes_ver($_POST['mes'],9); ?>>SETEMBRO</option>
<option value="10" <?php mes_ver($_POST['mes'],10); ?>>OUTUBRO</option>
<option value="11" <?php mes_ver($_POST['mes'],11); ?>>NOVEMBRO</option>
<option value="12" <?php mes_ver($_POST['mes'],12); ?>>DEZEMBRO</option>                               
</select>
</td>
</tr><tr>
<td>
Ano
</td>
<td> 
<select name="ano">
<? for ($year = 2010;$year <= 2100;$year++) { 
if ($_POST['ano']!='') {
	if ($year == $_POST['ano']) {
		echo "<option value=$year selected=\"yes\">$year</option>\r\n";
	} else {
		echo "<option value=$year>$year</option>\r\n";
	}
} else {
	if ($year == date('Y')) {
		echo "<option value=$year selected=\"yes\">$year</option>\r\n";
	} else {
		echo "<option value=$year>$year</option>\r\n";
	}
}
} ?>
</select>
</td>
</tr><tr>
<td><input type="submit" name="buscar" value="Buscar"></td>
</tr>
</form>
</table>
<?
if ($_POST['buscar']) {
	if (($_POST['tecnico']=='todos') && ($_POST['cliente']=="todos")) {
		evento('relatórios de serviço','relatório de serviço: '.$_POST['mes'].'/'.$_POST['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE `data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_POST['ano']."-".$_POST['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_POST['ano']."-".$_POST['mes']."-31')) ORDER BY `ordenador` ASC";
	} else if (($_POST['tecnico']!='todos') && ($_POST['cliente']=="todos")) {
		evento('relatórios de serviço','relatório de serviço ['.$_POST['tecnico'].']: '.$_POST['mes'].'/'.$_POST['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_POST['ano']."-".$_POST['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_POST['ano']."-".$_POST['mes']."-31'))) AND `tecnico` = '".$_POST['tecnico']."' ORDER BY `ordenador` ASC";
	} else if (($_POST['tecnico']=='todos') && ($_POST['cliente']!="todos")) {
		evento('relatórios de serviço','relatório de serviço ['.$_POST['cliente'].']: '.$_POST['mes'].'/'.$_POST['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_POST['ano']."-".$_POST['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_POST['ano']."-".$_POST['mes']."-31'))) AND `cliente` = '".$_POST['cliente']."' ORDER BY `ordenador` ASC";
        } else {
		evento('relatórios de serviço','relatório de serviço ['.$_POST['tecnico'].']: '.$_POST['mes'].'/'.$_POST['ano']);
		$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".$_POST['ano']."-".$_POST['mes']."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".$_POST['ano']."-".$_POST['mes']."-31'))) AND ((`tecnico` = '".$_POST['tecnico']."') AND  (`cliente` = '".$_POST['cliente']."')) ORDER BY `ordenador` ASC";
	}
$qry = mysql_query($sql);
$count = @mysql_num_rows($qry);
?>
<br /><br />

Total de Chamados: <b><?=$count?></b> <br /><br />
<table border="1">
<tr>
<td><font size=1><center>ID</center></font></td>
<td><font size=1><center>Abertura</center></font></td>
<td><font size=1><center>Cliente</center></font></td>
<td><font size=1><center>Solicitante</center></font></td>
<td><font size=1><center>Motivo</center></font></td>
<td><font size=1><center>Fechamento</center></font></td>
<td><font size=1><center>Técnico designado</center></font></td>
</tr>
<?
while($row = mysql_fetch_array($qry, MYSQL_ASSOC)) {
echo "<tr>";
echo "<td><center><font size=1><a href=?m=log&id=".$row['id'].">".$row['id']."</a></font></center></td>";
echo "<td><font size=1>".$row['data_abertura']."</font></td>";
echo "<td><font size=1>".$row['cliente']."</font></td>";
echo "<td><font size=1>".$row['solicitante']."</font></td>";
echo "<td><font size=1>".$row['motivo']."</font></td>";
echo "<td><font size=1>".$row['data']."</font></td>";
echo "<td><font size=1>".$row['tecnico']."</font></td>";
echo "</tr>";  
 }
?>

<?php
} else {
	evento('relatórios de serviço','tela incial');
}
?>
