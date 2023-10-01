<?php
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	
	//definindo o padrão da hora
	date_default_timezone_set('America/Sao_Paulo');
	
	require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');

    // criando variaveis de resposta
	if(!isset($resp_grava)){
	   $resp_grava = '';	
    }
	
	if(!isset($resp_grava_d)){
		$resp_grava_d ='';
	}
	
	if(!isset($resp_grava_h)){
		$resp_grava_h ='';
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
	if(!isset($opcao_css)) {
		$opcao_css ='';
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
	<!-- Recursos para o autocomplete - campo nome destino  -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

    <script src="js/bootstrap.min.js"></script> 
    <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/recursos.js"></script>
    <link rel="stylesheet" type="text/css" href="css/inclusao_hawb_horizontal.css">

	<!--recurso indispensavel para funcionar os selects relacionados -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	
</head>
<body onload="carregaSelect()">
    <?php 
	require_once('cabeca_paginas_internas.php'); 
	require_once('ajax.php'); 
	
	//Verifica se houve preenchimento do campo COD_BARRA para dessecar o numero da hawb e chamar outros dados
	if(isset($_POST['cod_barra'])){
		$cod_barra = $_POST['cod_barra'];
		//$_SESSION['cod_barra_m'] = $_POST['cod_barra'];
		
		if($cod_barra <>''){
			$localiza = "SELECT cod_barra FROM movimento WHERE cod_barra='$cod_barra'";
			$query = mysqli_query($cone,$localiza) or die ("Não foi possivel acessar o banco 1");
			$achou = mysqli_num_rows($query);
			If ($achou > 0 ) {
				$cor_msg = 'D';
				$resp_grava = 'HAWB já foi lançada. Verifique.';
				$cod_barra ='';
			}else {
				if(strlen($cod_barra) <= 26){
				   $cod_desti    =Substr($cod_barra,0,8);
				   $cod_desti    =intval($cod_desti);
				   $nu_hawb      =Substr($cod_barra,17,10);
				   $nu_hawb      =intval($nu_hawb);
				   
				   //tratando o codigo do destino para tirar zeros da frente.
				   
				   //Salva o numero da hawb numa variavel global
				   $_SESSION['n_hawb_m']     =$nu_hawb;
				  
				   //Pega os dados do destino a parir do código extraido do codigo de barras
				   $verifi="SELECT nome_desti,cep_desti,rua_desti,numero_desti,
				   comple_desti,bairro_desti,cidade_desti,estado_desti,classe_cep,cnpj_desti
				   FROM destino WHERE codigo_desti='$cod_desti'";
				   $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco");
				   $total = mysqli_num_rows($query);
                   if($total > 0) {
					   for($ic=0; $ic<$total; $ic++){
						  $mostra = mysqli_fetch_row($query);
						  $nome_desti     = $mostra[0];
						  $cep            = $mostra[1];
						  $rua_desti      = $mostra[2];
						  $numero_desti   = $mostra[3];
						  $comple_desti   = $mostra[4];
						  $bairro_desti   = $mostra[5];
						  $cidade_desti   = $mostra[6];
						  $estado_desti   = $mostra[7];
						  $classe_cep     = $mostra[8];
						  $cnpj_desti     = $mostra[9];
					   }
					   $grava_desti ='N';
					   $_SESSION['grava_desti_m'] = 'N';
				   }else {
					   $grava_desti ='S';
                       $_SESSION['grava_desti_m'] = 'S';					   
				   }
				   $qtdade = 1;
				}else {
					$cor_msg = 'D';
					$resp_grava = 'Código de barra esta fora do padrão (26 caravteres)! verifique.';
					$cod_barra ='';
				}
			}
		}
	}
	
	//verifica a tecla precionada e executa a operação correspondente.
	switch (get_post_action('fixa_cabe','gravar')) {
		case 'fixa_cabe':
		
		     // trata data movimento
		     if(isset($_POST['data_movi'])){
				$data_movi    = $_POST['data_movi'];
				$_SESSION['data_movi_m'] = $_POST['data_movi'];
			 }else {
				$data_movi    = $_SESSION['data_movi_m'];
			 }
			 
			 // trata data de previsao deentrega
             if(isset($_POST['data_pren'])){
				$data_pren    = $_POST['data_pren'];
				$_SESSION['data_pren_m'] = $_POST['data_pren'];
			 }else {
				$data_pren    = $_SESSION['data_pren_m'];
			 }
			 
			 // trata codigo escritorio
             if(isset($_POST['escritorio'])){
				$escritorio   = $_POST['escritorio'];
				$_SESSION['escritorio_m'] = $_POST['escritorio'];
			 }else {
				$escritorio   = $_SESSION['escritorio_m'];
			 } 
			 
			 // trata codigo do cliente selecionado
             if(isset($_POST['cliente'])){
				$codi_cli     = $_POST['cliente'];
				$_SESSION['codi_cli_m'] = $_POST['cliente'];
			 }else {
				$codi_cli     = $_SESSION['codi_cli_m'];
			 } 
			 
			 // trata codigo do serviço
             if(isset($_POST['servico'])){
				$codi_servi   = $_POST['servico'];
				$_SESSION['codi_servi_m'] = $codi_servi;
			 }else {
				$codi_servi   = $_SESSION['codi_servi_m'];
			 } 
			// var_dump("Data :".$data_movi. " - Data Prev:".$data_pren." - Escri :".$escritorio." - Cliente :".$codi_cli." - Serviço :".$codi_servi);
			 
		break;
		case 'gravar':
			  $cod_barra = $_POST['cod_barra'];
		      if($cod_barra <> '') {
				  $dt_movi     = $_POST['data_movi'];
				  $data_movi   = explode("/",$dt_movi);
				  $v_data_movi = $data_movi[2]."-".$data_movi[1]."-".$data_movi[0];
				  
				  $dt_preven   = $_POST['data_pren'];
				  $data_preve  = explode("/",$dt_preven);
				  $v_data_preve = $data_preve[2]."-".$data_preve[1]."-".$data_preve[0];
				  
				  $qtdade      = $_POST['qt_servico'];
				  if($qtdade == '') {
					 $qtdade =1;  
				  }
				  
				  $valor_ser = $_POST['vr_servico'];
				  if($valor_ser == '') {
					 $valor_ser =0.00;  
				  }
				  $ocorrencia = '01';
				  ////////////////////////////////////////////////////////TABELA MOVIMENTO///////////////////////////////////////////////////////
				  $tabela ='movimento';
				  $campos ='escritorio,dt_rece_hawb,codi_cli,co_servico,cod_barra,
				  n_hawb,estatus_hawb,qtdade,valor,codigo_desti,nome_desti,cnpj_desti,rua_desti,
				  numero_desti,comple_desti,bairro_desti,classe_cep,cep_desti,cidade_desti,estado_desti,data_previ_entrega,ocorrencia';
				  
				  $conteudo_post = "'".$_POST['escritorio']."'".","."'".$v_data_movi."'".","."'".$_POST['cliente']."'".","."'".$_SESSION['codi_servi_m']."'".","."'".
				  $_POST['cod_barra']."'".","."'".$_POST['nu_hawb']."'".","."'".'BIP-1'."'".","."'".$qtdade."'".","."'".$valor_ser."'".","."'".
				  $_POST['cd_destino']."'".","."'".$_POST['no_destino']."'".","."'".$_POST['cnpj_destino']."'".","."'".$_POST['rua']."'".","."'".
				  $_POST['numero']."'".","."'".$_POST['complemento']."'".","."'".$_POST['bairro']."'".","."'".$_POST['cl_cep']."'".","."'".
				  $_POST['cep_destino']."'".","."'".$_POST['cidade']."'".","."'".$_POST['estado']."'".","."'".$v_data_preve."'".","."'".$ocorrencia."'";

				  // Chama a classe crud
				  require_once('CrudMysqli.php'); 
				  //Instancia a classe crud e executa a gravação do registro na tabela movimento
				  $crudi   = new CrudMysqli($campos,$tabela,$conteudo_post,'','');
				  $inclui_movime = $crudi->Inserir();
				  if($inclui_movime == '1I') {
						 $cor_msg = 'S';
						 $resp_grava_h = 'HAWB incluida com sucesso!';
				  }
				  if($inclui_movime == '2I') {
						 $cor_msg = 'D';
						 $resp_grava_h = 'Problemas na inclusão da HAWB!';
				  }
				  
				  /////////////////////////////////////////////////////////TABELA HISTORICO HAWB////////////////////////////////////////////////////
				  
				  $usuario = $_SESSION['cpf_m'];
				  $ocorrencia ='01';
				  $hora = date('H:i:s');
				  
				  $tabela_h ='historico_hawb';
				  $campos_h ='n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro';
				  $historico_post = "'".$_POST['nu_hawb']."'".","."'".$_POST['cod_barra']."'".","."'".$ocorrencia."'".","."'".$v_data_movi."'".","."'".
				  $usuario."'".","."'".$hora."'";

				  // Chama a classe crud
				  require_once('CrudMysqli.php'); 
				  //Instancia a classe crud e executa a gravação do registro na tabela movimento
				  $crudi_h   = new CrudMysqli($campos_h,$tabela_h,$historico_post,'','');
				  $inclui_historico = $crudi_h->Inserir();
				  
                  ////////////////////////////////////////////////////TABELA DESTINOS//////////////////////////////////////////////////////////
				  //Caso os dados do destino não exista na tabela destino, comanda a inserção do destino na tabela
				  $grava_desti = $_SESSION['grava_desti_m'];
				
				  if($grava_desti == 'S') {
					 if($_POST['cd_destino'] == ''){
						 $codigo_desti = $_POST['cep_destino'].$_POST['numero'];
					 }else {
						 $codigo_desti = $_POST['cd_destino'];
					 }
					 
					 $tabela_des ='destino';
					 //Monta a variavel com os campos para passar para a classe crud
					 $campos_des = 'codigo_desti,nome_desti,cnpj_desti,rua_desti,numero_desti,
					 comple_desti,bairro_desti,classe_cep,cep_desti,cidade_desti,estado_desti,data_atu_cada'; 
					 
					 //Monta a variavel comos posts para passar para a variavel crud
					 $post_desti = "'".$codigo_desti."'".","."'".$_POST['no_destino']."'".","."'".$_POST['cnpj_destino']."'".","."'".$_POST['rua']."'".","."'".
					 $_POST['numero']."'".","."'".$_POST['complemento']."'".","."'".$_POST['bairro']."'".","."'".$_POST['cl_cep']."'".","."'".
					 $_POST['cep_destino']."'".","."'".$_POST['cidade']."'".","."'".$_POST['estado']."'".","."'".$v_data_movi."'";
					 
					 // Chama a classe crud
					 require_once('CrudMysqli.php'); 
					 //Instancia a classe crud e executa a gravação do registro na tabela
					 $crudes   = new CrudMysqli($campos_des,$tabela_des,$post_desti,'','');
					 $inserir  = $crudes->Inserir();
					 if($inserir == '1I') {
						 $cor_msg = 'S';
						 $resp_grava_d = 'Destnino atualizado com sucesso!';
					 }
					 if($inserir == '2I') {
						 $cor_msg = 'D';
						 $resp_grava_d = 'Problemas na atualização do destino!';
					 }
				  }else {
					  //Atualiza os dados do destino  
					  $tabela_des ='destino';
					  
					  //Monta a variavel com os campos para passar para a classe crud
					 // $campos_des = 'nome_desti,cnpj_desti,rua_desti,numero_desti,
					 // comple_desti,bairro_desti,classe_cep,cep_desti,cidade_desti,estado_desti'; 
					 
					  //Monta a variavel comos posts para passar para a variavel crud
					  $dados_desti = "nome_desti="."'".$_POST['no_destino']."'".","."cnpj_desti="."'".$_POST['cnpj_destino']."'".",".
					  "rua_desti="."'".$_POST['rua']."'".","."numero_desti="."'".$_POST['numero']."'".","."comple_desti="."'".
					  $_POST['complemento']."'".","."bairro_desti="."'".$_POST['bairro']."'".","."cidade_desti="."'".$_POST['cidade']."'".",".
					  "classe_cep="."'".$_POST['cl_cep']."'".","."estado_desti="."'".$_POST['estado']."'".",".
					  "data_atu_cada="."'".$v_data_movi."'".","."cep_desti="."'".$_POST['cep_destino']."'";
					  
					  $condicao = "codigo_desti ="."'".$_POST['cd_destino']."'";

					  // Chama a classe crud
					  require_once('CrudMysqli.php'); 
					  //Instancia a classe crud e executa a gravação do registro na tabela
					  $crudes   = new CrudMysqli('',$tabela_des,$dados_desti,$condicao,'');
					  $alterar  = $crudes->Alterar();
					  if($alterar == '1A') {
						 $cor_msg = 'S';
						 $resp_grava_d = 'Destnino atualizado com sucesso!';
					  }
					  if($alterar == '2A') {
						 $cor_msg = 'D';
						 $resp_grava_d = 'Problemas na atualização do destino!';
					  }  
				  }
				  $resp_grava = $resp_grava_h.' - '.$resp_grava_d;
				  
				  //Limpa as variveis para carreglas com novo conteudo digitado
				  $cod_barra    = '';
				  $nu_hawb      = '';
				  $qtdade       = '';
				  $nome_desti   = '';
				  $cep          = '';
				  $rua_desti    = '';
				  $numero_desti = '';
				  $comple_desti = '';
				  $bairro_desti = '';
				  $cidade_desti = '';
				  $estado_desti = '';
				  $classe_cep   = '';
				  $cnpj_desti   = '';
				  unset($_SESSION['cod_barra_m']);
		      }else {
			      $cor_msg = 'D';
			      $resp_grava = 'Campo codigo de barra não foi informado! Verifique.';
			      $cod_barra ='';
				  unset($_POST['cod_barra']);
				  unset($_SESSION['cod_barra_m']);
		      }
			  ?>
			  <script language="JavaScript">
				   document.getElementById('cod_barra').focus()
			  </script>
			  <?php
		break;
		default:
	}
	
    ?>
	<form class="form-horizontal" id="operacao" name="operacao" method="POST" action="inclusao_hawb_h.php">
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
			<div class="form-group" id="g_fixo">
				<div class="form-group">
					<div class="col-md-7" id="c_data">
						<div class="input-group">
						<?php if(isset($_SESSION['data_movi_m'])) {
							 $data_movi = $_SESSION['data_movi_m'];
						} else {
							$data_movi = date('d/m/Y');
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
					<div class="col-md-7" id="data_prev">
						<div class="input-group">
						<?php if(isset($_SESSION['data_pren_m'])) {
							 $data_pren = $_SESSION['data_pren_m'];
						} else {
							$data_pren = '';
						}?>
						<span class="input-group-addon">Prev. Entrega</span>
						<div class="input-group date">
								<input type="text" class="form-control" id="data_pren" value="<?php echo $data_pren;?>" name="data_pren">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<script type="text/javascript">
								$('#data_pren').datepicker({	
								format: "dd/mm/yyyy",	
								language: "pt-BR",
								/*startDate: '+0d',*/
							});
						</script>
						</div>	
					</div>
					<!--&nbsp&nbsp&nbsp&nbsp-->
					<div class="input-group" id="c_cliente">
						<?php if(isset($_SESSION['codi_cli_m'])) {
							$codi_cli = $_SESSION['codi_cli_m'];
						} else {
							$codi_cli ='';
						}?>
						<span class="input-group-addon">Cliente</span>
						<div class="col-md-13">
							<select id="cliente" name="cliente" class="form-control">
							    <option value="">Selecione o Cliente</option>
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
				</div>
				<div class="form-group">	  
					<div class="input-group" id="c_servico">
					    <?php if(isset($_SESSION['codi_servi_m'])) {
							$codi_servi = $_SESSION['codi_servi_m'];
						} else {
							$codi_servi ='';
						}?>
						<span class="input-group-addon">Serviço</span>
						<div class="col-md-13">
							<select id="servico" name="servico" class="form-control" onchange='selecionaItem(this)'>
							    <option value="">Selecione o Serviço</option>
							</select>
						</div>
					</div>
					<div class="input-group" id="c_escritorio">
							<?php if(isset($_SESSION['escritorio_m'])) {
								$escritorio = $_SESSION['escritorio_m'];
							} else {
								$escritorio ='';
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
				</div>
			</div>
			<div class="form-group" id="cab_fixo">
				<div class="col-md-2" id="fixa_cabecalho">
					<button type="Submit" id="fixa_cabe" name="fixa_cabe" class="btn btn-primary" onsubmit="carregaSelect()">Fixa Cabeçalho Remessa</button>
				</div>
			</div>			
            <div class="form-group">
				<div class="col-md-4">
				   <div class="input-group" id="cod_barras">
				        <?php if(!isset($cod_barra)) {
                           $cod_barra ='';
					    }
						?>
					   <span class="input-group-addon">Cod. Barras</span>
					   <input id="cod_barra" name="cod_barra" class="form-control" placeholder="" type="text" value ="<?php echo $cod_barra;?>" onChange="salva(this)">
					   <script language="JavaScript">
                             document.getElementById('cod_barra').focus()
                       </script>
				   </div>	
				</div>
				<div class="col-md-3" id="n_hawb">
				    <?php if(!isset($nu_hawb)) {
                        $nu_hawb ='';
				    }
					?>
				   <div class="input-group">
					   <span class="input-group-addon">HAWB</span>
					   <input id="nu_hawb" name="nu_hawb" class="form-control" placeholder="" type="text" value ="<?php echo $nu_hawb;?>">
				   </div>	
				</div>
				<div class="col-md-3" id="qtdade_ser">
				   <div class="input-group">
				       <?php if(!isset($qtdade)) {
                        $qtdade ='';
				       }
					   ?>
					   <span class="input-group-addon">QTD</span>
					   <input id="qt_servico" name="qt_servico" class="form-control" placeholder="" type="text">
				   </div>	
				</div>
				<div class="col-md-3" id="valor_ser">
				   <div class="input-group">
					   <span class="input-group-addon">R$</span>
					   <input id="vr_servico" name="vr_servico" class="form-control" placeholder="" type="text">
				   </div>	
				</div>
			</div>
			<div class="form-group">		  
			    <div class="col-md-4">
				   <div class="input-group" id="nome_desti">
				       <?php if(!isset($nome_desti)) {
                        $nome_desti ='';
					   }
					   ?>
					   <span class="input-group-addon">Destino</span>
					   <input id="no_destino" name="no_destino" class="form-control" placeholder="" type="text" value ="<?php echo $nome_desti;?>">
					   <!--<div id="resu_lista">-->
					      <ul id="resultados">
		
		                  </ul>
                       <!--</div>-->						  
				   </div>
				</div>
				<div class="col-md-3" id="cod_desti">
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
			</div>
			<div class="form-group">
				<div class="col-md-3" id="cnpj_desti">
				   <div class="input-group">
				       <?php if(!isset($cnpj_desti)) {
                        $cnpj_desti ='';
					   }
					   ?>
					   <span class="input-group-addon">CNPJ</span>
					   <input id="cnpj_destino" name="cnpj_destino" class="form-control" placeholder="" type="text">
				   </div>	
				</div>
				<div class="col-md-4" id="div_cep">
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
					<div class="col-md-2" id="cla_cep">
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
			</div>
			<div class="form-group">
				<div class="col-md-4">
					<div class="input-group" id="rua_desti">
					    <?php if(!isset($rua_desti)) {
                        $rua_desti ='';
						}
					    ?>
						<span class="input-group-addon">Rua</span>
						<input id="rua" name="rua" class="form-control" placeholder="" type="text" value ="<?php echo $rua_desti;?>">
					</div>	
				</div>
				<div class="col-md-2" id="nume_desti">
					<div class="input-group">
					    <?php if(!isset($numero_desti)) {
                        $numero_desti ='';
						}
					    ?>
						<span class="input-group-addon">Nº</span>
						<input id="numero" name="numero" class="form-control" placeholder="" type="text" value ="<?php echo $numero_desti;?>">
					</div>
				</div> 
				<div class="col-md-3">
					<div class="input-group" id="comple_desti">
					    <?php if(!isset($comple_desti)) {
                        $comple_desti ='';
						}
					    ?>
						<span class="input-group-addon">Complemento</span>
						<input id="complemento" name="complemento" class="form-control" placeholder="" type="text" value ="<?php echo $comple_desti;?>">
					</div>
				</div>
			</div>
			<div class="form-group">
			    <div class="col-md-3">
					<div class="input-group" id="bair_desti">
					    <?php if(!isset($bairro_desti)) {
                        $bairro_desti ='';
						}
					    ?>
						<span class="input-group-addon">Bairro</span>
						<input id="bairro" name="bairro" class="form-control" placeholder="" type="text" value ="<?php echo $bairro_desti;?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="input-group" id="cida_desti">
					    <?php if(!isset($cidade_desti)) {
                        $cidade_desti ='';
						}
					    ?>
						<span class="input-group-addon">Cidade</span>
						<input id="cidade" name="cidade" class="form-control" placeholder=""  type="text" value ="<?php echo $cidade_desti;?>">
					</div>
				</div>
				<div class="col-md-2" id="esta_desti">
					<div class="input-group">
					    <?php if(!isset($estado_desti)) {
                        $estado_desti ='';
						}
					    ?>
						<span class="input-group-addon">Estado</span>
						<input id="estado" name="estado" class="form-control" placeholder=""  type="text" value ="<?php echo $estado_desti;?>">
					</div>
				</div>
			</div>			  
			<div class="form-group" id="rodape">
				<button id="btn_gravar" name="gravar" class="btn btn-primary" type="Submit">Gravar</button>
				<a href="tela_entra_cadastro_operacao.php"><button id="btn_voltar" name="voltar" class="btn btn-primary" type="button">Voltar</button></a>
			</div>
		</div>
    </form>
	<script type="text/javascript">
	    //Função submete o form após informar o codigo de barras
	    function salva(campo){
           operacao.submit();
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
				//var $cod_desti     = $("input[name='cd_destino']");
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
					//$cod_desti.val( json.cod_desti );
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

        // Função para verificar se a hawb digitada existe ou não
	    $(function(){
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
				)}
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
			
			$.getJSON('procura_destino_cod.php',{ 
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
	   
	   //Função que relaciona o select cliente com o tipo de serviço
       $(function(){
			$('#cliente').change(function(){
				cliente = $(this).val();
				localStorage.setItem('cliente',cliente);
				if( $(this).val() ) {
					$.getJSON('procura_servico.php?search=',{cliente: $(this).val(), ajax: 'true'}, function(j){
						var options = '<option value="">Escolha Serviço</option>';	
						for (var i = 0; i < j.length; i++) {
							options += '<option value="' + j[i].tipo_servi + '">' + j[i].descri_se + '</option>';
						}
						$('#servico').html(options).show();
					});
				} else {
					$('#servico').html('<option value="">– Informe um nome válido –</option>');		
				}
			});
		});
		
	    function selecionaItem(elem){
           indice = elem.selectedIndex;
           vitem = elem.options[indice];
		   descri = vitem.innerHTML;
		   localStorage.setItem('item',vitem.value);
        };
		
		function carregaSelect() {	
			valor = localStorage.getItem('item');
			cliente   = localStorage.getItem('cliente');
			
			$.getJSON('procura_servico.php?search=',{cliente: cliente, ajax: 'true'}, function(j){
				var options = '<option value="">Escolha Serviço</option>';	
				for (var i = 0; i < j.length; i++) {
					if(j[i].tipo_servi === valor){
					   options += '<option value="' + j[i].tipo_servi + '" selected>' + j[i].descri_se + '</option>';
					}else{
					   options += '<option value="' + j[i].tipo_servi + '">' + j[i].descri_se + '</option>';
					}
				}
				$('#servico').html(options).show();
			});
		}
	</script>
</body>
</html>