<script language="javascript">
function isIE()
{
 return /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);
}

<?php if ($_POST['tecnico']) { ?>
function TecPrint() {
if (isIE())
	var winPop = window.open("exportar/log1.php?tecnico=<?=$_POST['tecnico']?>","","scrollbars=yes,menubar=no,status=no,toolbar=no,resizable=no");
else 
	window.open("exportar/log1.php?tecnico=<?=$_POST['tecnico']?>","Versão para Impressão","scrollbars=yes,menubar=no,status=no,toolbar=no,resizable=no");

}
<?php } ?>

<?php if ($_POST['cliente']) { ?>
function CliPrint() {
if (isIE())
	var winPop = window.open("exportar/log1.php?cliente=<?=$_POST['cliente']?>","","scrollbars=yes,menubar=no,status=no,toolbar=no,resizable=no");
else 
	window.open("exportar/log1.php?cliente=<?=$_POST['cliente']?>","Versão para Impressão","scrollbars=yes,menubar=no,status=no,toolbar=no,resizable=no");

}
<?php } ?>
	$().ready(function() {
		$('input[name=reabrir]').click(function() {
			if (!confirm("Deseja realmente REABRIR este chamado?")) {
				return false;
			}
		});
	});

</script>
<?
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao) or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
?>
<h2>buscar chamados</h2>
<?php
function se_vazio($var) {
if ($var=='') echo "não informado"; else echo $var;
}
if ((!$_POST['buscar_tec']) && (!$_POST['buscar_cliente'])) {
  if (($_POST['id']=='') && ($_GET['id']=='')) {
   evento('buscar chamados','tela de seleção.');
  }
}
if (($_POST['id']=='') && ($_GET['id'] == '') && (!$_POST['buscar_tec'])) { 
?>

<h3>listar chamados fechados por cliente</h3>
<table border="1" style="border-style:hidden" cellpadding="3" cellspacing="3">
<form name="log" action="?m=log" method="post">
<tr><td>CLIENTE</td><td><select name="cliente">
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
</td><td>
<input type="submit" name="buscar_cliente" value="buscar">
</td></tr>
</table>
<?php }

if (($_POST['id']=='') && ($_GET['id'] == '') && (!$_POST['buscar_cliente'])) { ?>
<h3>listar chamados fechados por técnico</h3>
<table border="1" style="border-style:hidden" cellpadding="3" cellspacing="3">
<form name="log" action="?m=log" method="post">
<tr><td>TÉCNICO</td><td><select name="tecnico">
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
</td><td>
<input type="submit" name="buscar_tec" value="buscar">
</td></tr>
</table>
<?php } ?>

<?php
if ($_POST['buscar_tec']!='') { 
evento('buscar chamados','buscar chamados por técnico.');
if ($_POST['tecnico']== 'todos') {
 evento('buscar chamados','buscar chamados de todos os técnicos');
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' ORDER BY `id` ASC");
 } else {
 evento('buscar chamados','buscar chamados do técnico: '.$_POST['tecnico']);
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' AND `tecnico` = '".$_POST['tecnico']."' ORDER BY `id` ASC");
}
$num_rows = mysql_num_rows($yes);
?>
<br />
Constam <?php echo $num_rows;?> chamado(s) para <?php echo $_POST['tecnico'];?>. <br /><br />
<table border="1" style="border-style:hidden">
<tr>
<td><font size=1><center>id</center></font></td>
<td><font size=1><center>abertura</center></font></td>
<td><font size=1><center>hora</center></font></td>
<td><font size=1><center>técnico</center></font></td>
<td><font size=1><center>cliente</center></font></td>
<td><font size=1><center>prioridade</center></font></td>
<td><font size=1><center>motivo</center></font></td>
<td><font size=1><center>fechamento</center></font></td>
</tr>
<?php
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>
<tr>
<td><font size=1><a href="?m=log&id=<?echo $row['id'];?>"><?echo $row['id'];?></a></font></td>
<td><font size=1><?echo $row['data_abertura'];?></font></td>
<td><font size=1><?echo $row['hora_abertura'];?></font></td>
<td><font size=1><?echo $row['tecnico'];?></font></td>
<td><font size=1><?echo $row['cliente'];?></font></td>
<td><font size=1><?
switch($row['prioridade']) {
 case 0:
 echo 'arquivo morto';
 break;
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
<td><font size=1><?echo $row['motivo'];?></font></td>
<td><font size=1><?echo $row['data'];?></font></td>
</tr>
<?
  }  
?>
</table>
<br />
<input type="button" onclick="javascript:TecPrint()" value="Imprimir">
<?
 }
 }
 ?>

