<?php
  
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
        session_start();
    }
    
	//definindo o padrão da hora
	date_default_timezone_set('America/Sao_Paulo');
	
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
    if(!isset($conta_re)){
		$conta_re=0;
    }		
    switch (get_post_action('imprime_lista','gn_lista')) {

          case 'gn_lista':
		        // Setando o padrão de hora para pegar a hora correta.
		        date_default_timezone_set('America/Sao_Paulo');
				
		        // Salvando a data da lista e guardando numa variavel global
                $data_lista = $_POST['data_lista'];
				$_SESSION['data_lista_m'] = $data_lista;
				
				$dt_lista     = explode("/",$data_lista);
			    $v_dt_lista_g = $dt_lista[2]."-".$dt_lista[1]."-".$dt_lista[0];
				$_SESSION['data_lista_g'] = $v_dt_lista_g;
				
				// Salvando o codigo do entregador e gravando numa variavel global
				$entregador = $_POST['entregador'];
				$_SESSION['entregador_m'] = $entregador;
				
				// Tirando as barras da data para compor o numero da remessa
				$d_lista    = str_replace('/','',$data_lista);
				
				// Pegando a hora, tirando os caracteres : para compor o numero da remessa
                $h_lista    = date('h:i:s');
				$h_lista    = str_replace(':','',$h_lista);

                // Compondo o numero da lista com data + hora,minuto e segundo e guardando numa variavel global 
                $nu_lista = $d_lista.$h_lista;
                $_SESSION['nu_lista_m'] = $nu_lista;				
              
          break;
          case 'imprime_lista':
		  
               $nu_lista_sel = $_SESSION['nu_lista_m']; 
			   
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
               

               $nome_relatorio = 'LISTA DE ENTREGA :'.$nu_lista_sel.' - Entregador :'.$nome_entregador; 
			  // $_SESSION['nome_relato_m'] = $nome_relatorio; 
			   $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,NUM,BAIRRO,CIDADE';
			  // $_SESSION['cabe_relato_m'] = $cabe_relatorio;
			   $campos_tabela  = 'n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti';
			   $tama_campo     = '40,210,210,40,150,150';
			  // $_SESSION['campos_tabe_m'] = $campos_tabela;
               $tabela         = 'movimento';
			  // $_SESSION['tabela_rel_m'] = $tabela;
			   $condicao       = 'nu_lista_entrega ='."'".$nu_lista_sel."'";
			    
			   header("location:imprimeRelatorio.php?nome_rel=".$nome_relatorio."&ca_rela=".$cabe_relatorio."&campos=".$campos_tabela."&t_campos=".$tama_campo."&tab=".$tabela."&condi=".$condicao);
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
    <link rel="stylesheet" type="text/css" href="css/elabora_lista_entrega.css">
	
	<script type="text/javascript">
       function salva(campo){
            lista_entrega.submit()
       }

	   /* Função para limpar a barra de endereço após serem recuperados os gets */
	   
	   if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://localhost/N_entregas/elabora_lista_entrega.php");
	   }
   </script>
	
