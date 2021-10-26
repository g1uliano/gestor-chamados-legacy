<?php
define("_SEGURO",1);
include("../config.php");
include("../incs/extras/funcoes.php");
session_start();
if (!isset($_SESSION['encode'])) {
	die("<H1>ACESSSO NEGADO!</H1>");
}
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
 evento('chamados abertos','Gerar Ordem de Serviço: '.$_GET['id']);
 $qry = mysql_query("SELECT * FROM `chamados` WHERE `id` = '".$_GET['id']."' ");
 $row = mysql_fetch_array($qry, MYSQL_ASSOC);
 $qry = mysql_query("SELECT * FROM `clientes` WHERE `cliente` = '".$row['cliente']."'");
 $cli = mysql_fetch_array($qry, MYSQL_ASSOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ordem de Serviço - No. <?=$_GET['id']?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<p>
  <script type="text/javascript">
self.print ();
  </script>
<table width="640" border="1" align="center" cellpadding="4" cellspacing=" 0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellspacing=" 0" cellpadding="0">

      <tr bgcolor="#FFFFCC">
        <td width="151">&nbsp;</td>
        <td align="center" bgcolor="#FFFFCC"><p>
          </b><b><h2>MEGADATA INFORMÁTICA</h2></b></p>
<b><b>ORDEM DE SERVI&Ccedil;O - No. <?=$_GET['id']?></b><br /><br /></td>
      </tr>
    </table></td>
  </tr>

  <tr>
    <td><table width="100%" border="0" cellspacing=" 0" cellpadding="0">
      <tr>
        <td width="440"><b><font color="#000000">SOLICITANTE:&nbsp;</font></b><font color="#000000"><?=$row['solicitante']?></font><br />
                      </font></td>
        <td width="188" valign="top"><b>ABERTURA:</b>&nbsp;<?=$row['data_abertura']?> <?=$row['hora_abertura']?><b><br />
            </b></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><div align="left">

      <table width="100%" border="0" cellspacing=" 0" cellpadding="0">
        <tr>
          <td><div align="center"><b>DADOS 
            DO CLIENTE</b></div></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td><b>Raz&atilde;o Social/Nome:</b> <?=$cli['cliente']?><br />
              </td>
            </tr>
            <tr>
              <td><b>Endere&ccedil;o:</b> <?=$cli['endereco']?><br />
              </td>
            </tr>
            <tr>
              <td><b>Telefone:</b> <?=$cli['telefone']?><br />
              </td>
            </tr>

          </table>
            
        </tr>
      </table>
    </div></td>
  </tr>

  <tr>
    <td>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td colspan="2"><div align="center"><b>MOTIVO DA SOLICITAÇÃO</b></div></td>
      </tr> 
      <tr>
        <td colspan="2" valign="top"><p><?=nl2br(strip_tags(stripslashes($row['motivo'])));?></p></td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
	<p>O cliente possui contrato? [&nbsp;&nbsp;&nbsp;&nbsp;] Sim [&nbsp;&nbsp;&nbsp;&nbsp;] Não<br/>
	Visita improdutiva? [&nbsp;&nbsp;&nbsp;&nbsp;] Sim [&nbsp;&nbsp;&nbsp;&nbsp;] Não<br/>
        O Problema foi resolvido? [&nbsp;&nbsp;&nbsp;&nbsp;] Sim [&nbsp;&nbsp;&nbsp;&nbsp;] Não</p>
	<p>Inicio do Atendimento: ____/____/_____ às ____:____ hrs.</p> <p>Término do Atendimento: ____/____/_____ às ____:____ hrs.</p>
	<br />
</td>
      </tr>
     
      <tr>
        <td valign="top">&nbsp;&nbsp;&nbsp;______________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />

              <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura 
                do T&eacute;cnico</b>&nbsp;</td>
        <td valign="top"><div align="right">_____________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
                  <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura 
                    do Cliente</b>&nbsp;<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>

    <td><div align="right"><b>megadata informática - suporte técnico especializado</b></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>