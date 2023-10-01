<?php
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');
	
	if(!isset($cor_msg)){
		$cor_msg='';
	}
	if(!isset($resp_grava)){
		$resp_grava='';
	}
		
	if(isset($_SESSION['volta_m'])){
		$progra = $_SESSION['volta_m'];
	}else {
		$progra = 'entrada.php';
	}
	
?>   

<!DOCTYPE html>
<html lang="pt-br">
    <head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/estilo_cria_formulario.css">
		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script> 
        <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
        <script src="js/bootstrap-datepicker.min.js"></script> 
        <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
        <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	</head>
    <?php
	//Chama a rotina que recebe os parametros passados pela rotina entrada.php
	//require_once('recebe_variaveis_para_criar_formulario.php');
	require_once('recebe_parametros_entrada.php');
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//cabecalho do formulario
    require_once('cabeca_paginas_internas.php');
	
	// Função para pegar o name do botão em que houve o click
	function get_post_action($name) {
		$params = func_get_args();
		foreach ($params as $name) {
		   if (isset($_POST[$name])) {
			   return $name;
		   }
		}
	}

	switch (get_post_action('Incluir','Alterar','Excluir')) {
		case 'Incluir':
		    //chama a rotina cria_form_inclusao.php, para inclusao de dados
			require_once('grava_form_inclusao.php');
			header("Location:".$progra);
		break;

		case 'Alterar':
            //chama a rotina cria_form_inclusao.php, para inclusao de dados
			require_once('grava_form_alteracao.php');
			header("Location:".$progra);
		break;

		case 'Excluir':
			require_once('grava_form_exclusao.php');
			header("Location:".$progra);
		break;
        default:
	}
				
	?>
	<form name="cadastro" class="form-horizontal" id="cadastro" action="cria_formulario.php" method="post">
    <div class="tabContainer" id="lista" style="width:750px;float:lef;position:relative;margin-top:70px;margin-left:300px;">
	  <div id="mensagem">
		  <?php if($cor_msg == 'D'){ ?>
			  <div class="alert alert-danger" role="alert" class="msg_conteudo">
				  <?php echo $resp_grava; ?>
			  </div>
		  <?php } ?>
		  <?php if($cor_msg == 'S'){ ?>
			  <div class="alert alert-success" role="alert" class="msg_conteudo">
				  <?php echo $resp_grava; ?>
			  </div>
		  <?php } ?>
	  </div>
	  <br><br>
	  <table class="table table-sm table-condensed table-bordered table-hover" style="width:750px;border: 2px solid #191970;">
	     <thead>
    		  <tr ng-repeat="item in lstMov" class="small" style="background-color:#191970;border: 2px solid #191970;">
    			<th width="11.3%" colspan="2" class="text-center" style="height:40px;vertical-align:middle;"><font size="4" face="arial" color="#ffffff"><b><?php echo $titulo_form;?></b></font></th>
    		  </tr>
    	 </thead>
		 <?php
			//Pega o nome da tabela em uso das variaveis globais//
			$tabela = $_SESSION['tabela_m'];
			
			//inicializa a variavel $campos
			$campos    ='';
			
			// Pega o conteudo da variavel acao.
			if(isset($_GET['acao'])) {
				$acao =	$_GET['acao'];
				$_SESSION['acao_m'] = $_GET['acao'];
			} else {
				if(isset($_SESSION['acao_m'])){
				   $acao = $_SESSION['acao_m'];
				}
			}
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//Define o nome do botão do submit em função da ação determinada
			if(isset($acao)) {
				if($acao == 1) {
				   $nome_botao ='Incluir';	
				}
				if($acao == 2) {
				   $nome_botao ='Alterar';	
				}
				if($acao == 3) {
				   $nome_botao ='Excluir';	
				}
				$_SESSION['botao_m'] = $nome_botao;
			}
			
			//Pega a chave de pesquisa enviada pela tela_entra_cadastro.php, quando é selecionado o registro
			if(isset($_GET['codigo'])){
				$chave_pesquisa = $_GET['codigo'];
				$_SESSION['chave_pesquisa_m'] =$chave_pesquisa;
			}
			else {
				$chave_pesquisa ='';
				$_SESSION['chave_pesquisa_m'] =$chave_pesquisa;
			}
	        
			//Para os casos de inclusao de registros
			if($acao == '1') {
				require_once('cria_form_formulario_inclusao.php');
			}
			
			//Para os casos de alteração e exclusão
			if($acao == '2' or $acao == '3') {
				require_once('cria_form_formulario_altera_exclui.php');
			} 

			//Verifica a ação envia e procede de acordo com a ação
			
			if($acao == '1'){ 
					$condicao ='';
			}
			else {
				  if(isset($_SESSION['c_indice_m'])) {
					$campo_pesquisa = $_SESSION['c_indice_m'];
				  } else {
					$campo_pesquisa ='';  
				  }
				  if(isset($_SESSION['pesquisa_m'])) {
					$chave_pesquisa = $_SESSION['pesquisa_m'];
				  }else {
					$chave_pesquisa ='';
				  }
				  $condicao =$campo_pesquisa.'='."'".$chave_pesquisa."'";
			}
			
			$_SESSION['condicao_m']  = $condicao;
			
            //echo "<p>Condição :".$condicao;
			
		 ?>
		 <tr>
		    <td colspan="2" style="border: 1px solid #0000FF;background-color:#0000FF;"></td>
		 </tr>
		 <tr>
			<td colspan="2" style="background-color:#191970;vertical-align:middle !important;height:50px;">
				<div style="width:20px;height:20px;float:left;position:relative;margin-left:5px;margin-top:-6px;">
				   <a href="<?php echo $progra;?>"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
				</div>
				<div style="width:20px;height:20px;float:left;position:relative;margin-left:625px;margin-top:-10px;">
				   <?php $nome_botao = $_SESSION['botao_m'];?>
				   <button type="submit" name="<?php echo $nome_botao;?>" value="<?php echo $nome_botao;?>" class="btn btn-primary glyphicon glyphicon-pencil"><font face="arial"><?php echo $nome_botao;?></font></button>
				</div>
			</td>
	     </tr>
      </table>
    </div>
    </form>
</body>
</HTML>
		
   

 