</head>
  <body>
     <form method="POST" name="lista_entrega" action="">
		<div id="dv_geral">
			  <table id="tb_dados_fixos_lista" class="table table-sm">
			     <tr>
				     <td id="t_form" colspan="2"><?php echo $titulo_form;?></td>
				 </tr>
				 <tr>
				    <td class="col-md-7" id="c_dt_entrega">
						<div class="input-group">
						<?php if(isset($_SESSION['data_lista_m'])) {
							 $data_lista = $_SESSION['data_lista_m'];
						} else {
							$data_lista = date('d/m/Y');
						}?>
						<span class="input-group-addon">Data Lista</span>
						<div class="input-group date">
								<input type="text" class="form-control" id="data_lista" value="<?php echo $data_lista;?>" name="data_lista">
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
					</td>
					<td class="input-group" id="c_entregador">
						<?php if(isset($_SESSION['entregador_m'])) {
							$entrega = $_SESSION['entregador_m'];
						} else {
							$entrega ='';
						}?>
						<span class="input-group-addon">Entregador</span>
						<div class="col-md-13">
							<select id="entregador" name="entregador" class="form-control">
							    <option value="">Selecione o Entregador</option>
								<?php
								$sql2 = "SELECT cnpj_cpf,nome,cidade,estado FROM pessoa WHERE ativo='S' AND (categoria='02' OR categoria='04')";
								$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
								while ( $linha = mysqli_fetch_array($resul)) {
									$select = $entrega == $linha[0] ? "selected" : "";
									echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1].' - '.$linha[2].' - '.$linha[3]."</option>";
								}					
								?>
							</select>
						</td>
					</div>	
				</tr>
				<tr> 
                   <td colspan="2" id="n_lista_entrega">				
					   <div class="col-md-3" id="n_lista">
						   <?php if(isset($_SESSION['nu_lista_m'])) {
							  $nu_lista = $_SESSION['nu_lista_m'];
						   }else {
							  $nu_lista =''; 
						   }
						   ?>
						   <div class="input-group">
							  <span class="input-group-addon">Numero Lista</span>
							  <input id="nu_lista" name="nu_lista" class="form-control" type="text" value ="<?php echo $nu_lista;?>">
						   </div>	
					   </div>
					   <button type="Submit" id="gn_lista_cabe" name="gn_lista" class="btn btn-primary">Gera Numero Lista</button>
				   </td>
				</tr>
			  </table>
			  <?php
			  
			 ///////////////// Verifica se foi passado hawb na leitora e capturado o codigo de barra ///////////////////////////////////////
			   
			   if(isset($_POST['cod_barra'])) {
				  $codi_barra   =$_POST['cod_barra'];
			   }
			   else {
				  $codi_barra  =''; 
			   }
			   if ($codi_barra<>'') {
				   if(!isset($_SESSION['entregador_m']) or $_SESSION['entregador_m'] == '') {
					   $cor_msg='D';
					   $resp_grava ='Entregador não foi Selecionado! Selecione.';
				   }
				   else {
					   If (!isset($_SESSION['nu_lista_m']) or  $_SESSION['nu_lista_m'] =='') {
						   $cor_msg='D';
						   $resp_grava ='Não foi gerado o numero da Lista! Verifique.';
					   }
				       else {
						   //Verifica se a hawb ja foi lançada no sistema
						   $verifi="SELECT controle,tentativa_entrega,nu_lista_entrega,dt_rece_hawb,n_hawb 
						   FROM movimento
						   WHERE cod_barra='$codi_barra'";
						   $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco");
						   $total = mysqli_num_rows($query);
						   If ($total == 0 ) {
							   $cor_msg='D';
							   $resp_grava ='HAWB não lançada no sistema! Verifique.';
							   $codi_barra='';
						   }
						   else {
							   for($ic=0; $ic<$total; $ic++){
								 $mostra = mysqli_fetch_row($query);
								 $controle       = $mostra[0];
								 $tenta_entrega  = $mostra[1];
								 $numero_lista   = $mostra[2];
								 $data_movi      = $mostra[3];
								 $n_hawb         = $mostra[4];
							   }
							   
							   //Altera formato da data da lista para comparar
							   $dt_lista     = explode("/",$data_lista);
							   $v_data_lista = $dt_lista[2]."-".$dt_lista[1]."-".$dt_lista[0];
							   
							   // Cria variaveis com as data da lista e data do movimento alteradas para ver qual a maior
							   $dt_lista_co = strtotime($v_data_lista);
							   $dt_movi_co  = strtotime($data_movi);
							   //Verifica se a hawb faz parte de alguma outra lista
							   If ($numero_lista=='') {
								   if ($dt_lista_co >= $dt_movi_co) {
									   
									   ///////////////////////////////////////////Grava o movimento na tabela movimento//////////////////////////
									   
									   $entregador     = $_SESSION['entregador_m'];
									   $v_dt_lista_g   = $_SESSION['data_lista_g'];
									   $tenta_entrega  = $tenta_entrega+1;
									   $nu_lista       = $_SESSION['nu_lista_m'];

									   $alteracao = "UPDATE movimento SET entregador='$entregador',dt_lista_entrega='$v_dt_lista_g',
									   tentativa_entrega='$tenta_entrega',nu_lista_entrega='$nu_lista',
									   ocorrencia='02',estatus_hawb='BIP2'
									   WHERE controle='$controle'";
									   if (mysqli_query($cone,$alteracao)) {
										  
										  /////////////////////////////////Atualiza a tabela de histórico HAWB /////////////////////////////////
										  
										  $usuario = $_SESSION['cpf_m'];
										  $ocorrencia ='02';
										  $hora = date('H:i');
										 
										  $atualiza="INSERT INTO historico_hawb (n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro)
										  VALUES('$n_hawb','$codi_barra','$ocorrencia','$v_dt_lista_g','$usuario','$hora')";
										  mysqli_query($cone,$atualiza);
										  $codi_barra          ='';
										  $controle            ='';
										  $entrega             ='';
										 
									   }
									   else {
										  $cor_msg='D';
										  $resp_grava="Problemas ao adiconar a HAWB a lista! Verifique.";
									   }//Fim if da gravação do registro
								   }
								   else {
									   $cor_msg='D';
									   $codi_barra  ='';
									   $resp_grava ='Data da lista é inferior a data entrada da HAWB no sistema! Verifique.';
								   }// Fim do if de comparação da data da lista com data da entrada da hawb
							   }
							   else {
								   $cor_msg='D';
								   $resp_grava="Esta HAWB já constra da lista de entrega : $numero_lista ! Verifique.";
							   } //Fim do if de verificação se o numero da lista esta vazio
						   }//Fim do if de verificação se a HAWB já consta de alguma lista
					   }// Fim do if de verificação se o campo numero da lista foi preenchido 
				   } // Fim do if que verifica se o entregador foi selecionado
			   } // Fim do if que verifica se foi digitado o codigo de barra da hawb
			   
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
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
					     if(isset($_SESSION['nu_lista_m'])) {
					        $nu_lista  = $_SESSION['nu_lista_m'];
						 }else {
							$nu_lista  =''; 
						 }
						 if($nu_lista<>'') {
							 $it =1;
							 $mostra_lista ="SELECT n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti
							 FROM movimento WHERE nu_lista_entrega='$nu_lista'";
							 $query_lista = mysqli_query($cone,$mostra_lista);
							 $conta_re    = mysqli_num_rows($query_lista);
							 for($i=0; $i<$conta_re; $i++){
								 $row = mysqli_fetch_row($query_lista);
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
			        Quantidade HAWBs :<?php echo $conta_re; ?>
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



