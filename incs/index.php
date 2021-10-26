<?php
define("_SEGURO",1);
include("config.php");
session_start();

function get_ip()
{
    $variables = array('REMOTE_ADDR',
                       'HTTP_X_FORWARDED_FOR',
                       'HTTP_X_FORWARDED',
                       'HTTP_FORWARDED_FOR',
                       'HTTP_FORWARDED',
                       'HTTP_X_COMING_FROM',
                       'HTTP_COMING_FROM',
                       'HTTP_CLIENT_IP');

    $return = 'Unknown';

    foreach ($variables as $variable)
    {
        if (isset($_SERVER[$variable]))
        {
            $return = $_SERVER[$variable];
            break;
        }
    }
    
    return $return;
}

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

if ($_POST['logar']) {
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
    $usr = $_POST['usuario'];
    $usr=strtr($usr, '\'', '*');
    $pwd = $_POST['senha'];
    $pwd=strtr($pwd, '\'', '*');
    $pwd=md5($pwd);
    $yes = @mysql_query("SELECT * FROM `usuarios` where `usuario` = '$usr' and `senha` = '$pwd';");
    if ($yes) {
      $row = mysql_fetch_array($yes, MYSQL_ASSOC);
      if (($row['usuario'] == $usr) && ($row['senha'] == $pwd)) {
         $_SESSION['encode'] = $row['usuario']."@".round($row['nivel']);
         session_encode();
         session_write_close();
	 mysql_query("INSERT INTO `l98924_chamados`.`history` (`id` ,`usuario` ,`date` ,`ip`) VALUES ( NULL , '".$row['usuario']."',CURRENT_TIMESTAMP , '".gethostbyaddr(get_ip())."');");
         Header("Location: index.php");
         exit;
      }
    } 
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
<link rel="stylesheet" href="andreas07.css" type="text/css" />
<!--[if IE 6]>
<link rel="stylesheet" href="fix.css" type="text/css" />
<![endif]-->
</head>
<? if (($_POST['logar']) && ($yes)) { ?>
<body onload="javascript:window.alert('Login/Senha Inválidos');">
<? } else { ?>
<body>
<? } ?>

<div id="sidebar">

<?php if (isset($_SESSION['nivel'])) {?>
<h1>Gestor de Chamados</h1>
<h2>megadata informática</h2>

<div id="menu">
<a <?php if ($_GET['m']=="chamado") echo "class=\"active\"";?> href="?m=chamado">abrir chamado</a>
<a <?php if ($_GET['m']=="aberto") echo "class=\"active\"";?> href="?m=aberto">chamados abertos</a>
<a <?php if ($_GET['m']=="arquivo") echo "class=\"active\"";?> href="?m=arquivo">arquivo morto [ x ]</a>
<a <?php if ($_GET['m']=="relatorios") echo "class=\"active\"";?> href="?m=relatorios">relatórios de serviço</a>
<a <?php if ($_GET['m']=="log") echo "class=\"active\"";?> href="?m=log">buscar chamados</a>
<a <?php if ($_GET['m']=="cadastro") echo "class=\"active\"";?> href="?m=cadastro">cadastro de cliente</a>
<a <?php if ($_GET['m']=="listar") echo "class=\"active\"";?> href="?m=listar">listar clientes</a>
<?php if ($_SESSION['nivel']==1) { ?>
<a <?php if ($_GET['m']=="usuarios") echo "class=\"active\"";?> href="?m=usuarios">usuários</a>
<?php } ?>
<a <?php if ($_GET['m']=="usuarios") echo "class=\"active\"";?> href="?m=acesso">histórico de acesso</a>
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
  include("incs/entrada.php");
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
