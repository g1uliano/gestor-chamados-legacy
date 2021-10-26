<?php
/***************************************/
/*
/* MEGADATA INFORMÁTICA
/*
/**************************************/
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

    $return = 'Desconhecido';

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

function evento($modulo,$acao) {
	$get = mysql_query("SELECT * FROM  `history` WHERE `usuario` = '".$_SESSION['id']."' ORDER BY `id` DESC;");
	$trow = mysql_fetch_array($get, MYSQL_ASSOC);
	mysql_query("INSERT INTO `rastreio` (`id` ,`hid` ,`modulo` ,`acao`, `tempo`) VALUES ( NULL , '".$trow['id']."', '".strtolower($modulo)."' , '".strtolower($acao)."',CURRENT_TIMESTAMP);");
}
?>
