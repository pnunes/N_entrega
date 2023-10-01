<?php 
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	
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
    
	if(isset($_GET['modulo'])){
		$modulo = $_GET['modulo'];
	}else {
		if(isset($_SESSION['modulo_m'])){
		   $modulo = $_SESSION['modulo_m'];
		}else {
		   $modulo ='';
		}
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
<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<!-- Recursos para o autocomplete - campo nome destino  e para campo data bootstrap-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    	
	<link rel="stylesheet" type="text/css" href="css/estilo_imprime_lista_entrega.css">
	<?php
	
	?>
  </head>
  <body>
   <?php
      
       // estancia c classe de conexao com o banco
       require_once('Conexao.php');
       $conn = new Conexao();
       $cone = $conn->Conecta();
	   mysqli_set_charset($cone,'UTF8');
	   
	   //Pega o nome do USUARIO da variavel global, que é mostrado no cabeçalho da pagina
	   $nome_usu  = $_SESSION['nome_m'];
	   
	   //Chama a rotina que monta o cabeçalho da pagina
       require_once('cabeca_paginas_internas.php'); 

       switch (get_post_action('sele_lista')) {
		  case 'sele_lista':
              
		       $nu_lista_sel = $_POST['nu_lista_sel']; 
			   $_SESSION['nu_lista_sel'] = $_POST['nu_lista_sel'];
			   
               //Pega nome entregador
               $verifi="SELECT DISTINCT movimento.entregador,pessoa.nome 
			   FROM movimento,pessoa 
			   WHERE movimento.entregador=pessoa.cnpj_cpf
			   AND movimento.nu_lista_entrega='$nu_lista_sel'";
			   
               $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar a tabela movimento!");
               $total = mysqli_num_rows($query);
               for($ic=0; $ic<$total; $ic++){
                  $mostra = mysqli_fetch_row($query);
                  $entregador        = $mostra[0];
				  $nome_entregador   = $mostra[1];
               }
               
			   $nome_arquivo   = 'lista_entrega.pdf';		   
               $nome_relatorio = 'LISTA DE ENTREGA :'.$nu_lista_sel.' - Entregador :'.$nome_entregador; 		  
			   $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,NUM,BAIRRO,CIDADE';
			   $campos_tabela  = 'n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti';
			   $tama_campo     = '40,210,210,40,150,150';
               $tabela         = 'movimento';	  
			   $condicao       = 'nu_lista_entrega ='."'".$nu_lista_sel."'";
			   
			   //var_dump($nome_relatorio.'-'.$cabe_relatorio.'-'.$campos_tabela.'-'.$tama_campo.'-'.$tabela.'-'.$condicao);
			  
			   header("Location:imprimeRelatorio.php?nome_rel=".$nome_relatorio."&ca_rela=".$cabe_relatorio."&campos=".$campos_tabela."&t_campos=".$tama_campo."&tab=".$tabela."&condi=".$condicao."&nome_arqui=".$nome_arquivo);
		  break;
		  default:
	   } 
   ?>
   <form name="cadastro" id="cadastro" align="center" action="imprime_lista_entrega.php" method="POST">
       <table id="tb_lista">
		   <tr>
			  <td id="t_form" colspan="2">Imprime lista de entrega</td>
		   </tr>
           <tr>
				<td class="input-group" id="c_lista">
					<?php if(isset($_SESSION['nu_lista_sel'])) {
						$nu_lista_sel = $_SESSION['nu_lista_sel'];
					} else {
						$nu_lista_sel ='';
					}
					?>
					
					<div class="input-group ml-4 mb-20" id="c_lista">
						<span class="input-group-addon">Lista</span>
						<div class="col-md-13">
						   <select id="nu_lista" name="nu_lista_sel" class="form-control">
							  <option value="">Selecione a lista de entrega</option>
							  <?php
							  $sql2 = "SELECT DISTINCT nu_lista_entrega,(SELECT nome FROM pessoa WHERE cnpj_cpf=entregador),
							  date_format(dt_lista_entrega,'%d/%m/%Y')
							  FROM movimento
							  WHERE nu_lista_entrega<>''";
							  $resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar as tabelas da SQL");
							  while ( $linha = mysqli_fetch_array($resul)) {
								$select = $nu_lista_sel == $linha[0] ? "selected" : "";
								echo "<option value=\"".$linha[0]. "\" $select>" .$linha[0]." - ".$linha[1]." - ".$linha[2]."</option>";
							  }					
							  ?>
						   </select>
						</div>
					</div>	
				</td>
				<td id="sel_lista">
					<button type="Submit" name="sele_lista" id="bt_sel_lista" class="btn btn-primary">Imprime a lista</button>
				</td>
		   </tr>
	   </table>	
	   <div style="width:1150px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:100px;margin-top:0px;">	   
		   <div style="width:85px;height:40px;float:left;position:relative;margin-left:7px;margin-top:6px;">
			   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
		   </div>
	   </div> 
   </form>
</body>
</html>