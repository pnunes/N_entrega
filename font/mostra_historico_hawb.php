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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

    <script src="js/bootstrap.min.js"></script> 
    <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="js/recursos.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mostra_historico_hawb.css">

</head>
<body>
    <?php 
	require_once('cabeca_paginas_internas.php'); 
	//require_once('ajax.php'); 
	
	switch (get_post_action('pega_hawb')) {
		
		case 'pega_hawb': 
		     $nu_hawb = $_POST['nu_hawb'];  
			 if($nu_hawb <>''){
				 
				$pega_hawb = "SELECT movimento.recebedor,movimento.documento,(SELECT sigla FROM escritorio WHERE codigo=movimento.escritorio),
				(SELECT nome_desti FROM destino WHERE codigo_desti=movimento.codigo_desti),(SELECT nome FROM pessoa WHERE cnpj_cpf=movimento.entregador),
				(SELECt nome FROM pessoa WHERE cnpj_cpf=movimento.codi_cli)			
				FROM movimento
				WHERE n_hawb='$nu_hawb'";
				$query_h = mysqli_query($cone,$pega_hawb) or die ("Não foi possivel acessar a tabela MOVIMENTO");
				$total_h = mysqli_num_rows($query_h);
				If ($total_h > 0 ) {
				   for($ic=0; $ic<$total_h; $ic++){
					  $row = mysqli_fetch_row($query_h);
					   $nome_recebe     = $row[0];
					   $doc_recebe     	= $row[1];
					   $sigla_escri   	= $row[2];
					   $nome_desti    	= $row[3];
					   $nome_entrega   	= $row[4];
					   $nome_cli   	    = $row[5];
				   }
				}else {
					$cor_msg = 'D';
			        $resp_grava = 'HAWB Não Registrada No Sistema! Verifique.';
				}
			 }
		break;
		default:
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
		    <div id="dados_hawb">
				<div id="n_hawb">
					<?php if(!isset($nu_hawb)) {
						$nu_hawb ='';
					}
					?>
				   <div class="input-group">
					   <span class="input-group-addon">HAWB</span>
					   <input id="nu_hawb" name="nu_hawb" class="form-control" placeholder="" type="text" value ="<?php echo $nu_hawb;?>">
				   </div>
                   <div class="col-md-2" id="b_pequisa_c_hawb">
						  <button type="submit" class="btn btn-primary" id="bt_hawb" name="pega_hawb">Pesquisar</button>
				   </div>					   
				</div>     
				<div id="c_escritorio_titu">
					Escritorio :						
				</div>
				<div id="c_escritorio_dado">
				    <?php if(!isset($sigla_escri)) {
						$sigla_escri = '';
					}?>
					<?php echo $sigla_escri;?>
				</div>
				
                <div id="c_cliente_titu">				
					Cliente :							
				</div>
				<div id="c_cliente_dado">				
					<?php if(!isset($nome_cli)) {
						$nome_cli = '';
					}?>				
					<?php echo $nome_cli;?>				
				</div>
				<div id="nome_desti_titu">
					   Destino :
				</div>
				<div id="nome_desti_dado">
				    <?php if(!isset($nome_desti)) {
					  $nome_desti ='';
				    }
				    ?>
				    <?php echo $nome_desti;?>
				</div>
				<div id="no_rece_titu">							
					 Recebedor :
				</div>
				<div id="no_rece_dado">		
					<?php if(!isset($nome_recebe)) {
					   $nome_recebe ='';
					}
					?>
					<?php echo $nome_recebe;?>
				</div>
				<div id="dc_recebe_titu">
					Documento :
				</div>
				<div id="dc_recebe_dado">
					<?php if(!isset($doc_recebe)) {
					   $doc_recebe ='';
					}
					?>
					<?php echo $doc_recebe;?>
				</div>
				<div id="c_entregador_titu">
					Entregador :
				</div>
				<div id="c_entregador_dado">
					<?php if(!isset($nome_entrega)) {
						$nome_entrega = '';
					}?>
					<?php echo $nome_entrega;?>
				</div>
			</div>
			<table id="tab_historico">
				<thead
				  <tr><th colspan="2" style="text-align:center; color:#ffffff; height:25px; background-color:#4682B4;border: 1px solid #ffffff;">DETALHES HISTORICO HAWB</th></tr>
				  <tr>
					 <th style="text-align:center; color:#ffffff; background-color:#4682B4;border:1px solid #ffffff; height:25px;">DATA</th>
					 <th style="text-align:center; color:#ffffff; background-color:#4682B4;border:1px solid #ffffff; height:25px;">EVENTO</th>
				  </tr>
			   </thead>
			   <tbody>
			      <?php
			      if($nu_hawb <> '') {
					  $pega_detalhe_hawb = "SELECT DATE_FORMAT(historico_hawb.dt_evento,'%d/%m/%Y'),
					  (SELECT descricao FROM ocorrencia WHERE codigo=historico_hawb.ocorrencia)
					  FROM historico_hawb
					  WHERE historico_hawb.n_hawb = '$nu_hawb' 
					  ORDER BY historico_hawb.dt_evento";
					  $histo_hawb = mysqli_query($cone,$pega_detalhe_hawb) or die ("Não foi possivel acessar a tabela MOVIMENTO");
				      $total_histo = mysqli_num_rows($histo_hawb);
					  If ($total_histo > 0 ) {
						   for($i=0; $i < $total_histo; $i++){
							  $row = mysqli_fetch_row($histo_hawb);
							  ?>
							  <tr>
							     <td><?php echo $row[0]; ?>  </td>
							     <td><?php echo $row[1]; ?>  </td>
							  </tr>
							  <?php
						   }
                      }
			      }
			      ?>
			   </tbody>
			</table>
			<div id="botoes">
				<a class="btn btn-primary" href="entrada.php" role="button" id="voltar" name="voltar">Voltar</a>
			</div>
		</div>
    </form>
</body>
</html>