<?php if (!defined("_SEGURO")) die("Acesso Negado"); 
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
evento('arquivo morto','visualizar chamados arquivados');
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` = ''  AND `prioridade` = 0 ORDER BY `prioridade` DESC");
$count = @mysql_num_rows($yes);
?>

<script type="text/javascript" src="incs/wz_tooltip/wz_tooltip.js"></script>  
<script type="text/javascript" src="incs/js/base64_decode.js"></script>  

<h2>arquivo morto</h2>

<h3>Temos <b><?=$count?></b> chamado(s) arquivado(s) no momento.</h3>
<table border="1" style="border-style:hidden">
<tr>
<td>data</td>
<td>hora</td>
<td>cliente</td>
<td>ação</td>
</tr>
<?php
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
<td><a href="?m=aberto&exibir=<?php echo $row['id']?>">exibir</a> / <a href="?m=aberto&fechar=<?php echo $row['id']?>">fechar</a>
<?php
if (($_SESSION['nivel']==1) || ($row['usuario']== $_SESSION['id'])) {
 echo " / <a href=?m=aberto&editar=".$row['id'].">editar</a> ";
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
