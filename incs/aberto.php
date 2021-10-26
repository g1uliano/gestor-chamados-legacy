<?php
function se_vazio($var) {
	if ($var=='') echo "não informado"; else echo $var;
}
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao) or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");

if ($mobile) {
	$_POST['motivo'] = '<p>'.nl2br($_POST['motivo']).'</p>';
	$_POST['obs'] = '<p>'.nl2br($_POST['obs']).'</p>';
}


if ($_SESSION['nivel']==1) {

 if ($_GET['deletar']!='') {
   evento('chamados abertos','deletar chamado: '.$_GET['deletar']);
   mysql_query("DELETE FROM `chamados` WHERE `chamados`.`id` = '".$_GET['deletar']."'");
 }
 if ($_POST['reabrir']) {
   evento('chamados abertos','reabrir chamado: '.$_POST['reabre_id']);
   mysql_query("UPDATE `chamados` SET `data` =  '',
   `tec_inicio` = '',
   `tec_fim` = '',
   `parecer` = '',
   `tecnico` = '' WHERE `id` = ".$_POST['reabre_id']." LIMIT 1 ;");	
 }
}


if ($_POST['salvar_chamado']) {
evento('chamados abertos','salvar chamado: '.$_POST['id']);
$comando_sql = "UPDATE `chamados` SET 
`prioridade` = '".$_POST['prioridade']."',
`solicitante` = '".$_POST['solicitante']."',
`cliente` = '".$_POST['cliente']."',
`motivo` = '".$_POST['motivo']."',
`obs` = '".$_POST['obs']."' WHERE CONVERT( `chamados`.`id` USING utf8 ) = '".$_POST['id']."' LIMIT 1 ;
";
mysql_query($comando_sql);
}

if ($_POST['fechar_chamado']) {
evento('chamados abertos','fechar chamado: '.$_POST['id']);
$inicio = $_POST['i_dia']."/".$_POST['i_mes']."/".$_POST['i_ano']." às ".$_POST['i_hora'].":".$_POST['i_minuto'];
$termino = $_POST['t_dia']."/".$_POST['t_mes']."/".$_POST['t_ano']." às ".$_POST['t_hora'].":".$_POST['t_minuto'];
mysql_query("UPDATE `chamados` SET `data` = '".date("d/m/Y")." às ".date("H:i:s")."',
`tec_inicio` = '".$inicio."',
`tec_fim` = '".$termino."',
`tecnico` = '".$_POST['tecnico']."',
`parecer` = '".$_POST['parecer']."' WHERE CONVERT( `chamados`.`id` USING utf8 ) = '".$_POST['id']."' LIMIT 1 ;
");
echo mysql_error();
}
//->
?>
<script type="text/javascript" src="incs/wz_tooltip/wz_tooltip.js"></script>  
<script type="text/javascript" src="incs/js/base64_decode.js"></script>  
<script language="javascript">
	$().ready(function() {
		<?php if($_GET['exibir']!='') { ?>
		$('input[name=gerar_os]').click(function() {
			window.open("os/?id=<?=$_GET['exibir']?>",null,"width=700,height=550,status=no,toolbar=no,menubar=no,location=no");
		});
		<?php } ?>
		$('input[name=fechar_chamado]').click(function() {
			if (!confirm('Deseja realmente fechar o presente chamado?')) {
					return false;
			}
		});
	});
</script>

<h2>chamados em aberto</h2>

<?php
//Código de exibição aqui.
if ($_GET['editar']!="") { 
 evento('chamados abertos','edição de chamado aberto: '.$_GET['editar']);
$yh = @mysql_query("SELECT * FROM `chamados` WHERE `id` = '".$_GET['editar']."'");
$rw = mysql_fetch_array($yh, MYSQL_ASSOC);
?>
<h3>edição de chamado aberto</h3>
<table>
<form action="?m=aberto" name="editar_chamado" method="post">
<tr><td>prioridade</td></tr>
<tr><td>
<input type="hidden" value="<?=$rw['id']?>" name="id">
<select name="prioridade">
<option <? if ($rw['prioridade']=='0') echo "selected=\"selected\""; ?> value="0" >arquivar chamado</option>
<option <? if ($rw['prioridade']=='1') echo "selected=\"selected\""; ?> value="1" >baixa</option>
<option <? if ($rw['prioridade']=='2') echo "selected=\"selected\""; ?> value="2" >abaixo do normal</option>
<option <? if ($rw['prioridade']=='3') echo "selected=\"selected\""; ?> value="3" >normal</option>
<option <? if ($rw['prioridade']=='4') echo "selected=\"selected\""; ?> value="4" >acima do normal</option>
<option <? if ($rw['prioridade']=='5') echo "selected=\"selected\""; ?> value="5" >alta</option>
<option <? if ($rw['prioridade']=='6') echo "selected=\"selected\""; ?>  value="6" >ômega</option>
</select>
</td></tr>
<tr><td>cliente</td></tr>
<tr><td>
<select name="cliente">
<?php
$yes = @mysql_query("SELECT * FROM `clientes` ORDER BY `id` ASC");
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
    if ($row['cliente']!=$rw['cliente'])  {
   echo "<option value=\"".$row['cliente']."\">".$row['cliente']."</option>";
    } else {
   echo "<option value=\"".$row['cliente']."\" selected=\"selected\">".$row['cliente']."</option>";
    }
   }
  }
?>
</select>
</td>
</tr>
<tr><td>solicitante</td></tr>
<tr><td><input type="text" name="solicitante" value="<?=$rw['solicitante']?>" size="60"></td></tr>
<tr>
<td>motivo</td></tr><tr>
<td><textarea name="motivo" rows="5" cols="50"><?php
	if ($mobile) 
	echo strip_tags($rw['motivo']);
	else 
	echo $rw['motivo'];
?></textarea>
</td>
</tr>
<td>observação</td></tr><tr>
<td><textarea name="obs" rows="5" cols="50"><?php
	if ($mobile) 
	echo strip_tags($rw['obs']."\r\nÚltima edição: ".date('d/M/Y')." às ".date('H:i:s')." hrs por: ".$_SESSION['id'].".");
	else 
	echo $rw['obs'].'<br />Última edição: '.date('d/m/Y').' às '.date('H:i:s').' hrs por: '.$_SESSION['id'].'.';
?></textarea>
</td>
</tr>
<tr><td>
<input type="submit" name="salvar_chamado" onkeypress="if (!window.confirm('Você deseja realmente salvar as alterações feitas neste chamado?')) {return false};" onclick="if (!window.confirm('Você deseja realmente salvar as alterações feitas neste chamado?')) {return false};" value="salvar">
</td></tr>
</form>
</table>
<?php
}


