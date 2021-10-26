<?php if (!defined("_SEGURO")) die("Acesso Negado"); 
function _ago($tm,$rcs = 0) {
    $cur_tm = time(); $dif = $cur_tm-$tm;
    $pds = array('segundo','minuto','hora','dia','semana','mês','ano','década');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1;($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); 
    if($v < 0) $v = 0; 
    $_tm = $cur_tm-($dif%$lngh[$v]);
    $no = floor($no); 
    if($no <> 1) if ($v==5) $pds[$v] ='meses'; else $pds[$v] .='s';
    $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
    return $x;
}


$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
$yes = mysql_query("SELECT * FROM `history` ORDER BY `id` DESC");
$count = @mysql_num_rows($yes);
?>
<h2>histórico de acesso</h2>

<?php if ($_GET['id']=='') { ?>
<h3>lista ordenada pelo último acesso</h3>
<table border="1" cellspacing="3" cellpadding="2" style="border-style:hidden">
<tr>
<td style="border-style:hidden"><font size=2><b>usuário</b></font></td>
<td style="border-style:hidden"><font size=2><b>último acesso</b></font></td>
<td style="border-style:hidden"><font size=2><b>hostname</b></font></td>
</tr>
<?php

function trimall($str, $charlist = "\t\n\r\0\x0B")
{
  return str_replace(str_split($charlist), '', $str);
}

  if ($yes) {
  
  while ($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
	
?>

<tr>
<td style="border-style:hidden"><font size=2><?php echo $row['usuario'];?></font></td>
<td style="border-style:hidden"><font size=2><?php echo date("d/m/Y H:i:s",strtotime($row['date']));?></font></td>
<td style="border-style:hidden"><font size=2><a href="?m=acesso&id=<?php echo$row['id'];?>" style="color:#000000; font-weight:normal; text-decoration:underline;"><?php echo $row['ip'];?></a></font></td>
</tr>
<?php
  }
 }
?>
</table>
  <br />
<?php } else {
?>
<h3>rastreio de operações efetuadas na sessão</h3>
<?php 
  $n = mysql_query("SELECT * FROM `history` WHERE `id` = '".$_GET['id']."'");
  $w = mysql_fetch_array($n, MYSQL_ASSOC);
  $yes = mysql_query("SELECT * FROM `rastreio` WHERE `hid` = '".$_GET['id']."' ORDER BY `id` ASC");
  echo mysql_error();
  $count = @mysql_num_rows($yes);
  if ($count>=1) {
  echo '<b>'.$w['usuario'].' @ '.$w['ip'].'</b> ~> <br /><br />';
?>

<table border="1" cellspacing="3" cellpadding="2" style="border-style:hidden">
<tr>
<td style="border-style:hidden"><font size=2><b>módulo</b></font></td>
<td style="border-style:hidden"><font size=2><b>ação</b></font></td>
</tr>
<?php
  while ($row = mysql_fetch_array($yes, MYSQL_ASSOC)) { 
?>  
<tr>
<td style="border-style:hidden"><font size=2><?php echo $row['modulo'];?></font></td>
<td style="border-style:hidden"><font size=2><?php echo $row['acao'];?></font></td>
</tr>
<?php
 } ?>
</table>
<br />
<?php
 } 
  $yes = mysql_query("SELECT * FROM `rastreio` WHERE `hid` = '".$_GET['id']."' ORDER BY `id` DESC");	
  $xow = mysql_fetch_array($yes, MYSQL_ASSOC);
  $count = @mysql_num_rows($yes);
  if ($count>=1) {
   echo "<h2><b>Fim da sessão:</b> "._ago(strtotime($xow['tempo'])).' atrás.</h2>';
  } else {
    echo '<br /><h2>Não foram encontrados dados relacionados a esta sessão.</h2>';
  }
} ?>
