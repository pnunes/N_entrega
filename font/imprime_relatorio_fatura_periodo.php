<?php 
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	
	//inicializando variaveis
	if(isset($resp_grava)){
		$_SESSION['resp_grava_m'] = $resp_grava;
	}else {
		$resp_grava ='';
	}
	
	if(isset($_GET['cor'])){
		$cor_msg = $_GET['cor'];
	}else {
		$cor_msg ='';
	}
	if(isset($_GET['titulo_form'])){
		$titulo_form = $_GET['titulo_form'];
	}else {
		$titulo_form ='';
	}
	if(isset($_GET['modulo'])){
		$modulo = $_GET['modulo'];
	}else {
		$modulo ='';
	}
	if(!isset($dt_ini)){
		$dt_ini ='';
	}
	
	if(!isset($dt_fim)){
		$dt_fim ='';
	}
	
	if(!isset($data_inicio)){
		$data_inicio ='';
	}
	
	if(!isset($data_final)){
		$data_final ='';
	}
	// fim da inicialização de variaveis
	
	// Função para pegar o name dos submits
	function get_post_action($name) {
		$params = func_get_args();
		foreach ($params as $name) {
		   if (isset($_POST[$name])) {
			   return $name;
		   }
		}
	}
?>
<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
   <!-- <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
	
	<link rel="stylesheet" type="text/css" href="css/imprime_relato_fatura_periodo.css">
	<?php
	
	//Chama a rotina que recebe os parametros passados pela rotina entrada.php
	//require_once('recebe_parametros_entrada.php');
	?>
  </head>
<body>
   <?php
       require_once('Conexao.php');
       $conn = new Conexao();
       $cone = $conn->Conecta();
	   mysqli_set_charset($cone,'UTF8');
	   
	   //Pega o nome do USUARIO da variavel global, que é mostrado no cabeçalho da pagina
	   $nome_usu  = $_SESSION['nome_m'];
	   
	   //Chama a rotina que monta o cabeçalho da pagina
       require_once('cabeca_paginas_internas.php'); 

       switch (get_post_action('datas')) {
		  case 'datas':
		      $dt_ini      = $_POST['data_inicio'];
			  $_SESSION['dt_ini_m'] = $dt_ini;
			  
			  // formatando data para pesquisar na tabela
		      $data_ini    = explode("/",$dt_ini);
			  $data_inicio = $data_ini[2]."-".$data_ini[1]."-".$data_ini[0];
			  
		      $dt_fim     = $_POST['data_fim'];
			  $_SESSION['dt_fim_m'] = $dt_fim;
			  
			  // formatando data para pesquisar na tabela
		      $data_fim    = explode("/",$dt_fim);
			  $data_final   = $data_fim[2]."-".$data_fim[1]."-".$data_fim[0];
			  
			  //colocando as data de pesquisa numa variavel global.
			  $_SESSION['data_inicio_m'] = $data_inicio;
			  $_SESSION['data_fim_m']    = $data_final;
			  
			  header("Location:gera_relatorios_faturamento.php?tipo_rela=fatu_periodo");
			  
		  break;
		  default:
	   } 
	   
   ?>
   <form name="cadastro" id="cadastro" align="center" action="" method="post">
        <table id="tb_datas">
		   <tr>
			  <td id="t_form" colspan="2"><?php echo $titulo_form;?></td>
		   </tr>
		   <tr>
		      <td id="data_pesquisa">
				<div class="input-group" id="c_dt_inicio">
				   <?php if(isset($_SESSION['data_ini_m'])) {
					   $data_lista = $_SESSION['data_ini_m'];
				   } else {
					   $data_lista = date('d/m/Y');
				   }?>
				   <span class="input-group-addon">Data Inicio</span>
				   <div class="input-group date">
						<input type="text" class="form-control" id="data_inicio" value="<?php echo $dt_ini;?>" name="data_inicio">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
				   </div>
				   <script type="text/javascript">
						$('#data_inicio').datepicker({	
						format: "dd/mm/yyyy",	
						language: "pt-BR",
						/*startDate: '+0d',*/
					});
				   </script>
				</div>				
			    <div class="input-group" id="c_dt_fim">
				   <?php if(isset($_SESSION['data_fim_m'])) {
					   $data_lista = $_SESSION['data_fim_m'];
				   } else {
					   $data_lista = date('d/m/Y');
				   }?>
				   <span class="input-group-addon">Data fim</span>
				   <div class="input-group date">
						<input type="text" class="form-control" id="data_fim" value="<?php echo $dt_fim;?>" name="data_fim">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
				   </div>
				   <script type="text/javascript">
						$('#data_fim').datepicker({	
						format: "dd/mm/yyyy",	
						language: "pt-BR",
						/*startDate: '+0d',*/
					});
				   </script>
				</div>	
			    <button type="Submit" id="bt_data" name="datas" class="btn btn-primary">Gerar Relatorio</button>
		     </td>
		  </tr>
	    </table>
        <div style="width:1150px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:100px;margin-top:0px;">
		   <div style="width:85px;height:40px;float:left;position:relative;margin-left:7px;margin-top:6px;">
			   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
		   </div
		</div>
 
</body>
</html>