if ($_GET['exibir']!='') {
 evento('chamados abertos','exibição de chamado aberto: '.$_GET['exibir']);
 $xes = mysql_query("SELECT * FROM `chamados` WHERE `id` = '".$_GET['exibir']."' ");
 $row = mysql_fetch_array($xes, MYSQL_ASSOC);
 
?>
<h3>exibir chamado nº <?php echo $row['id'];?></h3>
<table border="1" style="border-style:hidden" cellpadding="3" cellspacing="3">
<tr>
<td><b>CLIENTE</b></td><td><?php echo strtoupper($row['cliente']);?></td>
</tr>
<tr>
<td><b>SOLICITANTE</b></td><td>
<?php 
if ($row['solicitante'] == '') {
echo "NÃO INFORMADO";
} else {
echo strtoupper($row['solicitante']);
}
?></td>
</tr>
<tr>
</td>
<td><b>PRIORIDADE</b></td><td>
<?
switch($row['prioridade']) {
 case 0:
 echo 'CHAMADO ARQUIVADO';
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
?>
</td>
<tr>
<td><b>ABERTURA</b></td>
<td><?php echo $row['data_abertura'];?> às <?php echo $row['hora_abertura'];?> hrs</td>
</tr>
<tr><td><b>MOTIVO</b></td>
<td><?php 
if ($row['motivo']=='') {
echo 'NÃO ESPECIFICADO';
} else {
echo $row['motivo'];
}
s?></td>
<tr><td><b>OBSERVAÇÕES</b></td>
<td><?php echo $row['obs'];?></td></tr>
<tr>
<td><b>USUÁRIO</b></td><td><?php echo $row['usuario'];?></td>
</tr>
</table>
<br />
<input type="button" name="gerar_os" value="Gerar Ordem de Serviço">
<?php
}
?>

<?php
//Código para fechar chamado aqui.
if ($_GET['fechar']!='') {
 evento('chamados abertos','tela de fechamento de chamado: '.$_GET['fechar']);
$xes = mysql_query("SELECT * FROM `chamados` WHERE `id` = '".$_GET['fechar']."' ");
$row = mysql_fetch_array($xes, MYSQL_ASSOC);
 
?>
<h3>fechamento de chamado nº <?=$_GET['fechar'];?></h3>
<form action="?m=aberto" method="post" name="fechamento">
<table border="0" style="border-style:hidden" cellpadding="3" cellspacing="3">
<tr><td><b>Cliente</b></td><td><?=$row['cliente'];?></td></tr>
<tr><td><b>Solicitante</b></td><td><?se_vazio($row['solicitante']);?></td></tr>
<tr><td><b>Motivo</b></td><td><?se_vazio($row['motivo']);?></td></tr>
<tr><td><b>Aberto em</b></td><td><?=$row['data_abertura']." às ".$row['hora_abertura'];?></td></tr>
<tr><td>
<b>Inicio</b>
</td>
<td>
<select name="i_dia">
 <? 
 for ($i=1;$i<=9;$i++) {
	if (date('j')==$i) {
	    echo "<option selected=selected value=\"0".$i."\">0".$i."</option>";
	} else {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
	}
 } 
 for ($i=10;$i<=31;$i++) {
	if (date('j')==$i) {
	    echo "<option selected=selected value=".$i.">".$i."</option>";
	} else {
	    echo "<option value=$i>".$i."</option>";
	}
 } 
 ?>
</select>/
<select name="i_mes" >
 <? 
 for ($i=1;$i<=9;$i++) {
	if (date('n')==$i) {
	    echo "<option selected=selected value=\"0".$i."\">0".$i."</option>";
	} else {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
	}
 } 
 for ($i=10;$i<=12;$i++) {
	if (date('n')==$i) {
	    echo "<option selected=selected value=$i>".$i."</option>";
	} else {
	    echo "<option value=$i>".$i."</option>";
	}
 } 
 ?>
</select>/
<select name="i_ano" >
 <? 
 for ($i=2010;$i<=2050;$i++) {
	if (date('Y')==$i) {
	    echo "<option selected=selected value=$i>".$i."</option>";
	} else {
	    echo "<option value=$i>".$i."</option>";
	}
 } 
 ?>
</select>
às 
<select name="i_hora" >
 <? 
 for ($i=0;$i<=9;$i++) {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
 } 
 for ($i=10;$i<=23;$i++) {
	echo "<option value=$i>".$i."</option>"; 
 } 
 ?>
</select>
:
<select name="i_minuto" >
 <? 
 for ($i=0;$i<=9;$i++) {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
 } 
 for ($i=10;$i<=59;$i++) {
	echo "<option value=".$i.">".$i."</option>"; 
 } 
 ?>
 </select>
  hrs.</td>
</tr>
<tr><td>
<b>Término</b>
</td>
<td>
<select name="t_dia" >
 <? 
 for ($i=1;$i<=9;$i++) {
	if (date('j')==$i) {
	    echo "<option selected=selected value=\"0".$i."\">0".$i."</option>";
	} else {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
	}
 } 
 for ($i=10;$i<=31;$i++) {
	if (date('j')==$i) {
	    echo "<option selected=selected value=$i>".$i."</option>";
	} else {
	    echo "<option value=$i>".$i."</option>";
	}
 } 
 ?>
</select>/
<select name="t_mes" >
 <? 
 for ($i=1;$i<=9;$i++) {
	if (date('n')==$i) {
	    echo "<option selected=selected value=\"0".$i."\">0".$i."</option>";
	} else {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
	}
 } 
 for ($i=10;$i<=12;$i++) {
	if (date('n')==$i) {
	    echo "<option selected=selected value=$i>".$i."</option>";
	} else {
	    echo "<option value=$i>".$i."</option>";
	}
 } 
 ?>
</select>/
<select name="t_ano" >
 <? 
 for ($i=2010;$i<=2050;$i++) {
	if (date('Y')==$i) {
	    echo "<option selected=selected value=$i>".$i."</option>";
	} else {
	    echo "<option value=$i>".$i."</option>";
	}
 } 
 ?>
</select>
às 
<select name="t_hora" >
 <? 
 for ($i=0;$i<=9;$i++) {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
 } 
 for ($i=10;$i<=23;$i++) {
	echo "<option value=$i>".$i."</option>"; 
 } 
 ?>
</select>
:
<select name="t_minuto" >
 <? 
 for ($i=0;$i<=9;$i++) {
	    echo "<option value=\"0".$i."\">0".$i."</option>";
 } 
 for ($i=10;$i<=59;$i++) {
	echo "<option value=$i>".$i."</option>"; 
 } 
 ?>
 </select>
  hrs.
</td>
</tr>
<tr><td>
<b>Técnico</b>
</td>

<td>
<?php
$zes = mysql_query("SELECT * FROM `usuarios` WHERE (((`id` <> 1) AND (`nivel` <> 3)) AND `d` <> 1) ORDER BY `id` ASC");
  if ($zes) {
?>
<select name="tecnico" >
<?php
  while($now = mysql_fetch_array($zes, MYSQL_ASSOC)) {
?>
 <option <?php if ($_SESSION['id']==$now['usuario']) echo "selected=\"selected\""; ?> value="<?php echo $now['nomedousuario'];?>"><?php echo $now['nomedousuario'];?></option>
<?php }
 echo "<option value=\"Sem Atuação\">Sem Atuação</option>";
}
 ?>
</select>

</td>
</tr>

</table>
<table border="0" style="border-style:hidden" cellpadding="3" cellspacing="3">
<tr>
<td><b>Parecer Técnico</b></td>
</tr>
<tr>
<td><textarea name="parecer" rows="10" cols="40"></textarea>
<input type="hidden" name="id" value="<?=$row['id']?>"></td>
</tr>
<tr><td><input type="submit" value="fechar chamado" name="fechar_chamado"></td>
</tr>
</table>
</form>
<?php }



?>

<?php if (($_GET['exibir']=='') && ($_GET['fechar']=='') && ($_GET['editar']=='')) { 
evento('chamados abertos','visualizar chamados abertos');
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` = '' AND `prioridade` <> 0 ORDER BY `prioridade` DESC");
$count = @mysql_num_rows($yes);
?>
<h3>Temos <b><?=$count?></b> chamado(s) em aberto.</h3>
<br />
<table border="1" style="border-style:hidden">
<tr>
<td>data</td>
<td>hora</td>
<td>cliente</td>
<td><B>prioridade</B></td>
<td>ação</td>
</tr>
<?php
function prioridade($var) {
switch($var) {
 case 0:
 return 'arquivo morto';
 break;
 case 1:
 return 'baixa';
 break;
 case 2:
 return 'abaixo do normal';
 break;
 case 3:
 return 'normal';
 break;
 case 4:
 return 'acima do normal';
 break;
 case 5:
 return '<font color=red>alta</font>';
 break;
 case 6:
 return '<b><font color=red>ômega</font></b>';
 break;
}
}

function trimall($str, $charlist = "\t\n\r\0\x0B")
{
  return str_replace(str_split($charlist), '', $str);
}

  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
  
$tip  = '<table width="600" border="1" style="border-width: 0px 0px 0px 0px;
	border-spacing: 0px;
	border-style: outset outset outset outset;
	border-color: ;
	border-collapse: collapse;
	background-color: white;" cellpadding="0" cellspacing="0">';
$tip .= '<tr >';
$tip .= '<td width="16%"><font size=2><b>Cliente</b></font></td><td><font size=2>'.$row['cliente'].'</font></td>';
$tip .= '</tr>';
$tip .= '<tr>';
$tip .= '<td><font size=2><b>Solicitante</b></font></td><td><font size=2>';
if ($row['solicitante'] == '') {
$tip .=  "NÃO INFORMADO";
} else {
$tip .=  $row['solicitante'];
}
$tip .= '</font></td>';
$tip .= '</tr>';
$tip .= '<tr>';
$tip .= '</td>';
$tip .= '<td><font size=2><b>Prioridade</b></font></td><td><font size=2>';
switch($row['prioridade']) {
 case 0:
 $tip .= 'CHAMADO ARQUIVADO';
 break;
 case 1:
 $tip .=  'BAIXA';
 break;
 case 2:
 $tip .=  'ACIMA DO NORMAL';
 break;
 case 3:
 $tip .=  'NORMAL';
 break;
 case 4:
 $tip .=  'ACIMA DO NORMAL';
 break;
 case 5:
 $tip .=  '<font color=red>ALTA</font>';
 break;
 case 6:
 $tip .=  '<b><font color=red>ÔMEGA</font></b>';
 break;
}

 $tip .= '</font></td>';
 $tip .= '<tr>';
 $tip .= '<td><font size=2><b>Abertura</b></font></td>';
 $tip .= '<td><font size=2>'.$row['data_abertura'].' às '.$row['hora_abertura'].' hrs</font></td>';
 $tip .= '</tr>';
 $tip .= '<tr><td><b><font size=2>Motivo</font></b></td>';
 $tip .= '<td><font size=2>';
if ($row['motivo']=='') {
 $tip .=  'NÃO ESPECIFICADO';
} else {
 $tip .=  $row['motivo'];
}
 $tip .= '</font></td>';
 $tip .= '<tr><td><font size=2><b>Observações</b></font></td>';
 $tip .= '<td><font size=2>'.$row['obs'].'</font></td></tr>';
 $tip .= '<tr>';
 $tip .= '<td><font size=2><b>Aberto por</b></font></td><td><font size=2>'.$row['usuario'].'</font></td>';
 $tip .= '</tr>';
 $tip .= '</table>';

	$tip = base64_encode($tip);
if (!$mobile) {
?>
<tr onmouseover="Tip(base64_decode('<?=$tip?>'), BGCOLOR, '#FFFFFF', BORDERCOLOR, '#000000', FADEIN, 500, FADEOUT, 500, FONTSIZE, '12pt', FONTCOLOR, '#000000');" onmouseout="UnTip();" >
<?php } else {
echo "<tr>";
}
?>
<td><?echo $row['data_abertura'];?></td>
<td><?echo $row['hora_abertura'];?></td>
<td><?echo $row['cliente'];?></td>
<td><?=prioridade($row['prioridade']);?></td>
<td><a href="?m=aberto&exibir=<?php echo $row['id']?>">exibir</a> / <a href="?m=aberto&fechar=<?php echo $row['id']?>">fechar</a>
<?php
 if ($_GET['deletar']!='') {
 mysql_query("DELETE FROM `chamados` WHERE `chamados`.`id` = '".$_GET['deletar']."'");
 }
if (($_SESSION['nivel']==1) || ($row['usuario']== $_SESSION['id'])) {
 echo "/ <a href=?m=aberto&editar=".$row['id'].">editar</a> ";
}

 if ($_SESSION['nivel']==1) {
 echo "/ <a onclick=\"if(window.confirm('Voce tem certeza que deseja deletar este chamado da base de dados?')) return true; else return false;\" href=?m=aberto&deletar=".$row['id'].">excluir</a>";
}
?>
</td>
</tr>
<?php
  }
  }
?>
</table>
<?php 
}?>