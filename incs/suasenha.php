<?php if (!defined("_SEGURO")) die("Acesso Negado");
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
?>
<script src="incs/js/jquery.validate.js" type="text/javascript"></script> 
<script language="javascript">
	$().ready(function() { 
		<?php 
		if ($_POST['trocar_senha']) {
			$usr = $_SESSION['id'];
			$pwd = md5($_POST['senhaatual']);
			 $yes = @mysql_query("SELECT * FROM `usuarios` where `usuario` = '$usr' and `senha` = '$pwd';");
    			if ($yes) {
			      $row = mysql_fetch_array($yes, MYSQL_ASSOC);
			      if (($row['usuario'] == $usr) && ($row['senha'] == $pwd)) {					
					mysql_query("UPDATE `usuarios` SET `senha` = '".md5($_POST['novasenha'])."' WHERE `id` = ".$row['id'].";");
				?>
					window.alert("Senha atualizada com sucesso!");
				<?php 	
                               } else {

				?>
				window.alert("A senha atual é inválida.");
				<?php
				 }  
			}
		}
		?>

		$('input[name=s_atual]').focus();
		$("#trocarSenha").validate({
			rules: {
				senhaatual: "required",
				novasenha: {
					required: true,
					minlength: 5
				},
				cnovasenha: {
					required: true,
					minlength: 5,
					equalTo: "#novasenha"
				},
			},
			messages: {
				senhaatual: "Entre com a sua senha atual.",
				novasenha: {
					required: "Entre com a sua nova senha.",
					minlength: "Sua senha tem que possuir no minímo 5 caracteres."
				},
				cnovasenha: {
					required: "Confirme a sua nova senha ",
					minlength: "Sua senha tem que possuir no minímo 5 caracteres.",
					equalTo: "A senha informada é inválida. "
				}
			}
		});
	
		
	});
</script>
<h2>alterar senha</h2>
<h3>altere sua senha de acesso</h3>
<form class="cmxform" method="post"  id="trocarSenha"  action="?m=suasenha"> 
	<fieldset> <br />
			<label>senha atual</label> 
			<input name="senhaatual" id="senhaatual" type="password"><br /><br />
			<label>nova senha</label> 
			<input name="novasenha" id="novasenha" type="password"><br /><br />
			<label>confirmar senha</label> 
			<input name="cnovasenha"  id="cnovasenha" type="password"><br /><br />
			<input type="submit" name="trocar_senha" value="salvar alteração">
	</fieldset> 
</form> 
