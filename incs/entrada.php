<?php if (!defined("_SEGURO")) die("Acesso Negado"); 
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
$yes = mysql_query("SELECT * FROM `chamados` WHERE `data` = ''  AND `prioridade` <> 0 ORDER BY `prioridade` DESC");
$count = @mysql_num_rows($yes);
?>
<script type="text/javascript" src="incs/wz_tooltip/wz_tooltip.js"></script>  
<h2>Gestor de Chamados</h2>

<h3>Temos <b><?=$count?></b> chamado(s) em aberto.</h3>
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
}

function trimall($str, $charlist = "\t\n\r\0\x0B")
{
  return str_replace(str_split($charlist), '', $str);
}

  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>

<tr onmouseover="Tip('<b><?=$row['cliente'];?></b> - Aberto em <?echo $row['data_abertura'];?> às <?echo $row['hora_abertura'];?> por: <?=$row['usuario'];?><br /><b>prioridade:</b> <?=prioridade($row['prioridade']);?><br /><br /><b>motivo:</b></u><br /><?=trimall(nl2br(strip_tags($row['motivo'])));?><br /><b>observações:</b></u><br /><?=trimall(nl2br(strip_tags($row['obs'])));?>', BGCOLOR, '#FFFFFF', BORDERCOLOR, '#000000', FADEIN, 500, FADEOUT, 500, FONTSIZE, '12pt', FONTCOLOR, '#000000');" onmouseout="UnTip();">
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
