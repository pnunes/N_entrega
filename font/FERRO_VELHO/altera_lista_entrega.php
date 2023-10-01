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
	//recebendo parametros de resposta da rotina atualiza_lista_entrega.php
	if(isset($_GET['resposta'])){
		$resp_grava = $_GET['resposta'];
	}else {
		$resp_grava ='';
	}
	if(isset($_GET['cor'])){
		$cor_msg = $_GET['cor'];
	}
	
    switch (get_post_action('sele_lista','imprime_lista')) {

          case 'sele_lista':

             //Pega data digitada e o codigo do entregador
             $nu_lista                   =$_POST['nu_lista'];
             $_SESSION['nu_lista_m']     =$nu_lista;
			 
			 // Pega codigo entregador e codigo serviço da lista de entrega em alteração
			 $pega_dados = "SELECT DISTINCT nu_lista_entrega,entregador,dt_lista_entrega FROM movimento 
			 WHERE nu_lista_entrega='$nu_lista'
			 AND entregador<>''";
			 $q_pega_dados = mysqli_query($cone,$pega_dados) or die ("Não foi possivel acessar a tabela movimento");
			 $tot01 = mysqli_num_rows($q_pega_dados);
             for($it=0; $it<$tot01; $it++){
                  $row = mysqli_fetch_row($q_pega_dados);
                  $co_servico        = $row[0];
				  $n_entregador      = $row[1];
				  $dt_lista_entrega  = $row[2];
             }
			 $_SESSION['co_servico_m']   =$co_servico;
			 $_SESSION['n_entregador_m'] =$n_entregador;
			 $_SESSION['dt_lista_ent_m'] =$dt_lista_entrega;
          break;
		  
          case 'imprime_lista':
               $nu_lista    = $_SESSION['nu_lista_m'];
               
               //Pega nome entregador
               $verifi="SELECT nome FROM cli_for WHERE cnpj_cpf='$entregador'";
               $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco");
               $total = mysqli_num_rows($query);
               for($ic=0; $ic<$total; $ic++){
                  $mostra = mysqli_fetch_row($query);
                  $nome_entregador        = $mostra[0];
               }
               $nome_relatorio = 'LISTA DE ENTREGA :'.$nu_lista.' - Entregador :'.$nome_entregador; 
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
    <link rel="stylesheet" type="text/css" href="css/altera_lista_entrega.css">
	
	<script type="text/javascript">
       function salva(campo){
            lista_entrega.submit()
       }

       <!-- FUNÇÃO PARA DESABILITAR O CRTL+J-->

       function retornoCodbar(evt, valor){
        <!--ENTER = 13 -->
        if (window.event){
           var tecla = window.event.keyCode;
           if(tecla==13){
             <!--alert('Código de barras: '+valor);-->
             window.event.returnValue = false;
           }
        }
        else{
           var tecla = (evt.which) ? evt.which : evt.keyCode;
           if(tecla==13){
              <!--alert('Código de barras: '+valor);-->
             evt.preventDefault();
           }
        }
       }

       function desabilitaCtrlJ(evt){
          //ctrl+j == true+106
          //ctrl+J == true+74
          if (window.event){ //IE
             var ctrl = event.ctrlKey;
             var tecla = window.event.keyCode;
             if((ctrl==true)&&((tecla==106)||(tecla==74))){
                window.event.returnValue = false;
             }
          }
          else{ //Firefox
             var ctrl = evt.ctrlKey;
             var tecla = (evt.which) ? evt.which : evt.keyCode;
             if((ctrl==true)&&((tecla==106)||(tecla==74))){
                evt.preventDefault();
             }
          }
       }
	   
	   /* Função para limpar a barra de endereço após serem recuperados os gets */
	   
	   if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://localhost/N_entregas/altera_lista_entrega.php");
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
					<td class="input-group" id="c_lista">
						<?php if(isset($_SESSION['nu_lista_m'])) {
							$nu_lista = $_SESSION['nu_lista_m'];
						} else {
							$nu_lista ='';
						}?>
						<span class="input-group-addon">Lista</span>
						<div class="col-md-13">
							<select id="nu_lista" name="nu_lista" class="form-control">
							    <option value="">Selecione a lista de entrega</option>
								<?php
								$sql2 = "SELECT DISTINCT movimento.nu_lista_entrega,(SELECT nome FROM cli_for WHERE cnpj_cpf=movimento.entregador),
								(SELECT nome FROM cli_for WHERE cnpj_cpf=movimento.codi_cli),servico.descri_se,movimento.dt_lista_entrega
								FROM movimento,servico
								WHERE movimento.nu_lista_entrega<>''
								AND movimento.co_servico=servico.codigo_se";
								$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar as tabelas da SQL");
								while ( $linha = mysqli_fetch_array($resul)) {
									$select = $nu_lista == $linha[0] ? "selected" : "";
									echo "<option value=\"". $linha[0] . "\" $select>" . $linha[0] ." - ". $linha[1]." - ".$linha[2]." - ".$linha[3]." - ".$linha[4]."</option>";
								}					
								?>
							</select>
						</div>
					</td>
					<td id="sel_lista">
					    <button type="Submit" name="sele_lista" class="btn btn-primary">Seleciona lista</button>
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
				   //Verifica se a hawb ja foi lançada no sistema
				   $verifi="SELECT controle,nu_lista_entrega FROM movimento WHERE cod_barra='$codi_barra'";
				   $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar a tabela movimento!");
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
						 $nu_lista_ante  = $mostra[1];
					   }
					   $_SESSION['controle_m']   =$controle;
					    
					   //Verifica se a hawb faz parte de alguma outra lista
					   if ($nu_lista_ante =='') {
						   
						   ///altera o movimento
						   $nu_lista          = $_SESSION['nu_lista_m'];
						   $co_servico	      = $_SESSION['co_servico_m'];
						   $n_entregador      = $_SESSION['n_entregador_m'];
						   $dt_lista_entrega  = $_SESSION['dt_lista_ent_m'];
						   $estatus        	  = 'BIP2';
						   $controle		  = $_SESSION['controle_m'];
						   
						   $alteracao = "UPDATE movimento SET entregador='$n_entregador',dt_lista_entrega='$dt_lista_entrega',
						   nu_lista_entrega='$nu_lista',estatus_hawb='$estatus',co_servico='$co_servico'
						   WHERE controle='$controle'";
						   if (mysqli_query($cone,$alteracao)) {
							  //Atualiza a tabela log_operação sistema
							 /* $programa     =$_SESSION['programa_m'];
							  $matricula_m  =$_SESSION['matricula_m'];
							  $hora         = date ('H:i:s');
							  $data         = date('Y-m-d');
							  $n_hawb       =$_SESSION['n_hawb_m'];
							  $codi_barra_g =$_SESSION['cod_barra_g'];
							  $inclui="INSERT INTO log_operacao_sistema(matricula,tarefa_executada,data,hora,rotina,n_hawb)
							  VALUES('$matricula_m','Elabora Lista com leitora','$data','$hora','$programa','$n_hawb')";
							  mysqli_query($con,$inclui);*/
							  
							  //////////////////Atualiza a tabela de histórico HAWB /////////////////////////////////
							/*  $atualiza="INSERT INTO controle_reentrega (n_hawb,dt_evento,ocorrencia,cod_barra,ordem)
							  VALUES('$n_hawb','$v_dt_lista','Em rota de entrega.','$codi_barra_g','2')";
							  mysqli_query($con,$atualiza);
							  $codi_barra          ='';
							  $controle            ='';
							  $entrega             ='';
							  //$c_data_li           ='';
							  //unset($_SESSION['n_data_li_m']);
							  unset($_SESSION['tentativas_m']);
							  unset($_SESSION['controle_m']);
							  unset($_SESSION['n_hawb_m']);
							  unset($_SESSION['cod_barra_g']);
							  $v_numero_lista  =$_SESSION['numero_lista_m'];*/
							  $cor_msg='S';
							  $resp_grava="HAWB adicionada a lista com sucesso";
						   }
						   else {
							  $cor_msg='D';
							  $resp_grava="Não foi possível adicionar a HAWB a lista! Verifique.";
						   }//Fim if da gravação do registro
					   }
					   else {
						   $cor_msg='D';
						   $resp_grava="Esta HAWB já constra da lista de entrega : $nu_lista_ante ! Verifique.";
					   } //Fim do if de verificação se o campo numero da lista esta vazio
				   }//Fim do if de verificação se o registro com o codigo de barra foi localizado
			    }//Fim do if de verificação se foi lido algum codigo de barra	   
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
					   <th width="20%" align="center" class="cab_tab"><b>BAIRRO</b></th>
					   <th width="15%" align="center" class="cab_tab"><b>CIDADE</b></th>
					   <th width="15%" align="center" class="cab_tab"><b>Excluir</b></th>
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
							 $mostra_lista ="SELECT controle,n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti
							 FROM movimento WHERE nu_lista_entrega='$nu_lista'";
							 $query_lista = mysqli_query($cone,$mostra_lista);
							 $conta_re    = mysqli_num_rows($query_lista);
							 for($i=0; $i<$conta_re; $i++){
								 $row = mysqli_fetch_row($query_lista);
								 $controle		= $row[0];
								 $n_hawb        = $row[1];
								 $nome_desti    = $row[2];
								 $rua_desti     = $row[3];
								 $numero_desti  = $row[4];
								 $bairro_desti  = $row[5];
								 $cidade_desti  = $row[6];
								 ?>
								 <tr>
								 <td width="2%" align="center" class="itens"><?php echo $controle;?></td>
								 <td width="5%" align="center" class="itens"><?php echo $n_hawb;?></td>
								 <td width="24%" class="itens"><?php echo $nome_desti;?></td>
								 <td width="24%" class="itens"><?php echo $rua_desti;?></td>
								 <td width="5%" align="center" class="itens"><?php echo $numero_desti;?></td>
								 <td width="20%" align="center" class="itens"><?php echo $bairro_desti;?></td>
								 <td width="15%" align="center" class="itens"><?php echo $cidade_desti;?></td>
								 <td class="cell4" align="center"><a href='retira_hawb_lista_entrega.php?codigo=<?php echo $controle;?>&tabela=movimento'><span class="glyphicon glyphicon-remove text-danger"></span></a></td>
								 </tr>
								 <?php
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
		 </div>
     </form>
</body>
</html>



