<?php if (!defined("_SEGURO")) die("Acesso Negado");
$conexao = @mysql_connect($sql["servidor"],$sql["usuario"],$sql["senha"]);
@mysql_select_db($sql["base"], $conexao)
    or die("Não foi possivel conectar no banco de dados: <br><b>" . mysql_error()."</b><br>");
    evento('estatísticas','visualizar estatísticas');
    //estatísticas globais.
    //

    $qry = mysql_query("SELECT * FROM `usuarios` WHERE ((`id` <> 1) AND (`nivel` <> 3)) ORDER BY `id` ASC");

    for ($gi=0;$row = mysql_fetch_array($qry, MYSQL_ASSOC);$gi++) {
	 $result = mysql_query("SELECT * FROM `chamados` WHERE `tecnico` = '".$row['nomedousuario']."';");
	 $e_global[$gi]['num_rows'] = mysql_num_rows($result);
	 $e_global[$gi]['tecnico'] = $row['nomedousuario'];
    }

//neste mês
    $qry = mysql_query("SELECT * FROM `usuarios` WHERE ((`id` <> 1) AND (`nivel` <> 3)) ORDER BY `id` ASC"); 
    for ($mi=0;$row = mysql_fetch_array($qry, MYSQL_ASSOC);$mi++) {
	$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".date('Y')."-".date('m')."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".date('Y')."-".date('m')."-31'))) AND `tecnico` = '".$row['nomedousuario']."' ORDER BY `ordenador` ASC";
	 $result = mysql_query($sql);
	 $e_mensal[$mi]['num_rows'] = mysql_num_rows($result);
	 $e_mensal[$mi]['tecnico'] = $row['nomedousuario'];
	 $mes_atual += $e_mensal[$mi]['num_rows'];
    }


//mês anterior
    $qry = mysql_query("SELECT * FROM `usuarios` WHERE ((`id` <> 1) AND (`nivel` <> 3)) ORDER BY `id` ASC"); 
	if (date('n')==1) {
		$data = (date('Y')-1)."-12";			
	} else {
		$mes = (date('n')>10)?(date("n")-1):'0'.(date("n")-1);
		$data = date('Y')."-".$mes;			
	}
    for ($ma=0;$row = mysql_fetch_array($qry, MYSQL_ASSOC);$ma++) {
	$sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '$data-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '$data-31'))) AND `tecnico` = '".$row['nomedousuario']."' ORDER BY `ordenador` ASC";
	 $result = mysql_query($sql);
	 $e_anterior[$ma]['num_rows'] = mysql_num_rows($result);
	 $e_anterior[$ma]['tecnico'] = $row['nomedousuario'];
	 $mes_anterior += $e_anterior[$ma]['num_rows'];
    }


   $capm = mysql_num_rows(mysql_query("SELECT * FROM `chamados`;"));
   $sql = "SELECT *,str_to_date(data, '%d/%m/%Y') AS ordenador FROM `chamados` WHERE (`data` <> '' AND ((str_to_date(data, '%d/%m/%Y') >= '".date('Y')."-".date('m')."-01')) AND ((str_to_date(data, '%d/%m/%Y') <= '".date('Y')."-".date('m')."-31'))) ORDER BY `ordenador` ASC";
   $abum = mysql_num_rows(mysql_query($sql));
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">   
      google.load('visualization', '1', {'packages':['piechart']});     

      google.setOnLoadCallback(drawChart);

      function drawChart() {

        var gdata = new google.visualization.DataTable();
        gdata.addColumn('string', 'Técnicos');
        gdata.addColumn('number', 'Chamados Atendidos');
        gdata.addRows([
	 <?php for ($i=0;$i<$gi-1;$i++) {
	   echo  " ['".$e_global[$i]['tecnico']."', ".$e_global[$i]['num_rows']."],\r\n";
	   }
	   echo  " ['".$e_global[$i]['tecnico']."', ".$e_global[$i]['num_rows']."]\r\n";
          ?>
        ]);

        var gchart = new google.visualization.PieChart(document.getElementById('e_global'));
        gchart.draw(gdata, {width: 400, height: 240, is3D: true, backgroundColor: '#fafafa',titleFontSize: 16,title: 'até hoje...'});

        var adata = new google.visualization.DataTable();
        adata.addColumn('string', 'Técnicos');
        adata.addColumn('number', 'Chamados Atendidos');
        adata.addRows([
	 <?php for ($i=0;$i<$ma-1;$i++) {
	   echo  " ['".$e_anterior[$i]['tecnico']."', ".$e_anterior[$i]['num_rows']."],\r\n";
	   }
	   echo  " ['".$e_anterior[$i]['tecnico']."', ".$e_anterior[$i]['num_rows']."]\r\n";
          ?>
        ]);
        var achart = new google.visualization.PieChart(document.getElementById('e_anterior'));
        achart.draw(adata, {width: 400, height: 240, is3D: true, backgroundColor: '#fafafa', titleFontSize: 16,title: 'no mês anterior...'});


<?php
  if ($mes_atual>0) {
?>
        var edata = new google.visualization.DataTable();
        edata.addColumn('string', 'Técnicos');
        edata.addColumn('number', 'Chamados Atendidos');
        edata.addRows([
	 <?php for ($i=0;$i<$mi-1;$i++) {
	   echo  " ['".$e_mensal[$i]['tecnico']."', ".$e_mensal[$i]['num_rows']."],\r\n";
	   }
	   echo  " ['".$e_mensal[$i]['tecnico']."', ".$e_mensal[$i]['num_rows']."]\r\n";
          ?>
        ]);
        var echart = new google.visualization.PieChart(document.getElementById('e_mensal'));
        echart.draw(edata, {width: 400, height: 240, is3D: true, backgroundColor: '#fafafa', titleFontSize: 16,title: 'neste mês...'});
<?php 
}
?>
      }
    </script>
<script language="javascript">
</script>

<h2>estatísticas</h2>
<h3>chamados fechados por técnico</h3>
<div id="e_global"></div>
<div id="e_anterior"></div>
<div id="e_mensal"></div>
<h3>mais informações</h3>
<font align="justify">
	<font size=4>chamados registrados até hoje: </font><font size=6><?=$capm?></font> </font><br />
	<font size=4>chamados fechados no mês anterior: </font><font size=6><?=$mes_anterior?></font> </font><br />
	<font size=4>chamados fechados neste mês (até hoje): </font><font size=6><?=$mes_atual?></font> </font><br />
</font>
