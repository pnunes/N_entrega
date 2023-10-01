<?php
  
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
        session_start();
    }
    
	//Conecta com o banco de dados
    require_once('Conexao.php');
    $conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');
	
	/*OBSERVAÇÃO IMPORTANTE: Como nesta rotina e feito chamada a classe DOMPDF, não pode haver tags html nem get nem set php antes
	da chamada da biblioteca DOMPDF*/
	
	// Função para pegar o name do botão submit
    function get_post_action($name) {
       $params = func_get_args();
       foreach ($params as $name) {
         if (isset($_POST[$name])) {
            return $name;
         }
       }
    }
    if(!isset($resp_grava)){
	   $resp_grava ='';
	}
    If(!isset($nu_hawb)){
		$nu_hawb=0;
	}
	If(!isset($conta_lo)){
		$conta_lo=0;
	}
    switch (get_post_action('imprime_lista','gera_lote')) {

          case 'gera_lote':
		        // Setando o padrão de hora para pegar a hora correta.
		        date_default_timezone_set('America/Sao_Paulo');
				
		        // Salvando a data da lista e guardando numa variavel global
                $data_lote = $_POST['data_lote'];
				$_SESSION['data_lote_m'] = $data_lote;
				
				$dt_lote     = explode("/",$data_lote);
			    $v_dt_lote_g = $dt_lote[2]."-".$dt_lote[1]."-".$dt_lote[0];
				$_SESSION['data_lote_g'] = $v_dt_lote_g;
				
				// Salvando o codigo do entregador e gravando numa variavel global
				$cliente = $_POST['cliente'];
				$_SESSION['cliente_m'] = $cliente;
				
				// Tirando as barras da data para compor o numero da remessa
				$d_lote    = str_replace('/','',$data_lote);
				
				// Pegando a hora, tirando os caracteres : para compor o numero da remessa
                $h_lote     = date('h:i:s');
				$h_lote     = str_replace(':','',$h_lote);
				$_SESSION['hr_lote_m'] = $h_lote;
				
                // Compondo o numero da lista com data + hora,minuto e segundo e guardando numa variavel global 
                $nu_lote  = $d_lote.$h_lote;
                $_SESSION['nu_lote_m'] = $nu_lote;				
              
          break;
          case 'imprime_lista':
               $cliente  = $_SESSION['cliente_m'];
               $nu_lote  = $_SESSION['nu_lote_m'];
               
               //Pega nome entregador
               $verifi="SELECT nome FROM pessoa WHERE cnpj_cpf='$cliente'";
               $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco");
               $total = mysqli_num_rows($query);
               for($ic=0; $ic<$total; $ic++){
                  $mostra = mysqli_fetch_row($query);
                  $nome_cliente        = $mostra[0];
               }
               $nome_relatorio = 'LISTA DE ENTREGA :'.$nu_lote.' - Entregador :'.$nome_cliente; 
			  // $_SESSION['nome_relato_m'] = $nome_relatorio; 
			   $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,NUM,BAIRRO,CIDADE';
			  // $_SESSION['cabe_relato_m'] = $cabe_relatorio;
			   $campos_tabela  = 'n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti';
			   $tama_campo     = '60,240,200,30,150,150';
			  // $_SESSION['campos_tabe_m'] = $campos_tabela;
               $tabela         = 'movimento';
			  // $_SESSION['tabela_rel_m'] = $tabela;
			   $condicao       = 'nu_lista_entrega ='.$nu_lista;
			  // $_SESSION['condi_rel_m'] = $condicao;
			   
			   // Chama a classe ImprimeRelatorio
			   require_once("ImprimeRelatorio.php"); 
			   //Instancia a classe e executa a geração do relatorio
		       $imprime   = new ImprimeRelatorio($campos_tabela,$tabela,$condicao,$nome_relatorio,$cabe_relatorio,$tama_campo);   
               $relatorio = $imprime->Listar(); 
               //var_dump($relatorio);			   
          break;
          default:
    }
	
	////////////////////////////////////////////////////////////////////////
    //Pega variavel que identifica função do formulario
	if(isset($_GET['titulo_form'])){
		$titulo_form = $_GET['titulo_form'];
		$_SESSION['titulo_form_m'] = $_GET['titulo_form'];
	}else {
		$titulo_form = $_SESSION['titulo_form_m'];
	}
	
	////////////////////////////////////////////////////////////////////////
    //Pega variavel que identifica qual parte do sistema esta sendo usado
	if(isset($_GET['modulo'])){
		$modulo = $_GET['modulo'];
		$_SESSION['modulo_m'] = $_GET['modulo'];
	}
	
	if(!isset($resp_grava)){
		$resp_grava='';
	}
	if(!isset($cor_msg)) {
		$cor_msg ='';
	}

    require_once('cabeca_paginas_internas.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>elabora_lista_entrega.php</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Recursos para o autocomplete - campo nome destino  e para campo data bootstrap-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
   
    <script src="js/bootstrap.min.js"></script> 
    <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="css/elabora_lista_devolucao_origem.css">
	
	<script type="text/javascript">
       function salva(campo){
            lista_devolucao.submit()
       }

	   /* Função para limpar a barra de endereço após serem recuperados os gets */
	   
	   if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://localhost/N_entregas/elabora_lista_devolucao_origem.php");
	   }
   </script>
	