<?php
if ($_POST['buscar_cliente']!='') { 
if ($_POST['cliente']== 'todos') {
 evento('buscar chamados','buscar por todos os clientes.');
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' ORDER BY `id` ASC");
} else {
 evento('buscar chamados','buscar chamados do cliente: '.$_POST['cliente']);
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` <> '' AND `cliente` = '".$_POST['cliente']."' ORDER BY `id` ASC");
}
$num_rows = mysql_num_rows($yes);
?>
<br />
Constam <?php echo $num_rows;?> chamado(s) atendidos no cliente "<?php echo $_POST['cliente'];?>". <br />
<br />
<table border="1" style="border-style:hidden">
<tr>
<td><font size=1><center>id</center></font></td>
<td><font size=1><center>abertura</center></font></td>
<td><font size=1><center>hora</center></font></td>
<td><font size=1><center>cliente</center></font></td>
<td><font size=1><center>prioridade</center></font></td>
<td><font size=1><center>motivo</center></font></td>
<td><font size=1><center>fechamento</center></font></td>
</tr>
<?php
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>
<tr>
<td><font size=1><a href="?m=log&id=<?echo $row['id'];?>"><?echo $row['id'];?></a></font></td>
<td><font size=1><?echo $row['data_abertura'];?></font></td>
<td><font size=1><?echo $row['hora_abertura'];?></font></td>
<td><font size=1><?echo $row['cliente'];?></font></td>
<td><font size=1><?
switch($row['prioridade']) {
 case 0:
 echo 'arquivo morto';
 break;
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
<td><font size=1><?echo $row['motivo'];?></font></td>
<td><font size=1><?echo $row['data'];?></font></td>
</tr>
<?
  }  
?>
</table>
<br />
<input type="button" onclick="javascript:CliPrint()" value="Imprimir">

<?
 }
 }
 ?>
 <?
$id='';
if ($_POST['id']!='') $id = $_POST['id'];
if ($_GET['id']!='') $id = $_GET['id'];
//
if ($id!='') {
   evento('buscar chamados','exibir chamado: '.$id);

   $ehneh = mysql_query("SELECT * FROM `chamados` WHERE `id` = '".$id."'");
   $row = mysql_fetch_array($ehneh, MYSQL_ASSOC);
   ?>
<h3>chamado nº <?=$row['id'];?></h3>
<table border="1" style="border-style:hidden" cellpadding="3" cellspacing="3">
<tr><td><b>cliente</b></td><td><?=$row['cliente'];?></td></tr>
<tr><td><b>solicitante</b></td><td><?=se_vazio($row['solicitante']);?></td></tr>
<tr><td><b>motivo</b></td><td><?=se_vazio($row['motivo']);?></td></tr>
<tr><td><b>obs</b></td><td><?=$row['obs'];?></td></tr>
<tr><td><b>prioridade</b></td><td><?
switch($row['prioridade']) {
 case 0:
 echo 'ARQUIVO MORTO';
 break;
 case 1:
 echo 'BAIXA';
 break;
 case 2:
 echo 'ACIMA DO NORMAL';
 break;
 case 3:
 echo 'NORMAL';
 break;
 case 4:
 echo 'ACIMA DO NORMAL';
 break;
 case 5:
 echo '<font color=red>ALTA</font>';
 break;
 case 6:
 echo '<b><font color=red>ÔMEGA</font></b>';
 break;
}
?></td></tr>
<tr><td><b>aberto em</b></td><td><?=$row['data_abertura']." às ".$row['hora_abertura'];?></td></tr>
<tr><td><b>fechado em</b></td><td><?=$row['data'];?></td></tr>
<tr><td><b>técnico</b></td><td><?=$row['tecnico'];?></td></tr>
<tr><td><b>inicio</b></td><td><?=$row['tec_inicio'];?></td></tr>
<tr><td><b>término</b></td><td><?=$row['tec_fim'];?></td></tr>
<tr><td><b>parecer técnico</b></td><td><?=$row['parecer'];?></td></tr>
</table>
<br />
<?php if ($_SESSION['nivel']==1) { ?>
<form action="?m=aberto" name="reabre" method="post">
	<input type="hidden" name="reabre_id" value="<?php echo $_GET['id'];?>">
	<input type="submit" name="reabrir" value="reabrir chamado">
</form>
<?php } ?>
<?   
}
?>