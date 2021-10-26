<?php
define("_SEGURO",1);
include("config.php");
include("incs/extras/funcoes.php");
session_start();
error_reporting(0);

session_set_cookie_params  (999999,"/",$_SERVER['SERVER_ADDR']);
session_cache_limiter('private');
$cache_limiter = session_cache_limiter();
session_cache_expire(30);
$cache_expire = session_cache_expire();

if (isset($_SESSION['encode'])) {
 $h = explode('@',$_SESSION['encode']);
 $_SESSION['id'] = $h[0];       
 $_SESSION['nivel'] = $h[1];
}

if (@$_POST['logar']) {
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
    $usr = strtolower($_POST['usuario']);
    $usr=strtr($usr, '\'', '*');
    $pwd = $_POST['senha'];
    $pwd=strtr($pwd, '\'', '*');
    $pwd=md5($pwd);
    $yes = @mysql_query("SELECT * FROM `usuarios` where `usuario` = '$usr' and `senha` = '$pwd';");
    if ($yes) {
      $row = mysql_fetch_array($yes, MYSQL_ASSOC);
      if (($row['usuario'] == $usr) && ($row['senha'] == $pwd)) {
         $_SESSION['encode'] = $row['usuario']."@".round($row['nivel']);
	 $_SESSION['id'] = $row['usuario'];
         session_encode();
         session_write_close();
	 mysql_query("INSERT INTO `history` (`id` ,`usuario` ,`date` ,`ip`) VALUES ( NULL , '".$row['usuario']."',CURRENT_TIMESTAMP , '".gethostbyaddr(get_ip())."');");
	 evento('Tela de Login','Login efetuado com sucesso.');
         Header("Location: .");
         exit;
      } else {
	 if ($_SESSION!=$_POST['id']) {	 
		 mysql_query("INSERT INTO `history` (`id` ,`usuario` ,`date` ,`ip`) VALUES ( NULL , '".$_POST['usuario']."',CURRENT_TIMESTAMP , '".gethostbyaddr(get_ip())."');");
		 $_SESSION['id'] = $_POST['usuario'];
	 } 
         evento('Tela de Login','Acesso negado.');
      }
    } 
}

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile|o2|opera m(ob|in)i|palm( os)?|p(ixi|re)\/|plucker|pocket|psp|smartphone|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce; (iemobile|ppc)|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)
|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
$mobile=true;
} else {
$mobile=false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
<head>
<title>Gestor de Chamados</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="description goes here" />
<meta name="keywords" content="keywords,goes,here" />
<script type="text/javascript" src="incs/js/jquery-1.7.1.min.js"></script>
<?php if (!$mobile) { ?>
<script type="text/javascript" src="incs/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	// O2k7 skin (silver)
	tinyMCE.init({
		// General options
		language : 'pt',
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",

 		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist",
		theme_advanced_buttons2 : "outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true,
 
	});
</script>
<?php } ?>
<?php 
if ($mobile) {
	echo "<link rel=\"stylesheet\" href=\"mobile.css\" type=\"text/css\" />";
} else {
	echo "<link rel=\"stylesheet\" href=\"andreas07.css\" type=\"text/css\" />";
}
?>
  
<!--[if IE 6]>
<link rel="stylesheet" href="fix.css" type="text/css" />
<![endif]-->
</head>
<body>
<div id="sidebar">
<?php if (isset($_SESSION['nivel'])) {?>
<h1>Gestor de Chamados</h1>
<h2>Central de Suporte</h2>
<div id="menu">
<a <?php if ($_GET['m']=="chamado") echo "class=\"active\"";?> href="?m=chamado">abrir chamado</a>
<a <?php if ($_GET['m']=="aberto") echo "class=\"active\"";?> href="?m=aberto">chamados abertos</a>
<a <?php if ($_GET['m']=="arquivo") echo "class=\"active\"";?> href="?m=arquivo">arquivo morto</a>
<?php if (!$mobile) { ?>
<a <?php if ($_GET['m']=="cobranca") echo "class=\"active\"";?> href="?m=cobranca">cobrança</a>
<a <?php if ($_GET['m']=="estatisticas") echo "class=\"active\"";?> href="?m=estatisticas">estatísticas</a>
<a <?php if ($_GET['m']=="orcamento") echo "class=\"active\"";?> href="?m=orcamento">orçamento</a>
<a <?php if ($_GET['m']=="equipamentos") echo "class=\"active\"";?> href="?m=equipamentos">equipamentos</a>
<?php } ?>
<a <?php if ($_GET['m']=="relatorios") echo "class=\"active\"";?> href="?m=relatorios">relatórios de serviço</a>
<a <?php if ($_GET['m']=="cadastro") echo "class=\"active\"";?> href="?m=cadastro">cadastro de cliente</a>
<a <?php if ($_GET['m']=="listar") echo "class=\"active\"";?> href="?m=listar">listar clientes</a>
<?php if ($_SESSION['nivel']==1) { ?>
<a <?php if ($_GET['m']=="usuarios") echo "class=\"active\"";?> href="?m=usuarios">usuários</a>
<?php } else { ?>
<a <?php if ($_GET['m']=="suasenha") echo "class=\"active\"";?> href="?m=suasenha">alterar senha</a>
<?php } ?>
<a <?php if ($_GET['m']=="acesso") echo "class=\"active\"";?> href="?m=acesso">histórico de acesso</a>
<a href="sair.php">sair</a>
</div>
<?php
@include("versao.php");
 } ?>
</div>
<div id="content">
<?php if (!isset($_SESSION['nivel'])) {
include("incs/login.php");
} else {
 if ($_GET['m']=="") {
  include("incs/aberto.php");
 } else {
 if (file_exists("incs/".$_GET['m'].".php")) {
  include("incs/".$_GET['m'].".php");
  }
 }
}
?>
</div>
</body>
</html>