<?php if (!defined("_SEGURO")) die("Acesso Negado");
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");

?>
<h2>gestão de equipamentos</h2>
<?php 

if ($_POST['atualizar']) {
	$sql = "UPDATE  `equipamentos` SET  `problema` =  '".$_POST['problema']."',`obs` =  '".$_POST['obs']."' WHERE  `identificador` =  '".$_POST['identificador']."';";
	mysql_query($sql);
	evento('equipamentos','atualizar equipamento: '.$_POST['identificador']);
}
if ($_POST['excluir']) {
	
	echo "<br />O registro ".$_POST['identificador']." foi excluído com sucesso.";
	$sql = "DELETE FROM `equipamentos` WHERE `identificador` = '".$_POST['identificador']."';";
	mysql_query($sql);
	evento('equipamentos','excluir equipamento: '.$_POST['identificador']);
}


//
if ((!$_POST['pesq_cli'])&&(!$_POST['spesq_serie'])) {
?>
<h3>cadastrar equipamento</h3>
<?php
if ($_POST['cadastrar_eqpto']) {
$_POST['identificador'] = strtoupper($_POST['identificador']);
  if (($_POST['identificador']=='')||($_POST['problema']=='')) {
  	echo "É obrigatório o preenchimento dos campos 'identificador' e 'problema'.<br />";
  } else {
    	$sql  = "INSERT INTO  `equipamentos` (`identificador` ,`cliente` ,`problema` ,`obs`) "; 
    	$sql .= "VALUES ('".$_POST['identificador']."',  '".$_POST['cliente']."',  '".$_POST['problema']."',  '".$_POST['observacao']."');";
	if (mysql_query($sql)) {
 		echo "Equipamento <b>".$_POST['identificador']."</b> foi cadastrado com sucesso.<br />";
		evento('equipamentos','cadastrar equipamento: '.$_POST['identificador']);
	}  else {
		if (mysql_errno()==1062) {
			echo "O número de série <b>".$_POST['identificador']."</b> já foi cadastrado anteriormente.";
			evento('equipamentos','erro ao cadastrar equipamento: '.$_POST['identificador']);
		} else {
			echo "Erro ao cadastrar equipamento: (".mysql_errno() . "): " . mysql_error();
		}
	}
  }
} 

?>
<table>
<form action="?m=equipamentos" method="post" >
<tr>
<td>Cliente</td>
<td><select name="cliente">
<?php
$yes = @mysql_query("SELECT * FROM `clientes` ORDER BY `cliente` ASC");
  if ($yes) {
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
<td>Série/Identificador:</td>
<td><input name="identificador" size="30" maxlength="30"></td>
</tr>
<tr>
<td>Problema:</td>
<td><input name="problema" size="30"></td>
</tr>
<tr>
<td>Observação:</td>
<td><textarea name="observacao"></textarea></td>
</tr>
<tr><td><input type="submit" name="cadastrar_eqpto" value="cadastrar"></td></tr>
</form>
</table>
<?php
 }
?>
<?php
//
if ((!$_POST['pesq_cli'])&&(!$_POST['cadastrar_eqpto'])) {
?>
<h3>pesquisar equipamento</h3>
<table>
<form action="?m=equipamentos" method="post" >
<tr>
<td>Série/Identificador:</td>
<td><input name="identificador" value="<?=$_POST['identificador'];?>" size="30"></td>
<td><input name="spesq_serie" type="submit" value="buscar"></td>
</tr>
</form>
</table>
<?php
 if ($_POST['spesq_serie']) {
	 evento('equipamentos','visualizar/editar equipamento: '.$_POST['identificador']);
     $row = mysql_fetch_array(mysql_query("SELECT * FROM `equipamentos` WHERE `identificador` = '".$_POST['identificador']."' "), MYSQL_ASSOC);
	if ($row['identificador']!="") {
    
     echo "<form action=\"?m=equipamentos\" method=\"post\" >";
     echo "<table>";
     echo "<input size=40 name=identificador type=hidden value=\"".$row['identificador']."\">";
     echo "<tr><td>Identificador<td><td><input size=40 disabled=disabled value=\"".$row['identificador']."\"></td></tr>";
     echo "<tr><td>Cliente<td><td><input name=cliente size=40 disabled=disabled value=\"".$row['cliente']."\"></td></tr>";
     echo "<tr><td>Problema<td><td><input name=problema size=30 value=\"".$row['problema']."\"></td></tr>";	
     echo "<tr><td>Observação<td><td><textarea name=obs>".$row['obs']."</textarea></td></tr>";
     echo "</table><table>";
     echo "<tr><td><input name=atualizar value='atualizar este registro' type=submit></td></tr>";
     echo "<tr><td><input name=excluir onclick=\"if(window.confirm('Deseja realmente excluir este registro?')) return true; else return false;\" value='excluir este registro' type=submit></td></tr>";
     echo "</table>";
     echo "</form>";	
	} else {
	echo "Nenhum equipamento foi encontrado este número de série.";
	}
  }
 }
?>

<?php
//
if ((!$_POST['spesq_serie'])&&(!$_POST['cadastrar_eqpto'])) {
?>
<h3>listar equipamentos de um cliente</h3>
<table>
<form action="?m=equipamentos" method="post" >
<tr>
<td>cliente:</td>
<td><select name="cliente">
<?php
$yes = @mysql_query("SELECT * FROM `clientes` ORDER BY `cliente` ASC");
  if ($yes) {
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
<td><input type="submit" name="pesq_cli" value="buscar"></td>
</tr>
</form>
</table>
<?php
  if ($_POST['pesq_cli']) {
	evento('equipamentos','pesquisar cliente: '.$_POST['cliente']);
	$q = mysql_query("SELECT * FROM  `equipamentos` WHERE `cliente` = '".$_POST['cliente']."'");
	$i = mysql_num_rows($q);
	if ($i>0) {
	echo "<table border=1 style=\"border-style:hidden\">";
	echo "<tr><td><center>id</center></td><td><center>cliente</center></td><td><center>problema</center></td><td><center>ação</center></tr>";
	 while ($row = mysql_fetch_array($q,MYSQL_ASSOC)) {
		echo "<tr>";
		echo "<td><center>".$row['identificador']."</center></td><td><center>".$row['cliente']."</center></td><td><center>".$row['problema']."</center></td>";
		echo "<td><form action=\"?m=equipamentos\" method=\"post\">";
		echo "<input type=hidden name=identificador value=\"".$row['identificador']."\">";
		echo "<input type=submit name=spesq_serie value=\"editar/exibir\">";
		echo "</td></tr>";
	 }
	 echo "</table>";
        } else {
		echo "Não foram encontrados equipamentos para o cliente informado.";
	}
  }
 }
?>