</head>
  <body>
     <form method="POST" name="lista_devolucao" action="">
		<div id="dv_geral">
			  <table id="tb_dados_fixos_lista" class="table table-sm">
			     <tr>
				     <td id="t_form" colspan="2"><?php echo $titulo_form;?></td>
				 </tr>
				 <tr>
				  <td class="col-md-7" id="c_dt_devolucao">
						<div class="input-group">
						<?php if(isset($_SESSION['data_lote_m'])) {
							 $data_lote = $_SESSION['data_lote_m'];
						} else {
							$data_lote = date('d/m/Y');
						}?>
						<span class="input-group-addon">Data Devolução</span>
						<div class="input-group date">
								<input type="text" class="form-control" id="data_lote" value="<?php echo $data_lote;?>" name="data_lote">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<script type="text/javascript">
								$('#data_lote').datepicker({	
								format: "dd/mm/yyyy",	
								language: "pt-BR",
								/*startDate: '+0d',*/
							});
						</script>
						</div>	
					</td>
					<td class="input-group" id="c_cliente">
						<?php if(isset($_SESSION['cliente_m'])) {
							$cliente = $_SESSION['cliente_m'];
						} else {
							$cliente ='';
						}?>
						<span class="input-group-addon">Cliente</span>
						<div class="col-md-13">
							<select id="cliente" name="cliente" class="form-control">
							    <option value="">Selecione o Cliente</option>
								<?php
								$sql2 = "SELECT cnpj_cpf,nome FROM pessoa WHERE ativo='S' AND categoria='01'";
								$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
								while ( $linha = mysqli_fetch_array($resul)) {
									$select = $cliente == $linha[0] ? "selected" : "";
									echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
								}					
								?>
							</select>
						</td>
					</div>	
				</tr>
				<tr> 
                   <td colspan="2" id="n_lote_devolucao">				
					   <div class="col-md-3" id="n_lote">
						   <?php if(isset($_SESSION['nu_lote_m'])) {
							  $nu_lote = $_SESSION['nu_lote_m'];
						   }else {
							  $nu_lote =''; 
						   }
						   ?>
						   <div class="input-group">
							  <span class="input-group-addon">Lote Devolução</span>
							  <input id="nu_lista" name="nu_lote" class="form-control" type="text" value ="<?php echo $nu_lote;?>">
						   </div>	
					   </div>
					   <button type="Submit" id="gn_lista_cabe" name="gera_lote" class="btn btn-primary">Gera Numero Lote</button>
				   </td>
				</tr>
			  </table>
			  <?php
			   // Verifica se foi passado hawb na leitora e capturado o codigo de barra
			   if(isset($_POST['cod_barra'])) {
				  $codi_barra   =$_POST['cod_barra'];
			   }
			   else {
				  $codi_barra  =''; 
			   }
			   if ($codi_barra<>'') {
				   if(!isset($_SESSION['cliente_m']) or $_SESSION['cliente_m'] == '') {
					   $cor_msg='D';
					   $resp_grava ='Cliente não foi Selecionado! Selecione.';
				   }
				   else {
					   If (!isset($_SESSION['nu_lote_m']) or  $_SESSION['nu_lote_m'] =='') {
						   $cor_msg='D';
						   $resp_grava ='Numero do lote nao foi gerado! Verifique.';
					   }
				       else {
						   //Verifica se a hawb ja foi lançada no sistema
						   $verifi="SELECT controle,lote_devo_origem,dt_rece_hawb 
						   FROM movimento
						   WHERE cod_barra='$codi_barra'";
						   $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco -1");
						   $total = mysqli_num_rows($query);
						   If ($total == 0 ) {
							   $cor_msg='D';
							   $resp_grava ='HAWB não lançada no sistema! Verifique.';
							   $codi_barra='';
						   }
						   else {
							   for($ic=0; $ic<$total; $ic++){
								 $mostra = mysqli_fetch_row($query);
								 $controle           = $mostra[0];
								 $lote_origem		 = $mostra[1];
								 $data_entra_sistema = $mostra[2];
							   }
							   
							   //Altera formato da data de entrada no sistema para comparar
							   $dt_entrada_sis   = explode("/",$data_entra_sistema);
							   $v_dt_entra_sis   = $dt_entrada_sis[2]."-".$dt_entrada_sis[1]."-".$dt_entrada_sis[0];
							   
							   // Cria variaveis com as data da lista e data do movimento alteradas para ver qual a maior
							   $v_dt_lote_g  = $_SESSION['data_lote_g'];
							   
							   $dt_lote_co = strtotime($v_dt_lote_g);
							   $dt_movi_co  = strtotime($v_dt_entra_sis);
							   //Verifica se a hawb faz parte de alguma outra lista
							   If ($lote_origem =='') {
								   if ($dt_lote_co >= $dt_movi_co) {
									   
									   ///Grava o movimento
									   $nu_lote        = $_SESSION['nu_lote_m'];
									   $h_lote         = $_SESSION['hr_lote_m'];

									   $alteracao = "UPDATE movimento SET dt_lote_devo_origem='$v_dt_lote_g',
									   lote_devo_origem='$nu_lote',estatus_hawb='BIP4',ocorrencia='04'
									   WHERE controle='$controle'";
									   if (mysqli_query($cone,$alteracao)) {
										  $cor_msg='S';
										  $resp_grava="HAWB adiconada a lista de devolução com sucesso";
										
										  ////////////////////////////////////////////PEGA DADOS DA HAWB PARA REGISTRAR NO ARQUIVO HISTORICO_HAWB////////////////////
										  $pega_dados ="SELECT n_hawb,cod_barra FROM movimento WHERE controle='$controle'";
										  $query_dados = mysqli_query($cone,$pega_dados) or die ("Não foi possivel acessar o banco 1");
										  $achou_d = mysqli_num_rows($query_dados);
										  for($ic=0; $ic<$achou_d; $ic++){
											  $mostra = mysqli_fetch_row($query_dados);
											  $n_hawb     = $mostra[0];
											  $codi_barra = $mostra[1];
										  }
											
										  /////////////////////////////////Atualiza a tabela de histórico HAWB /////////////////////////////////
										  
										  $usuario = $_SESSION['cpf_m'];
										  $ocorrencia ='04';
										  $hora = date('H:i');
										 
										  $atualiza="INSERT INTO historico_hawb (n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro)
										  VALUES('$n_hawb','$codi_barra','$ocorrencia','$v_dt_lote_g','$usuario','$hora')";
										  mysqli_query($cone,$atualiza);
										  $codi_barra          ='';
										  $controle            ='';
										  $entrega             ='';
									   }
									   else {
										  $cor_msg='D';
										  $resp_grava="Problemas ao adiconar HAWB ao lote de devolução! Verifique.";
									   }//Fim if da gravação do registro
								   }
								   else {
									   $cor_msg='D';
									   $codi_barra  ='';
									   $resp_grava ='Data do lote é inferior a data entrada da HAWB no sistema! Verifique.';
								   }// Fim do if de comparação da data da lista com data da entrada da hawb
							   }
							   else {
								   $cor_msg='D';
								   $resp_grava="Esta HAWB já constra da lista de entrega : $lote_origem ! Verifique.";
							   } //Fim do if de verificação se o numero da lista esta vazio
						   }//Fim do if de verificação se a HAWB já consta de alguma lista
					   }// Fim do if de verificação se o campo numero da lista foi preenchido 
				   } // Fim do if que verifica se o entregador foi selecionado
			   } // Fim do if que verifica se foi digitado o codigo de barra da hawb
			  ?>
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
            <div class="table-wrapper">			  
			  <table id="tb_itens_entrega" class="table-wrapper">
			     <thead>
					 <tr>
					   <th width="2%" align="center" class="cab_tab"><b>ORD.</b></th>
					   <th width="5%" align="center" class="cab_tab"><b>HAWB</b></th>
					   <th width="24%" align="center" class="cab_tab"><b>DESTINATÁRIO</b></th>
					   <th width="24%" align="center" class="cab_tab"><b>RUA</b></th>
					   <th width="5%" align="center" class="cab_tab"><b>NUMERO</b></th>
					   <th width="15%" align="center" class="cab_tab"><b>BAIRRO</b></th>
					   <th width="15%" align="center" class="cab_tab"><b>CIDADE</b></th>
					 </tr>
				 </thead>
				 <tbody>
				     <?php
					     if(isset($_SESSION['nu_lote_m'])) {
					        $nu_lote  = $_SESSION['nu_lote_m'];
						 }else {
							$nu_lote  =''; 
						 }
						 if($nu_lote<>'') {
							 $it =1;
							 $mostra_lote ="SELECT n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti
							 FROM movimento WHERE lote_devo_origem='$nu_lote'";
							 $query_lote = mysqli_query($cone,$mostra_lote);
							 $conta_lo    = mysqli_num_rows($query_lote);
							 for($i=0; $i<$conta_lo; $i++){
								 $row = mysqli_fetch_row($query_lote);
								 $n_hawb        = $row[0];
								 $nome_desti    = $row[1];
								 $rua_desti     = $row[2];
								 $numero_desti  = $row[3];
								 $bairro_desti  = $row[4];
								 $cidade_desti  = $row[5];
								 ?>
								 <tr>
								 <td width="2%" align="center" class="itens"><?php echo $it;?></td>
								 <td width="5%" align="center" class="itens"><?php echo $n_hawb;?></td>
								 <td width="24%" class="itens"><?php echo $nome_desti;?></td>
								 <td width="24%" class="itens"><?php echo $rua_desti;?></td>
								 <td width="5%" align="center" class="itens"><?php echo $numero_desti;?></td>
								 <td width="15%" align="center" class="itens"><?php echo $bairro_desti;?></td>
								 <td width="15%" align="center" class="itens"><?php echo $cidade_desti;?></td>
								 </tr>
								 <?php
								 $it++;
							 }
						 } 
					 ?>
				 </tbody>
			  </table>
			</div>
			<div id="tb_rodape">
			     <div id="l_tb_rodape">
			        Quantidade HAWBs :<?php echo $conta_lo; ?>
				 </div>
				 <div id="i_lista">
				   <button type="Submit" name="imprime_lista" class="btn btn-primary">Imprime a Lista</button>
				 </div>
				 <div id="bt_voltar">
				    <a href="entrada.php"><button name="voltar" class="btn btn-primary" type="button">Voltar</button></a>
				 </div>
			  </div>
			<div class="input-group" id="cod_barras">
				 <?php if(!isset($cod_barra)) {
				   $cod_barra ='';
				 }
				 ?>
			     <span class="input-group-addon">Cod. Barras</span>
			     <input id="cod_barra" name="cod_barra" class="form-control" placeholder="" type="text" value ="<?php echo $cod_barra;?>" onChange="salva(this)">
			     <!-- Coloca foco no primeiro campo codigo de barras do formulário -->
			     <script language="JavaScript">
					 document.getElementById('cod_barra').focus()
			     </script>
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
		</div>
     </form>
  </body>
</html>



