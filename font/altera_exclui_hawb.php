<?php
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');

    // criando a variavel resp_grava
	if(!isset($resp_grava)){
	   $resp_grava = '';	
    }
	if(!isset($cor_msg)) {
		$cor_msg ='';
	}
	////////////////////////////////////////////////////////////////////////
    //Pega variavel que identifica qual parte do sistema esta sendo usado
	if(isset($_GET['modulo'])){
		$modulo = $_GET['modulo'];
		$_SESSION['modulo_m'] = $_GET['modulo'];
	}
	////////////////////////////////////////////////////////////////////////
    //Pega variavel que identifica função do formulario
	if(isset($_GET['titulo_form'])){
		$titulo_form = $_GET['titulo_form'];
		$_SESSION['titulo_form_m'] = $_GET['titulo_form'];
	}else {
		$titulo_form = $_SESSION['titulo_form_m'];
	}
	// Pega o codigo do cliente passado por POST pela função pegaLci() do javascript
	if(isset($_SESSION['codi_cli_m'])) {
         $codi_cli   = $_SESSION['codi_cli_m'];
	}
	
	//Pega o numero da hawb passado pela rotina tela_entra_cadastro_operacao.php
	if(isset($_GET['codigo'])){
		$nu_hawb = $_GET['codigo'];
		$_SESSION['nu_hawb_m'] = $_GET['codigo'];
	}else {
		$nu_hawb = $_SESSION['nu_hawb_m'];
	}
    
	// Função para pegar o name dos submits
	function get_post_action($name) {
		$params = func_get_args();
		foreach ($params as $name) {
		   if (isset($_POST[$name])) {
			   return $name;
		   }
		}
	}
	
	//Variavel para controlar o submit do campo cod_barra
	if(!isset($opcao)){
		$opcao = 'b';
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
	<!-- Recursos para o autocomplete - campo nome destino  -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

    <script src="js/bootstrap.min.js"></script> 
    <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/recursos.js"></script>
    <link rel="stylesheet" type="text/css" href="css/altera_exclui_hawb.css">

	<script type="text/javascript">
	    //Função submete o form após digitar o campo numero hawb
	    function salva(campo){
           operacao.submit()
        }
		
        //Função autocompleta para o campo nome destino
        $( function() {
			//Define onde os dados pegos da tabela vao aparecer
			var apresenta = $('#resultados');
			//define os li de uma lista e mantem oculto até que se inicie a digitação dos dados no campo
			apresenta.hide().html('<li style="color:green">Aguarde. Carregando...</li>');
			//Chama a função autocomplete do jquery - esta função manda por get um paremetro chamado term
			$( '#no_destino' ).autocomplete({
				//informa a rotina php que mandara os dados para o autocomplete
				source:'procura_destinos.php'
			});
		});
		
		//Função pega demais dados do destino ao sair do campo no_destino
		$(document).ready(function(){
			$("input[name='no_destino']").blur(function(){
				var $cod_desti     = $("input[name='cd_destino']");
				var $rua_desti     = $("input[name='rua']");
				var $nume_desti    = $("input[name='numero']");
				var $comple_desti  = $("input[name='complemento']");
				var $bairro_desti  = $("input[name='bairro']");
				var $cidade_desti  = $("input[name='cidade']");
				var $cep_desti     = $("input[name='cep_destino']");
				var $estado_desti  = $("input[name='estado']");
				$.getJSON('function.php',{ 
					no_destino: $( this ).val() 
				},function( json ){
					$cod_desti.val( json.cod_desti );
					$rua_desti.val( json.rua_desti );
					$nume_desti.val( json.nume_desti );
					$comple_desti.val( json.comple_desti );
					$bairro_desti.val( json.bairro_desti );
					$cidade_desti.val( json.cidade_desti );
					$cep_desti.val( json.cep_desti );
					$estado_desti.val( json.estado_desti );
				});
			});
		});

		// Função para pegar codigo do cliente para usar no select do serviço
		/*function pegacli() {
			// informa o elemento html de onde esta selecionado o cliente - select - com id cliente
			var eleme = document.getElementById('cliente');
			// Pega da select cliente o cpf ou cnpj do cliente,que é passado pelo select
			var cliente = eleme.options[eleme.selectedIndex].value;
			// constroi as variaveis que serão usadas pelo ajax.
			var vData = { codigo: cliente };
			var vUrl = "ajax.php";
		
			$.post(
				vUrl, //Required URL of the page on server
				vData,
				function(response,status) {
					// tratando o status de retorno. Sucesso significa que o envio e retorno foi executado com sucesso.
					if(status == "success") {
						// pegando os dados jSON
						var obj = jQuery.parseJSON(response);
						$("#result").html(
							"Codigo Cliente: " + obj.codigo 
						);
					}    	
				}
			);
			document.getElementById("operacao").submit(); 
	   };*/

       // Função para verificar se a hawb digitada existe ou não
	  /* $(function(){
			$("#nu_hawb").change(function(){
				//Recuperar o valor do campo
				var pesquisa = $(this).val();
				// Passa conteudo digitado e nome da rotina php a ser chamada para variavies
				var nData = { nume_hawb: pesquisa };
			    var nUrl = "pega_hawb.php";
				
				//Verificar se algo foi digitado
				if(pesquisa != ''){
					$.post(
					nUrl, 
					nData,
					function(response,status) {
						// tratando o status de retorno. Sucesso significa que o envio e retorno foi executado com sucesso.
						if(status == "success") {
							// pegando os dados jSON
							if(response!= '') {
							     alert(response);
							}else {
								document.getElementById('qt_servico').focus()
							}
						}    	
					}
			);
				}
			});
	   });*/
	   
	   // Função para pegar os dados do destino a partir do codigo informado
		function pega_desti() {
			// informa o campo html de onde esta pegando o codigo para passar para o php pesquisar
			var codi_desti = document.getElementById('cd_destino').value;
			//alert(codi_desti);
			//define os camposdo formulario que vao receber o resultado da pesquisa
			var $nome_desti    = $("input[name='no_destino']");
			var $cnpj_desti    = $("input[name='cnpj_destino']");
			var $cep_desti     = $("input[name='cep_destino']");
			var $rua_desti     = $("input[name='rua']");
			var $numero_desti  = $("input[name='numero']");
			var $comple_desti  = $("input[name='complemento']");
			var $bairro_desti  = $("input[name='bairro']");
			var $cidade_desti  = $("input[name='cidade']");
			var $estado_desti  = $("input[name='estado']");
			var $classe_cep    = $("input[name='cl_cep']");
			
			$.getJSON('procura_destino.php',{ 
				codigo: codi_desti 
			},function( json ){
				$nome_desti.val( json.nome_desti );
				$cnpj_desti.val( json.cnpj_desti );
				$cep_desti.val( json.cep_desti );
				$rua_desti.val( json.rua_desti );
				$numero_desti.val( json.numero_desti );
				$comple_desti.val( json.comple_desti );
				$bairro_desti.val( json.bairro_desti );
				$cidade_desti.val( json.cidade_desti );
				$estado_desti.val( json.estado_desti );
				$classe_cep.val( json.classe_cep );
			});
	   };
	</script>
</head>
<body>
    <?php 
	require_once('cabeca_paginas_internas.php'); 
	//require_once('ajax.php'); 
	
	switch (get_post_action('alterar','excluir','pega_hawb')) {
		case 'excluir':
		     $tabela ='movimento';
             $condicao = "n_hawb ="."'".$_SESSION['nu_hawb_m']."'";
			 
			 // Chama a classe crud
			 require_once('CrudMysqli.php');
			 
			 //Instancia a classe crud e executa a gravação do registro na tabela
			 $crudi   = new CrudMysqli('',$tabela,'',$condicao,'');
             $excluir = $crudi->Excluir();
			 $resp_grava = $excluir;
			 
			 if($excluir == '1E') {
				  $cor_msg == 'S';
			      $resp_grava = 'Exclusão realizada com sucessro!';
			  }
			  if($excluir == '2E') {
				  $cor_msg == 'D';
			      $resp_grava = 'Problemas na exclusão! Verifique.';
			  }
			  require_once('limpa_campos_hawb.php');
		break;
		case 'alterar':
		      //Alterando formato das datas para gravar na tabela movimento
		      $dt_movi          = $_POST['data_movi'];
		      $data_movi        = explode("/",$dt_movi);
			  $v_data_movi      = $data_movi[2]."-".$data_movi[1]."-".$data_movi[0];
			  
			  $dt_lista         = $_POST['data_lista'];
			  $data_lista       = explode("/",$dt_lista);
			  $v_data_lista     = $data_lista[2]."-".$data_lista[1]."-".$data_lista[0];
			  
			  $dt_entrega       = $_POST['data_entrega'];
			  $data_entrega     = explode("/",$dt_entrega);
			  $v_data_entrega   = $data_entrega[2]."-".$data_entrega[1]."-".$data_entrega[0];
			  
			  $dt_baixa         = $_POST['data_baixa'];
			  $data_baixa       = explode("/",$dt_baixa);
			  $v_data_baixa     = $data_baixa[2]."-".$data_baixa[1]."-".$data_baixa[0];
			  
			  $dt_lista_dev     = $_POST['data_lista_dev'];
			  $data_lista_dev   = explode("/",$dt_lista_dev);
			  $v_data_lista_dev = $data_lista_dev[2]."-".$data_lista_dev[1]."-".$data_lista_dev[0];
			  
			  $dt_dev_origem    = $_POST['data_dev_orige'];
			  $data_dev_orige   = explode("/",$dt_dev_origem);
			  $v_data_dev_orige = $data_dev_orige[2]."-".$data_dev_orige[1]."-".$data_dev_orige[0];
			  
			  // Pega o valor e altera o formato do mesmo para gravar na tabela
			  $valor 			= $_POST['vr_servico'];
			  if ($valor <>'') {
                if (strlen($valor)>=6) {
                   $valor          = str_replace(".", "", $valor );
                   $valor          = str_replace(",", ".", $valor );
                }
                if (strlen($valor)<6) {
                   $valor          = str_replace(",", ".", $valor );
                }
              }
			  //define quantidade padrão se o campo qtdade não for atualizado ou preenchido
			  $qtdade      = $_POST['qt_servico'];
			  if($qtdade == '') {
				 $qtdade =1;  
			  }
			  
			  $tabela ='movimento';	  
		      $campos ='';
			  
			  //Montando os dados para enviar para o crudMysql para autalização da tabela movimento
			  $dados_up = "escritorio="."'".$_POST['escritorio']."'".","."dt_rece_hawb ="."'".$v_data_movi."'".","."codi_cli="."'".$_POST['cliente']."'".
			  ","."co_servico="."'".$_POST['servico']."'".","."qtdade="."'".$_POST['qt_servico']."'".","."valor="."'".$valor."'".
			  ","."codigo_desti="."'".$_POST['cd_destino']."'".","."nome_desti="."'".$_POST['no_destino']."'".","."cnpj_desti="."'".$_POST['cnpj_destino']."'".
			  ","."rua_desti="."'".$_POST['rua']."'".","."numero_desti="."'".$_POST['numero']."'".","."comple_desti="."'".$_POST['complemento']."'".
			  ","."bairro_desti="."'".$_POST['bairro']."'".","."classe_cep="."'".$_POST['cl_cep']."'".","."cep_desti="."'".$_POST['cep_destino']."'".
			  ","."cidade_desti="."'".$_POST['cidade']."'".","."estado_desti="."'".$_POST['estado']."'".","."dt_lista_entrega="."'".$v_data_lista."'".
			  ","."nu_lista_entrega="."'".$_POST['nu_lista']."'".","."volta_lista="."'".$_POST['vo_lista']."'".","."dt_entrega="."'".$v_data_entrega."'".
			  ","."hr_entrega="."'".$_POST['hora_entrega']."'".","."documento="."'".$_POST['dc_recebedor']."'".","."recebedor="."'".$_POST['no_recebedor']."'".
			  ","."ocorrencia="."'".$_POST['ocorrencia']."'".","."tentativa_entrega="."'".$_POST['tenta_entrega']."'".","."dt_baixa="."'".$v_data_baixa."'".
			  ","."dt_lista_dev_origem="."'".$v_data_lista_dev."'".","."dt_dev_origem="."'".$v_data_dev_orige."'".
			  ","."lista_dev_origem="."'".$_POST['nu_lista_dev']."'".","."hora_dev_origem="."'".$_POST['hora_devo_ori']."'".","."observacao="."'".$_POST['observacao']."'".
			  ","."imagem_exportada="."'".$_POST['img_exportada']."'".","."estatus_hawb="."'".$_POST['sta_hawb']."'";
			  
              //echo "<p>Formato sql : ".$dados_up;	
              			  
			  $tabela ='movimento';	  
			  $condicao = "n_hawb ="."'".$_SESSION['nu_hawb_m']."'"; 
			 			 
			  //echo "<p>Condição :".$condicao;
			  //exit;
			  
			  // Chama a classe crud
			  require_once('CrudMysqli.php'); 
			  //Instancia a classe crud e executa a gravação do registro na tabela
			  $crudi   = new CrudMysqli('',$tabela,$dados_up,$condicao,'');
              $alterar = $crudi->Alterar();
			  $resp_grava = $alterar;
			  
			  if($alterar == '1A') {
				  $cor_msg = 'S';
			      $resp_grava = 'Alteração realizada com sucessro!';
			  }
			  if($alterar == '2A') {
				  $cor_msg = 'D';
			      $resp_grava = 'Problemas na alteração! Verifique.';
			  }
			  
			  require_once('limpa_campos_hawb.php');
			  
		break;
		case 'pega_hawb': 
		     $n_hawb = $_POST['nu_hawb'];  
			 if($n_hawb <>''){
				 
				$_SESSION['n_hawb_m'] = $n_hawb;
				
				$pega_hawb = "SELECT controle,cod_barra,escritorio,date_format(dt_rece_hawb,'%d/%m/%Y'),codi_cli,co_servico,
				estatus_hawb,codigo_desti,nome_desti,cnpj_desti,rua_desti,numero_desti,comple_desti,bairro_desti,classe_cep,
				cep_desti,cidade_desti,estado_desti,DATE_FORMAT(dt_lista_entrega,'%d/%m/%Y'),nu_lista_entrega,volta_lista,
				DATE_FORMAT(dt_entrega,'%d/%m/%Y'),hr_entrega,documento,recebedor,entregador,ocorrencia,tentativa_entrega,
				qtdade,valor,DATE_FORMAT(dt_baixa,'%d/%m/%Y'),DATE_FORMAT(dt_lista_dev_origem,'%d/%m/%Y'),
				DATE_FORMAT(dt_dev_origem,'%d/%m/%Y'),lista_dev_origem,hora_dev_origem,observacao,imagem_exportada			
				FROM movimento WHERE n_hawb='$n_hawb'";
				$query_h = mysqli_query($cone,$pega_hawb) or die ("Não foi possivel acessar a tabela MOVIMENTO");
				$total_h = mysqli_num_rows($query_h);
				If ($total_h > 0 ) {
				   for($ic=0; $ic<$total_h; $ic++){
					  $row = mysqli_fetch_row($query_h);
					   $controle        = $row[0];
					   $cod_barra      	= $row[1];
					   $escritorio   	= $row[2];
					   $data_movi    	= $row[3];
					   $codi_cli     	= $row[4];
					   $codi_servi   	= $row[5];
					   $status_pod	    = $row[6];
					   $cod_desti    	= $row[7];
					   $nome_desti   	= $row[8];
					   $cnpj_desti   	= $row[9];
					   $rua_desti    	= $row[10];	
					   $numero_desti 	= $row[11];
					   $comple_desti 	= $row[12];
					   $bairro_desti 	= $row[13];
					   $classe_cep   	= $row[14];	
					   $cep          	= $row[15];
					   $cidade_desti 	= $row[16];
					   $estado_desti 	= $row[17];
					   $dt_lista     	= $row[18];
					   $nu_lista     	= $row[19];
					   $vo_lista 		= $row[20];
					   $dt_entrega   	= $row[21];
					   $hora_entrega 	= $row[22];
					   $doc_recebe   	= $row[23];
					   $nome_recebe  	= $row[24];
					   $cod_entregador  = $row[25];
					   $cod_ocorre      = $row[26];
					   $tenta_entrega	= $row[27];
					   $qtdade       	= $row[28];
					   $valor        	= $row[29];
					   $dt_baixa     	= $row[30];
					   $dt_lista_dev    = $row[31];
					   $dt_dev_orige   	= $row[32];
					   $nu_lista_dev    = $row[33];
					   $hora_dev_orige 	= $row[34];
					   $observa    	    = $row[35];
					   $imagem_expo    	= $row[36];   
				   }
				   // Muda formato do valor para mostrar
				   $valor   = number_format($valor, 2, ',', '.');
				}else {
					$cor_msg = 'D';
			        $resp_grava = 'HAWB Ainda Não Foi Lançada No Sistema! Verifique.';
				}
			 }
		break;
		default:
	}
	//echo "Numero HAWB :".$nu_hawb;
	//exit;
	
	//Verifica se houve preenchimento do campo COD_BARRA da HWB para dessecar o numero da hawb e chamar outros dados
    if(isset($nu_hawb)){
	  if($nu_hawb <>''){
		$pega_dados = "SELECT cod_barra,escritorio,date_format(dt_rece_hawb,'%d/%m/%Y'),codi_cli,co_servico,
		estatus_hawb,codigo_desti,nome_desti,cnpj_desti,rua_desti,numero_desti,comple_desti,bairro_desti,classe_cep,
		cep_desti,cidade_desti,estado_desti,DATE_FORMAT(dt_lista_entrega,'%d/%m/%Y'),nu_lista_entrega,volta_lista,
		DATE_FORMAT(dt_entrega,'%d/%m/%Y'),hr_entrega,documento,recebedor,entregador,ocorrencia,tentativa_entrega,
		qtdade,valor,DATE_FORMAT(dt_baixa,'%d/%m/%Y'),DATE_FORMAT(dt_lote_devo_origem,'%d/%m/%Y'),
		DATE_FORMAT(dt_dev_origem,'%d/%m/%Y'),lote_devo_origem,observacao,imagem_exportada,controle			
		FROM movimento WHERE n_hawb='$nu_hawb'";
		$query_d = mysqli_query($cone,$pega_dados) or die ("Não foi possivel acessar a tabela MOVIMENTO");
		$total_d = mysqli_num_rows($query_d);
		If ($total_d > 0 ) {
		   for($ic=0; $ic<$total_d; $ic++){
			  $row = mysqli_fetch_row($query_d);
			   $cod_barra       = $row[0];
			   $escritorio   	= $row[1];
			   $data_movi    	= $row[2];
			   $codi_cli     	= $row[3];
			   $codi_servi   	= $row[4];
			   $status_pod	    = $row[5];
			   $cod_desti    	= $row[6];
			   $nome_desti   	= $row[7];
			   $cnpj_desti   	= $row[8];
			   $rua_desti    	= $row[9];	
			   $numero_desti 	= $row[10];
			   $comple_desti 	= $row[11];
			   $bairro_desti 	= $row[11];
			   $classe_cep   	= $row[13];	
			   $cep          	= $row[14];
			   $cidade_desti 	= $row[15];
			   $estado_desti 	= $row[16];
			   $dt_lista     	= $row[17];
			   $nu_lista     	= $row[18];
			   $vo_lista 		= $row[19];
			   $dt_entrega   	= $row[20];
			   $hora_entrega 	= $row[21];
			   $doc_recebe   	= $row[22];
			   $nome_recebe  	= $row[23];
			   $cod_entregador  = $row[24];
			   $cod_ocorre      = $row[25];
			   $tenta_entrega	= $row[26];
			   $qtdade       	= $row[27];
			   $valor        	= $row[28];
			   $dt_baixa     	= $row[29];
			   $dt_lista_dev    = $row[30];
			   $dt_dev_orige   	= $row[31];
			   $nu_lista_dev    = $row[32];
			   $observa    	    = $row[33];
			   $imagem_expo    	= $row[34]; 
			   $controle    	= $row[35];
		   }
		   // Muda formato do valor para mostrar
		   $valor   = number_format($valor, 2, ',', '.');
		   
		   //guarda o numero do registro para usar na hora do UPDATE.
		   $_SESSION['controle_m'] = $controle;
		}else {
			?>
		   <script language="javascript"> window.location.href=("altera_exclui_hawb.php")
			  alert('HAWB Ainda Não Foi Lançada No Sistema! Verifique.');
		   </script>
		   <?php 
		   // limpa a variavel $n_hawb e a variavel global $_SESSION['n_hawb_m'] para o sistema não ficar em looping;
		   $nu_hawb = '';
		   unset($_SESSION['nu_hawb_m']);
		}
	  }
	}
	
    ?>
	<form class="form-horizontal" id="operacao" name="operacao" method="POST" action="">
	    <div id="t_form"><?php echo $titulo_form;?></div>
		<div id="principal">
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
		    <div class="input-group" id="dados_hawb">
			    <div id="d_hawb">DADOS DA HAWB</div>
				<div class="col-md-4">
				   <div class="input-group" id="cod_barras">
						<?php if(!isset($cod_barra)) {
						   $cod_barra ='';
						}
						?>
					   <span class="input-group-addon">Cod. Barras</span>
					   <?php if($opcao == 'b') {?>
					        <input id="cod_barra" name="cod_barra" class="form-control" placeholder="" type="text" value ="<?php echo $cod_barra;?>" onChange="salva(this)">
                            <!-- Coloca foco no campo codigo de barras do formulário -->
					        <script language="JavaScript">
							    document.getElementById('cod_barra').focus()
					        </script>
                       <?php } 
					         if($opcao == 'h') {?>
                            <input id="cod_barra" name="cod_barra" class="form-control" placeholder="" type="text" value ="<?php echo $cod_barra;?>">
	                   <?php } ?>
				   </div>	
				</div>
				<div class="col-md-3" id="n_hawb">
					<?php if(!isset($nu_hawb)) {
						$nu_hawb ='';
					}
					if(isset($_SESSION['n_hawb_m'])) {
						$nu_hawb = $_SESSION['n_hawb_m'];
					}
					?>
				   <div class="input-group">
					   <span class="input-group-addon">HAWB</span>
					   <input id="nu_hawb" name="nu_hawb" class="form-control" placeholder="" type="text" value ="<?php echo $nu_hawb;?>">
				   </div>
                   <div class="col-md-2" id="b_pequisa_c_desti">
						  <button type="submit" class="btn btn-primary" id="bt_hawb" name="pega_hawb">Pesquisar</button>
				   </div>					   
				</div>     
				<div class="col-md-10" id="c_data">
					<div class="input-group">
						<?php if(!isset($data_movi)) {
						   $data_movi = '';
						}?>
						<span class="input-group-addon">Entrada</span>
						<div class="input-group date">
							<input type="text" class="form-control" id="data_movi" value="<?php echo $data_movi;?>" name="data_movi">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<script type="text/javascript">
							$('#data_movi').datepicker({	
							   format: "dd/mm/yyyy",	
							   language: "pt-BR",
							   /*startDate: '+0d',*/
							});
						</script>
					</div>	
				</div>
				<div class="input-group" id="c_escritorio">
					<?php if(!isset($escritorio)) {
						$escritorio = '';
					}?>
					<span class="input-group-addon">Escritorio</span>
					<div class="col-md-13">
						<select id="escritorio" name="escritorio" class="form-control">
						<?php
						$sql1 = "SELECT codigo,nome FROM escritorio";
						$resula = mysqli_query($cone,$sql1) or die ("Não foi possivel acessar o banco");
						while ( $linha = mysqli_fetch_array($resula)) {
							$select = $escritorio == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
						}
						?>
						</select>
					</div>
				</div>	
				<div class="input-group" id="c_cliente">
					<?php if(!isset($codi_cli)) {
						$codi_cli = '';
					}?>
					<span class="input-group-addon">Cliente</span>
					<div class="col-md-13">
						<select id="cliente" name="cliente" class="form-control" onChange="pegacli()">
							<?php
							$sql2 = "SELECT cnpj_cpf,nome FROM pessoa WHERE ativo='S' AND categoria='01'";
							$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
							while ( $linha = mysqli_fetch_array($resul)) {
								$select = $codi_cli == $linha[0] ? "selected" : "";
								echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
							}					
							?>
						</select>
					</div>
				</div>	
				<div class="input-group" id="c_servico">
					<?php if(!isset($codi_servi)) {
						$codi_servi = '';
					}?>
					<?php //echo "Serviço :".$codi_servi;?>
					<span class="input-group-addon">Serviço</span>
					<div class="col-md-13">
						<select id="servico" name="servico" class="form-control">
							<?php
							$sql3 = "SELECT DISTINCT tabela_preco.tipo_servi, servico.descri_se
							FROM tabela_preco,servico
							WHERE ((tabela_preco.tipo_servi=servico.codigo_se)
							AND (tabela_preco.ativo='S')
							AND  (tabela_preco.codi_cli='$codi_cli'))";
							$resulo = mysqli_query($cone,$sql3) or die ("Não foi possivel usar a tabela remessa.");
							while ( $linha = mysqli_fetch_array($resulo)) {
								$select = $codi_servi == $linha[0] ? "selected" : "";
								echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-3" id="qtdade_ser">
				   <div class="input-group">
					   <?php if(!isset($qtdade)) {
						$qtdade ='';
					   }
					   ?>
					   <span class="input-group-addon">QTD</span>
					   <input id="qt_servico" name="qt_servico" class="form-control" placeholder="" value="<?php echo $qtdade;?>" type="text">
				   </div>	
				</div>
				<div class="col-md-3" id="valor_ser">
				   <?php if(!isset($valor)) {
						$valor ='';
				   }
				   ?>
				   <div class="input-group">
					   <span class="input-group-addon">R$</span>
					   <input id="vr_servico" name="vr_servico" class="form-control" placeholder="" value="<?php echo $valor;?>" type="text">
				   </div>	
				</div>
				<div class="col-md-3" id="status_hawb">
				   <?php if(!isset($status_pod)) {
						$status_pod ='';
				   }
				   ?>
				   <div class="input-group">
					   <span class="input-group-addon">Status HAWB</span>
					   <input id="sta_hawb" name="sta_hawb" class="form-control" placeholder="" value="<?php echo $status_pod;?>" type="text">
				   </div>	
				</div>
          	</div>
            <div class="input-group" id="dados_destino">
			    <div id="d_hawb">DADOS DO DESTINATARIO</div>			
				<div class="col-md-4">
				   <div class="input-group" id="nome_desti">
					   <?php if(!isset($nome_desti)) {
						$nome_desti ='';
					   }
					   ?>
					   <span class="input-group-addon">Destino</span>
					   <input id="no_destino" name="no_destino" class="form-control" placeholder="" type="text" value ="<?php echo $nome_desti;?>">
					   <ul id="resultados">
		
					   </ul>	
				   </div>
				</div>
				<div id="cod_desti">
				   <div class="input-group">
					   <?php if(!isset($cod_desti)) {
						$cod_desti ='';
					   }
					   ?>
					   <span class="input-group-addon">Codigo</span>
					   <input id="cd_destino" name="cd_destino" class="form-control" placeholder="" type="text" value ="<?php echo $cod_desti;?>">
				   </div>
				   <div class="col-md-2" id="b_pequisa_c_desti">
						  <button type="button" class="btn btn-primary" onclick="pega_desti()">Pesquisar</button>
				   </div>		
			    </div>
				<div id="cnpj_desti">
				   <div class="input-group">
					   <?php if(!isset($cnpj_desti)) {
						$cnpj_desti ='';
					   }
					   ?>
					   <span class="input-group-addon">CNPJ</span>
					   <input id="cnpj_destino" name="cnpj_destino" class="form-control" placeholder="" type="text" value ="<?php echo $cnpj_desti;?>">
				   </div>	
				</div>
				<div id="div_cep">
					<div class="input-group" id="n_cep">
					   <?php if(!isset($cep)) {
						$cep ='';
					   }
					   ?>
					   <span class="input-group-addon">CEP</span>
					   <input id="cep_destino" name="cep_destino" placeholder="Apenas números" class="form-control input-md" value ="<?php echo $cep;?>" type="search" maxlength="8" pattern="[0-9]+$">
					</div>
					<div class="col-md-2" id="b_pesquisa_cep">
						<button type="button" class="btn btn-primary" onclick="pesquisacep(cep_destino.value)">Pesquisar</button>
					</div>
				</div>
				<div class="input-group" id="classe_cep">
					<span class="input-group-addon">Classe CEP</span>
					<div id="cla_cep">
						<?php if(!isset($classe_cep)) {
						$classe_cep ='';
						}
						?>
						<select id="cl_cep" name="cl_cep" class="form-control">
						<?php
						$sql2 = "SELECT classe_cep,nome_classe FROM intervalo_cep";
						$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
						while ( $linha = mysqli_fetch_array($resul)) {
							$select = $classe_cep == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
						}
						?>
						</select>
					</div>
				</div>
				<div class="input-group" id="rua_desti">
					<?php if(!isset($rua_desti)) {
					$rua_desti ='';
					}
					?>
					<span class="input-group-addon">Rua</span>
					<input id="rua" name="rua" class="form-control" placeholder="" type="text" value ="<?php echo $rua_desti;?>">
				</div>	
				<div class="input-group" id="nume_desti">
					<?php if(!isset($numero_desti)) {
					$numero_desti ='';
					}
					?>
					<span class="input-group-addon">Nº</span>
					<input id="numero" name="numero" class="form-control" placeholder="" type="text" value ="<?php echo $numero_desti;?>">
				</div>
				<div class="input-group" id="comple_desti">
					<?php if(!isset($comple_desti)) {
					$comple_desti ='';
					}
					?>
					<span class="input-group-addon">Complemento</span>
					<input id="complemento" name="complemento" class="form-control" placeholder="" type="text" value ="<?php echo $comple_desti;?>">
				</div>
				<div class="input-group" id="bair_desti">
					<?php if(!isset($bairro_desti)) {
					$bairro_desti ='';
					}
					?>
					<span class="input-group-addon">Bairro</span>
					<input id="bairro" name="bairro" class="form-control" placeholder="" type="text" value ="<?php echo $bairro_desti;?>">
				</div>
				<div class="input-group" id="cida_desti">
					<?php if(!isset($cidade_desti)) {
					$cidade_desti ='';
					}
					?>
					<span class="input-group-addon">Cidade</span>
					<input id="cidade" name="cidade" class="form-control" placeholder=""  type="text" value ="<?php echo $cidade_desti;?>">
				</div>
				<div class="input-group" id="esta_desti">
					<?php if(!isset($estado_desti)) {
					$estado_desti ='';
					}
					?>
					<span class="input-group-addon">Estado</span>
					<input id="estado" name="estado" class="form-control" placeholder=""  type="text" value ="<?php echo $estado_desti;?>">
				</div>
			</div>
			<div class="input-group" id="dados_entrega">
			    <div id="d_hawb">DADOS DA ENTREA</div>
				<div class="input-group" id="n_lista">
					<?php if(!isset($nu_lista)) {
					$nu_lista ='';
					}
					?>
					<span class="input-group-addon">Lista Entrega</span>
					<input id="nu_lista" name="nu_lista" class="form-control" placeholder="" type="text" value ="<?php echo $nu_lista;?>">
				</div>
				<div id="dt_lista">
					<div class="input-group">
						<?php if(!isset($dt_lista)) {
						   $dt_lista = '';
						}?>
						<span class="input-group-addon">Data Lista</span>
						<div class="input-group date">
							<input type="text" class="form-control" id="data_lista" value="<?php echo $dt_lista;?>" name="data_lista">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<script type="text/javascript">
							$('#data_lista').datepicker({	
							   format: "dd/mm/yyyy",	
							   language: "pt-BR",
							   /*startDate: '+0d',*/
							});
						</script>
					</div>	
				</div>			
				<div id="dt_entrega">
					<div class="input-group">
						<?php if(!isset($dt_entrega)) {
						   $dt_entrega = '';
						}?>
						<span class="input-group-addon">Data Entrega</span>
						<div class="input-group date">
							<input type="text" class="form-control" id="data_entrega" value="<?php echo $dt_entrega;?>" name="data_entrega">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<script type="text/javascript">
							$('#data_entrega').datepicker({	
							   format: "dd/mm/yyyy",	
							   language: "pt-BR",
							   /*startDate: '+0d',*/
							});
						</script>
					</div>	
				</div>
				<div class="input-group" id="hr_entrega">
					<?php if(!isset($hora_entrega)) {
					   $hora_entrega ='';
					}
					?>
					<span class="input-group-addon">H. Entrega</span>
					<input id="hora_entrega" name="hora_entrega" class="form-control" placeholder=""  type="text" value ="<?php echo $hora_entrega;?>">
				</div>
				<div class="input-group" id="no_rece">
					<?php if(!isset($nome_recebe)) {
					   $nome_recebe ='';
					}
					?>
					<span class="input-group-addon">Nome Recebedor</span>
					<input id="no_recebedor" name="no_recebedor" class="form-control" placeholder=""  type="text" value ="<?php echo $nome_recebe;?>">
				</div>
				<div class="input-group" id="dc_recebe">
					<?php if(!isset($doc_recebe)) {
					   $doc_recebe ='';
					}
					?>
					<span class="input-group-addon">Doc. Recebe</span>
					<input id="dc_recebedor" name="dc_recebedor" class="form-control" placeholder=""  type="text" value ="<?php echo $doc_recebe;?>">
				</div>
				<div class="input-group" id="c_entregador">
					<?php if(!isset($cod_entregador)) {
						$cod_entregador = '';
					}?>
					<span class="input-group-addon">Entregador</span>
					<div class="col-md-13">
						<select id="entregador" name="entregador" class="form-control">
							<?php
							$sqlf = "SELECT cnpj_cpf,nome FROM pessoa WHERE ativo ='S' AND categoria = '02' ORDER BY nome";
							$resulef = mysqli_query($cone,$sqlf) or die ("Não foi possivel usar a tabela remessa.");
							while ( $linha = mysqli_fetch_array($resulef)) {
								$select = $cod_entregador == $linha[0] ? "selected" : "";
								echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="input-group" id="c_ocorrencia">
					<?php if(!isset($cod_ocorre)) {
						$cod_ocorre = '';
					}?>
					<span class="input-group-addon">Ocorrencia</span>
					<div class="col-md-13">
						<select id="ocorrencia" name="ocorrencia" class="form-control">
							<?php
							$sqloc = "SELECT codigo,descricao FROM ocorrencia ORDER BY descricao";
							$resuloc = mysqli_query($cone,$sqloc) or die ("Não foi possivel usar a tabela remessa.");
							while ( $linha = mysqli_fetch_array($resuloc)) {
								$select = $cod_ocorre == $linha[0] ? "selected" : "";
								echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="input-group" id="t_entrega">
					<?php if(!isset($tenta_entrega)) {
					   $tenta_entrega ='';
					}
					?>
					<span class="input-group-addon">Tenta. Entrega</span>
					<input id="tenta_entrega" name="tenta_entrega" class="form-control" placeholder=""  type="text" value ="<?php echo $tenta_entrega;?>">
				</div>
				<div class="input-group" id="v_lista">
					<?php if(!isset($vo_lista)) {
					$vo_lista ='';
					}
					?>
					<span class="input-group-addon">Volta a Lista</span>
					<input id="vo_lista" name="vo_lista" class="form-control" placeholder=""  type="text" value ="<?php echo $vo_lista;?>">
				</div>
			</div>
			<div class="input-group" id="dados_baixa">
			    <div id="d_hawb">DADOS DA BAIXA / DEVOLUÇÃO ORIGEM</div>
				<div id="dt_baixa">
					<div class="input-group">
						<?php if(!isset($dt_baixa)) {
						   $dt_baixa = '';
						}?>
						<span class="input-group-addon">Data Baixa</span>
						<div class="input-group date">
							<input type="text" class="form-control" id="data_baixa" value="<?php echo $dt_baixa;?>" name="data_baixa">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<script type="text/javascript">
							$('#data_baixa').datepicker({	
							   format: "dd/mm/yyyy",	
							   language: "pt-BR",
							   /*startDate: '+0d',*/
							});
						</script>
					</div>	
				</div>
				<div class="input-group" id="n_lista_devo">
					<?php if(!isset($nu_lista_dev)) {
					$nu_lista_dev ='';
					}
					?>
					<span class="input-group-addon">Lista Dev. Origem</span>
					<input id="nu_lista_dev" name="nu_lista_dev" class="form-control" placeholder="" type="text" value ="<?php echo $nu_lista_dev;?>">
				</div>
				<div id="dt_lista_devo">
					<div class="input-group">
						<?php if(!isset($dt_lista_dev)) {
						   $dt_lista_dev = '';
						}?>
						<span class="input-group-addon">Data Lista</span>
						<div class="input-group date">
							<input type="text" class="form-control" id="data_lista_dev" value="<?php echo $dt_lista;?>" name="data_lista_dev">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<script type="text/javascript">
							$('#data_lista_dev').datepicker({	
							   format: "dd/mm/yyyy",	
							   language: "pt-BR",
							   /*startDate: '+0d',*/
							});
						</script>
					</div>	
				</div>
                <div id="dt_devo_origem">
					<div class="input-group">
						<?php if(!isset($dt_dev_orige)) {
						   $dt_dev_orige = '';
						}?>
						<span class="input-group-addon">Devolve Origem</span>
						<div class="input-group date">
							<input type="text" class="form-control" id="data_dev_orige" value="<?php echo $dt_lista;?>" name="data_dev_orige">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<script type="text/javascript">
							$('#data_dev_orige').datepicker({	
							   format: "dd/mm/yyyy",	
							   language: "pt-BR",
							   /*startDate: '+0d',*/
							});
						</script>
					</div>	
				</div>
                <div class="input-group" id="img_expo">
					<?php if(!isset($imagem_expo)) {
					   $imagem_expo ='';
					}
					?>
					<span class="input-group-addon">Img. Exp.</span>
					<input id="img_exportada" name="img_exportada" class="form-control" placeholder=""  type="text" value ="<?php echo $imagem_expo;?>">
				</div>
                <div class="input-group" id="observa">
					<?php if(!isset($observa)) {
					   $observa ='';
					}
					?>
					<span class="input-group-addon">Observação</span>
					<input id="observacao" name="observacao" class="form-control" placeholder=""  type="text" value ="<?php echo $observa;?>">
				</div>					
			</div>
			<div id="botoes" class="col-md-4">
			    <a class="btn btn-primary" href="tela_entra_cadastro_operacao.php" role="button" id="voltar" name="voltar">Voltar</a>
				<button id="excluir" name="excluir" class="btn btn-primary" type="Submit">Excluir</button>
				<button id="alterar" name="alterar" class="btn btn-primary" type="Submit">Alterar</button>
				<div id="result"></div>
			</div>
		</div>
    </form>
</body>
</html>