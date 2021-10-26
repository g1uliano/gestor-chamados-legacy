<?php if (!defined("_SEGURO")) die("Acesso Negado");
if ($_SESSION['nivel']!=1) die();
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");

 if ($_POST['cadastrado']) {
   $ehneh = mysql_query("SELECT * FROM `usuarios` WHERE `usuario` = '".$_POST['usuario']."'");
   $myx = mysql_fetch_array($ehneh, MYSQL_ASSOC);
   if ($myx['usuario'] == "") {
   $ox = mysql_query("SELECT *FROM `usuarios`  ORDER BY `usuarios`.`id` DESC");
   $mow = mysql_fetch_array($ox, MYSQL_ASSOC);
   $mow['id']++;
   $sql = "INSERT INTO `usuarios` ( `id` , `usuario` , `nomedousuario`, `email` , `senha` , `nivel`) VALUES ( '".$mow['id']."', '".$_POST['usuario']."', '".$_POST['nomedousuario']."', '".$_POST['email']."' ,  '".md5($_POST['senha'])."', '".$_POST['nivel']."');";
   mysql_query($sql,$conexao);
   }
 }

 if ($_POST['edicao']) {
      if ($_POST['senha'] != '') {
	mysql_query("UPDATE `usuarios` SET `id` = '".$_POST['id']."', `usuario` = '".$_POST['usuario']."', `nomedousuario` = '".$_POST['nomedousuario']."', `senha` = '".md5($_POST['senha'])."', `email` = '".$_POST['email']."', `nivel` = '".$_POST['nivel']."' WHERE CONVERT( `usuarios`.`id` USING utf8 ) = '".$_POST['id']."' ;");

     } else {
         mysql_query("UPDATE `usuarios` SET `id` = '".$_POST['id']."', `usuario` = '".$_POST['usuario']."', `nomedousuario` = '".$_POST['nomedousuario']."', `email` = '".$_POST['email']."', `nivel` = '".$_POST['nivel']."' WHERE CONVERT( `usuarios`.`id` USING utf8 ) = '".$_POST['id']."' ;");

     }
 }

 if ($_POST['excluir']) {
   mysql_query("DELETE FROM `usuarios` WHERE CONVERT(`usuarios`.`id` USING utf8) = '".$_POST['id']."' LIMIT 1");
 }
?>

<h2>controle de usuários</h2>

<h3>usuários cadastrados</h3>
<form name="usr_cad" action="?m=usuarios&cad=editar" method="post">
<?php
$yes = @mysql_query("SELECT * FROM `usuarios` WHERE (`id` <> 1 AND `d` <> 1 ) ORDER BY `id` ASC");
  if ($yes) {
?>
<select name="id" style="border-style:double; color:#000000">
<?php
  while($row = mysql_fetch_array($yes, MYSQL_ASSOC)) {
?>
 <option <?php if ($_POST['id']==$row['id']) echo "selected=\"selected\""; ?> value="<?php echo $row['id'];?>"><?php echo $row['usuario'];?></option>
<?php }
}
 ?>
</select>
&nbsp;<input type="submit" style="border-style:double; color:#000000" value="editar" name="editar">
</form>
<?php if ($_GET['cad'] != 'editar') { ?>
<script language="javascript">
function validaForm(){
d = document.cadastrodeusr;
if (d.usuario.value== ""){
                   alert('O campo "usuário" deve ser preenchido.');
                   d.usuario.focus();
                   return 0;
      }
if (d.nomedousuario.value== ""){
                   alert('O campo "nome do usuário" deve ser preenchido.');
                   d.nomedousuario.focus();
                   return 0;
      }
if (d.senha.value== ""){
                   alert('O campo "senha" deve ser preenchido.');
                   d.senha.focus();
                   return 0;
      }
}
</script>
<h3>cadastrar novo usuário</h3>
<table>
<form name="cadastrodeusr" action="?m=usuarios&cad=novo" method="post"><tr>
<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">usuário:</font></td>
<td><input type="text" name="usuario" style="border-style:double; color:#000000"></td>
</tr>

<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">nome do usuário:</font></td>
<td><input type="text" name="nomedousuario" style="border-style:double; color:#000000"></td>
</tr>

<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">e-mail:</font></td>
<td><input type="text" name="email" style="border-style:double; color:#000000"></td>
</tr>

<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">senha:</font></td>
<td><input type="password" name="senha" style="border-style:double; color:#000000"></td>
</tr>

<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">nível:</font></td>
<td><select name="nivel" style="border-style:double; color:#000000">
    <option value="1">Administrador</option>
    <option value="2">Técnico</option>
    <option value="3">Usuário</option>
    </select>
</td>
</tr>
<tr>
<td><input type="submit" name="cadastrado" value="salvar cadastro" onclick="if (validaForm()==0) return false; else return true;" onkeypress="if (validaForm()==0) return false; else return true;" style="border-style:double; color:#000000"></td>
 <td></td>
</tr>
</form>
</table>
<?php } else { ?>
<h3>editar usuário</h3>
<?php
$edt = mysql_query("SELECT * FROM `usuarios` WHERE (`id` <> 1 AND `d` <> 1 ) AND`id` = ".$_POST['id']);
  if ($edt) {
  $xow = mysql_fetch_array($edt, MYSQL_ASSOC);
?>
<table>
<script language="javascript">
function validaForm(){
d = document.editar;
if (d.usuario.value== ""){  
                   alert('O campo "usuário" deve ser preenchido.');
                   d.usuario.focus();
                   return 0;
      } 
if (d.nomedousuario.value== ""){  
                   alert('O campo "nome do usuário" deve ser preenchido.');
                   d.nomedousuario.focus();
                   return 0;
      } 
}
</script>
<span id="exibe"></span>
<form name="editar" action="?m=usuarios" method="post">
<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">usuário:</font></td>
<td><input type="text" name="usuario" value="<?php echo $xow['usuario'];?>" style="border-style:double; color:#000000"><input type="hidden" name="id" value="<?php echo $xow['id'];?>"></td>
</tr>
<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">e-mail:</font></td>
<td><input type="text" name="email" value="<?php echo $xow['email'];?>"  style="border-style:double; color:#000000"></td>
</tr>

<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">nome do usuário:</font></td>
<td><input type="text" name="nomedousuario" value="<?php echo $xow['nomedousuario'];?>" style="border-style:double; color:#000000"></td>
</tr>
<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">nova senha:</font></td>
<td><input type="password" name="senha" style="border-style:double; color:#000000"></td>
</tr>
<tr>
<td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">nível:</font></td>
<td><select name="nivel" style="border-style:double; color:#000000">
    <option value="1" <?php if ($xow['nivel']==1) echo 'selected="selected"';?>>Administrador</option>
    <option value="2" <?php if ($xow['nivel']==2) echo 'selected="selected"';?>>Técnico</option>
    <option value="3" <?php if ($xow['nivel']==3) echo 'selected="selected"';?>>Usuário</option>
    </select>
</td>
</tr>
<tr><td><input name="edicao" value="Salvar edição" onclick="if (validaForm()==0) return false; else return true;" onkeypress="if (validaForm()==0) return false; else return true;" type="submit" style="border-style:double; color:#000000">
</td><td>
<?php if ($xow['id']!= 1) { ?>
<input name="excluir" onclick="return confirm('Confirmar exclusão do usuário?');" onkeypress="return confirm('Confirmar exclusão do usuário?');" value="Excluir usuário" type="submit" style="border-style:double; color:#000000">
<?php } ?>
</td>
</tr></form>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
</table>
<?php 
 }
?>
<?php } ?>