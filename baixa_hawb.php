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
    <link rel="stylesheet" type="text/css" href="css/baixa_hawb.css">

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
	
	switch (get_post_action('baixar','pega_hawb')) {

		case 'baixar':
		      //Alterando formato das datas para gravar na tabela movimento
		      
			  $dt_baixa         = $_POST['data_baixa'];
			  $data_baixa       = explode("/",$dt_baixa);
			  $v_data_baixa     = $data_baixa[2]."-".$data_baixa[1]."-".$data_baixa[0];
			  
			  $dt_entrega       = $_POST['data_entrega'];
			  $data_entrega     = explode("/",$dt_entrega);
			  $v_data_entrega   = $data_entrega[2]."-".$data_entrega[1]."-".$data_entrega[0];
			  
			  $estatus = 'BIP3';
			  $tabela ='movimento';	  
		      $campos ='';
			  
			  //Montando os dados para enviar para o crudMysql para autalização da tabela movimento
			  $dados_up = "dt_entrega="."'".$v_data_entrega."'".","."hr_entrega="."'".$_POST['hora_entrega']."'".
			  ","."documento="."'".$_POST['dc_recebedor']."'".","."recebedor="."'".$_POST['no_recebedor']."'".
			  ","."ocorrencia="."'".$_POST['ocorrencia']."'".","."dt_baixa="."'".$v_data_baixa."'".
			  ","."observacao="."'".$_POST['observacao']."'".","."status_hawb="."'".$estatus."'";
			  
              // echo "<p>Formato sql : ".$dados_up;			  
			  $tabela ='movimento';	  
		      $campos ='';
			  $condicao = "controle ="."'".$_SESSION['controle_m']."'";
		      
			  $parametro = '';
			  // Chama a classe crud
			  require_once('CrudMysqli.php'); 
			  //Instancia a classe crud e executa a gravação do registro na tabela
			  $crudi   = new CrudMysqli($campos,$tabela,$dados_up,$condicao,$parametro);
              $alterar = $crudi->Alterar();
			  $resp_grava = $alterar;
			  
			  // ativa a opção de pesquisar pelo codigo de barra
			  $opcao ='b';
			  ?>
			  <script language="JavaScript">
                   document.getElementById('cod_barra').focus()
              </script>
			  <?php
		break;
		case 'pega_hawb': 
		     $n_hawb = $_POST['nu_hawb'];  
			 if($n_hawb <>''){
				 
				$_SESSION['n_hawb_m'] = $n_hawb;
				
				$pega_hawb = "SELECT controle,cod_barra,DATE_FORMAT(dt_entrega,'%d/%m/%Y'),hr_entrega,documento,
				recebedor,entregador,ocorrencia,DATE_FORMAT(dt_baixa,'%d/%m/%Y'),observacao			
				FROM movimento WHERE n_hawb='$n_hawb' AND nu_lista_entrega<>'' AND dt_baixa='0000-00-00'";
				$query_h = mysqli_query($cone,$pega_hawb) or die ("Não foi possivel acessar a tabela MOVIMENTO");
				$total_h = mysqli_num_rows($query_h);
				If ($total_h > 0 ) {
				   for($ic=0; $ic<$total_h; $ic++){
					  $row = mysqli_fetch_row($query_h);
					   $controle        = $row[0];
					   $cod_barra      	= $row[1];
					   $dt_entrega   	= $row[2];
					   $hora_entrega 	= $row[3];
					   $doc_recebe   	= $row[4];
					   $no_recebedor  	= $row[5];
					   $cod_entregador  = $row[6];
					   $cod_ocorre      = $row[7];
					   $dt_baixa     	= $row[8];
					   $observa    	    = $row[9];   
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
				}
				$opcao ='h';
			 }
		break;
		default:
	}
	
	//Verifica se houve preenchimento do campo COD_BARRA para pegar os dados da mesma
	if($opcao == 'b'){
	  if(isset($_POST['cod_barra'])){
		$cod_barra = $_POST['cod_barra'];
		if($cod_barra <>''){
			$pega_dados = "SELECT controle,n_hawb,DATE_FORMAT(dt_entrega,'%d/%m/%Y'),hr_entrega,documento,
				recebedor,entregador,ocorrencia,DATE_FORMAT(dt_baixa,'%d/%m/%Y'),observacao			
				FROM movimento WHERE cod_barra='$cod_barra' AND nu_lista_entrega<>'' AND dt_baixa='0000-00-00'";
			$query_d = mysqli_query($cone,$pega_dados) or die ("Não foi possivel acessar a tabela MOVIMENTO");
			$total_d = mysqli_num_rows($query_d);
			If ($total_d > 0 ) {
			   for($ic=0; $ic<$total_d; $ic++){
			      $row = mysqli_fetch_row($query_d);
				   $controle        = $row[0];
                   $nu_hawb      	= $row[1];
				   $dt_entrega   	= $row[2];
				   $hora_entrega	= $row[3];
				   $doc_recebe     	= $row[4];
				   $no_recebedor   	= $row[5];
				   $cod_entregador	= $row[6];
				   $cod_ocorre      = $row[7];
				   $dt_baixa	    = $row[8];
                   $observa    	    = $row[9];    			   
			   }
			   
			   //guarda o numero do registro para usar na hora do UPDATE.
			   $_SESSION['controle_m'] = $controle;
			}else {
				?>
			   <script language="javascript"> window.location.href=("altera_exclui_hawb.php")
				  alert('HAWB Ainda Não Foi Lançada No Sistema! Verifique.');
			   </script>
			   <?php   
			}
		}
	  }
	}else {
	    $opcao = 'b';	
	}
    ?>
	<form class="form-horizontal" id="operacao" name="operacao" method="POST" action="">
	    <div id="t_form"><?php echo $titulo_form;?></div>
		<div id="principal">
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
                   <div class="col-md-2" id="bt_pequisa_hawb">
						<button type="submit" class="btn btn-primary" id="bt_hawb" name="pega_hawb">Pesquisar</button>
				   </div>					   
				</div>
				<div class="input-group" id="c_entregador">
					<?php if(!isset($cod_entregador)) {
						$cod_entregador = '';
					}?>
					<span class="input-group-addon">Entregador</span>
					<div class="col-md-13">
						<select id="entregador" name="entregador" class="form-control">
							<?php
							$sqlf = "SELECT cnpj_cpf,nome FROM cli_for WHERE ativo ='S' AND catego = 'F' ORDER BY nome";
							$resulef = mysqli_query($cone,$sqlf) or die ("Não foi possivel usar a tabela remessa.");
							while ( $linha = mysqli_fetch_array($resulef)) {
								$select = $cod_entregador == $linha[0] ? "selected" : "";
								echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="input-group" id="dados_entrega">
			    <div id="d_hawb">DADOS DA ENTREA</div>
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
			    <a class="btn btn-primary" href="entrada.php" role="button" id="voltar" name="voltar">Voltar</a>
				<div id="mensagem">
					<font face="arial" size="3" color="#ffffff"><?php echo "$resp_grava";?></font>
				</div>
				<button id="baixa" name="baixar" class="btn btn-primary" type="Submit">Baixar</button>
				<div id="result"></div>
			</div>
		</div>
    </form>
</body>
</html>