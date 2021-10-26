<?php if (!defined("_SEGURO")) die("Acesso Negado"); 
$conexao = mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
mysql_select_db($sql["base"], $conexao)
 or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
?>
<script language="javascript">
	$().ready(function() {
		$('input[name=abrir_chamado]').click(function(){
			if (!confirm("Você deseja realmente abrir este chamado?")) {
				return false;
			}
		});
	});
</script>
<h2>abertura de chamados</h2>
<?php
if ($_POST['abrir_chamado']) {
if ($mobile) {
	$_POST['motivo'] = '<p>'.nl2br($_POST['motivo']).'</p>';
	$_POST['obs'] = '<p>'.nl2br($_POST['obs']).'</p>';
}

   $z = mysql_query("SELECT * FROM `clientes` WHERE `cliente` = '".$_POST['cliente']."'");
   $z = mysql_fetch_array($z, MYSQL_ASSOC);
   $ox = mysql_query("SELECT * FROM `chamados`  ORDER BY `chamados`.`id` DESC");
   $mow = mysql_fetch_array($ox, MYSQL_ASSOC);
   $mow['id']++;
   $data_abertura = date("d/m/Y");
   $hora_abertura = date("H:i:s");
   if (mysql_query("INSERT INTO `chamados` (`id` ,`prioridade` ,`tipo` ,
`data_abertura` ,
`hora_abertura` ,
`cliente` ,
`cliente_id` ,
`solicitante` ,
`motivo` ,
`obs` ,
`data` ,
`tec_inicio` ,
`tec_fim` ,
`parecer`,
`usuario`

)
VALUES ('".$mow['id']."', '".$_POST['prioridade']."', '".$_POST['tipo']."', '$data_abertura', '$hora_abertura', '".$_POST['cliente']."', '".$z['id']."', '".$_POST['solicitante']."', '".$_POST['motivo']."', '".$_POST['obs']."', '', '', '', '','".$_SESSION['id']."');"))
 echo "<b>O chamado #".$mow['id']." foi aberto com sucesso às $hora_abertura do dia $data_abertura.</b>";
	evento('abrir chamado','chamado aberto: '.$mow['id']);
 echo mysql_error();
 } else {
	evento('abrir chamado','formulário de abertura de chamado');
 }
?>
<h3>formulário de abertura de chamado </h3>
<table>
<form action="?m=chamado" name="abertura_chamado" method="post">
<tr><td>prioridade</td></tr>
<tr><td>
<select name="prioridade">
<option value="1">baixa</option>
<option value="2">abaixo do normal</option>
<option value="3" selected="selected">normal</option>
<option value="4">acima do normal</option>
<option value="5">alta</option>
<option value="6">ômega</option>
</select>
</td></tr>
<tr><td>cliente</td></tr>
<tr><td>
<select name="cliente">
<?php
$yes = @mysql_query("SELECT * FROM `clientes` ORDER BY `cliente` ASC");
  if ($yes) {
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
   echo "<option value=\"".$row['cliente']."\">".$row['cliente']."</option>";
   }
  }
?>
</select>
</td>
</tr>
<tr><td>solicitante</td></tr>
<tr><td><input type="text" name="solicitante" size="60"></td></tr>
<tr>
<td>motivo</td></tr><tr>
<td><textarea name="motivo" rows="5" cols="50">
</textarea>
</td>
</tr>
<td>observação</td></tr><tr>
<td><textarea name="obs" rows="5" cols="50">
</textarea>
</td>
</tr>
<tr><td>
<input type="submit" name="abrir_chamado" value="abrir chamado">
</td></tr>
</form>
</table>