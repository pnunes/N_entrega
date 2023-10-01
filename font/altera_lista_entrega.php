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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    	
	<link rel="stylesheet" type="text/css" href="css/estilo_altera_lista_entrega.css">
	<?php
	
	//Chama a rotina que recebe os parametros passados pela rotina entrada.php
	require_once('recebe_parametros_entrada.php');
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

       switch (get_post_action('sele_lista','imp_lista')) {
		  case 'sele_lista':
		      $nu_lista_pes = $_POST['nu_lista_pes']; 
			  $_SESSION['nu_lista_pes'] = $_POST['nu_lista_pes'];
		  break;
		  case 'imp_lista':
			   $nu_lista_imp = $_SESSION['nu_lista_pes'];
			   
			   //Pega nome entregador
               $verifi="SELECT DISTINCT movimento.entregador,pessoa.nome 
			   FROM movimento,pessoa 
			   WHERE movimento.entregador=pessoa.cnpj_cpf
			   AND movimento.nu_lista_entrega='$nu_lista_imp'";
			   
               $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar a tabela movimento!");
               $total = mysqli_num_rows($query);
               for($ic=0; $ic<$total; $ic++){
                  $mostra = mysqli_fetch_row($query);
                  $entregador        = $mostra[0];
				  $nome_entregador   = $mostra[1];
               }  

               $nome_relatorio = 'LISTA DE ENTREGA :'.$nu_lista_imp.' - Entregador :'.$nome_entregador; 
			  // $_SESSION['nome_relato_m'] = $nome_relatorio; 
			   $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,NUM,BAIRRO,CIDADE';
			  // $_SESSION['cabe_relato_m'] = $cabe_relatorio;
			   $campos_tabela  = 'n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti';
			   $tama_campo     = '40,210,210,40,150,150';
			  // $_SESSION['campos_tabe_m'] = $campos_tabela;
               $tabela         = 'movimento';
			  // $_SESSION['tabela_rel_m'] = $tabela;
			   $condicao       = 'nu_lista_entrega ='."'".$nu_lista_imp."'";
			   
			  // $_SESSION['condi_rel_m'] = $condicao;
			 // var_dump($nome_relatorio.'-'.$cabe_relatorio.'-'.$campos_tabela.'-'.$tama_campo.'-'.$tabela.'-'.$condicao);
			  
			  header("location:imprimeRelatorio.php?nome_rel=".$nome_relatorio."&ca_rela=".$cabe_relatorio."&campos=".$campos_tabela."&t_campos=".$tama_campo."&tab=".$tabela."&condi=".$condicao);
		  break;
		  default:
	   } 
   ?>
   <form name="cadastro" id="cadastro" align="center" action="altera_lista_entrega.php" method="POST">
        <table id="tb_lista">
		   <tr>
			  <td id="t_form" colspan="2"><?php echo $titulo_form;?></td>
		   </tr>
           <tr>
				<td class="input-group" id="c_lista">
					<?php if(isset($_SESSION['nu_lista_pes'])) {
						$nu_lista_pes = $_SESSION['nu_lista_pes'];
					} else {
						$nu_lista_pes ='';
					}
					?>
					
					<div class="input-group ml-4 mb-20" id="c_lista">
						<span class="input-group-addon">Lista</span>
						<div class="col-md-13">
						   <select id="nu_lista" name="nu_lista_pes" class="form-control">
							  <option value="">Selecione a lista de entrega</option>
							  <?php
							  $sql2 = "SELECT DISTINCT nu_lista_entrega,(SELECT nome FROM pessoa WHERE cnpj_cpf=entregador),
							  date_format(dt_lista_entrega,'%d/%m/%Y')
							  FROM movimento
							  WHERE nu_lista_entrega<>''";
							  $resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar as tabelas da SQL");
							  while ( $linha = mysqli_fetch_array($resul)) {
								$select = $nu_lista_pes == $linha[0] ? "selected" : "";
								echo "<option value=\"".$linha[0]. "\" $select>" .$linha[0]." - ".$linha[1]." - ".$linha[2]."</option>";
							  }					
							  ?>
						   </select>
						</div>
					</div>	
				</td>
				<td id="sel_lista">
					<button type="Submit" name="sele_lista" id="bt_sel_lista" class="btn btn-primary">Seleciona lista</button>
				</td>
		   </tr>
	    </table>
		<div class="alert alert-danger" role="alert" id="mensagem_n" class="msg_conteudo"></div>
	    <div class="alert alert-success" role="alert" id="mensagem_s" class="msg_conteudo"></div>
		<?php
		  ///////////////// Verifica se foi passado hawb na leitora e capturado o codigo de barra ///////////////////////////////////////
	   	   
		   if(isset($_POST['codi_barra'])) {
			  $codi_barra  = $_POST['codi_barra'];
		   }
		   else {
			  $codi_barra  ='';
		   }
		    
		   if ($codi_barra <> '') {
			   
			   //Verifica se a hawb foi lançada no sistema e se consta de alguma lista de entrega
			   $verifi="SELECT controle,nu_lista_entrega,n_hawb
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
					 $controle_pes   = $mostra[0];
					 $numero_lista   = $mostra[1];
					 $n_hawb         = $mostra[2];
				   }
				   if($numero_lista <> ''){
					  $cor_msg='D';
					  $resp_grava ='HAWB Ja faz parte da lista de entrega : $numero_lista ! Confira.'; 
				   }else {
					   // Pega dados da lista em alteração
					   $nu_lista_pes = $_SESSION['nu_lista_pes'];
					   
					   $pesqui_lista = "SELECT DISTINCT entregador,dt_lista_entrega,tentativa_entrega 
					   FROM movimento 
					   WHERE nu_lista_entrega='$nu_lista_pes'";
					   $query_lis = mysqli_query($cone,$pesqui_lista) or die ("Não foi possivel acessar o banco"); 
					   $total_lis = mysqli_num_rows($query_lis);
					   for($li=0; $li < $total_lis; $li++){
						   $lista = mysqli_fetch_row($query_lis);
						   $entregador_lis   = $lista[0];
						   $dt_entrega_lis   = $lista[1];
						   $tentativa_lis    = $lista[2];
					   }
					   
					   $tentativa_lis = $tentativa_lis++;
					   
					   ///Grava o movimento
					
					   $alteracao = "UPDATE movimento SET entregador='$entregador_lis',dt_lista_entrega='$dt_entrega_lis',
					   tentativa_entrega='$tentativa_lis',nu_lista_entrega='$nu_lista_pes',ocorrencia='02',estatus_hawb='BIP2'
					   WHERE controle='$controle_pes'";
					   if (mysqli_query($cone,$alteracao)) {
						 
						  ?>
						  <script>
						     var texto ='HAWB adiconada a lista com sucesso!';
						     var div = document.querySelector("#mensagem_s");
							 div.style.display = "block";
							 $('#mensagem_s').html(texto).show();
							 setTimeout(function(){ 
								 cadastro.submit();
							 }, 1000);
						  </script>
						  <?php
						 
						  /////////////////////////////////////Atualiza a tabela de histórico HAWB /////////////////////////////////////////
						  
						  $usuario = $_SESSION['cpf_m'];
						  $ocorrencia ='08';
						  $hora = date('H:i');
						  
						  $atualiza="INSERT INTO historico_hawb (n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro)
						  VALUES('$n_hawb','$codi_barra','$ocorrencia','$dt_entrega_lis','$usuario','$hora')";
						  mysqli_query($cone,$atualiza);
						  $codi_barra          ='';
						  $controle            ='';
						  $entrega             ='';
					   }
					   else {
						  ?>
						  <script>
						     var texto ='Problemas ao adiconar a HAWB a lista! Verifique.!';
						     var div = document.querySelector("#mensagem_s");
							 div.style.display = "block";
							 $('#mensagem_n').html(texto).show();
							 setTimeout(function(){ 
								 cadastro.submit();
							 }, 1000);
						  </script>
						  <?php
						  
					   }//Fim if da gravação do registro
					}
				}//Fim do if de verificação se a hawb exite no sistema   
		   }//Fim do if de verificação se se o numero d lista esta vazio
			   
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(!isset($resp_grava_n)){
		    $resp_grava_n = '';
		}
		if(!isset($resp_grava_s)){
		    $resp_grava_s = '';
		}
		?>
	    <table id="tab_entra" class="table-wrapper">
		  <thead id="cabe_tab">
			  <tr>
			     <td colspan="2">
                     <div class="form-group input-group" id="div_busca">
				        <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				        <input name="consulta" id="txt_consulta" placeholder="Procurar" type="text" class="form-control">
						<div id="impressora">
						    <a href='imprime_documento_fpdf.php?codigo=$var1&tabela=$tabela&titulo_doc=$titulo_doc&documento=procuracao.php'><span class="glyphicon glyphicon-print text-primary" id="impre"></span></a>
						</div>
			         </div>
				 </td>
			  </tr>
			 
			  <tr id="cabe_tab">
				  <td class="cell1 cabe_td"><?php echo $cab_1;?></td>
				  <td class="cell2 cabe_td"><?php echo $cab_2;?></td>
				  <td class="cell5 cabe_td"><?php echo $cab_3;?></td>
				  <td class="cell6 cabe_td"><?php echo $cab_4;?></td>
				  <td class="cell3 cabe_td"><?php echo $cab_5;?></td>
				  <td class="cell6 cabe_td"><?php echo $cab_6;?></td>
				  <td class="cell4 cabe_td">MUDA</td>
				  <td class="cell4 cabe_td">TIRA</td>
			  </tr>
		  </thead>
	    <tbody>
		<?php
	    	if(isset($_SESSION['nu_lista_pes'])){
			   $nu_lista_pes = $_SESSION['nu_lista_pes']; 	
			}else {
			   $nu_lista_pes ='';
			}
			
			$seleciona_hawb ="SELECT movimento.controle,movimento.cod_barra,movimento.nome_desti,movimento.nu_lista_entrega,
			date_format(movimento.dt_lista_entrega,'%d/%m/%Y'),pessoa.nome 
			FROM movimento,pessoa
			WHERE movimento.nu_lista_entrega = '$nu_lista_pes' 
			AND movimento.entregador=pessoa.cnpj_cpf";
			
			$query_hawb = mysqli_query($cone,$seleciona_hawb) or die (mysqli_errno($cone)." - ".mysqli_error($cone));
			$total_hawb = mysqli_num_rows($query_hawb);
			
			for($i=0; $i<$total_hawb; $i++) {
			   $dados = mysqli_fetch_row($query_hawb);
			   $controle          = $dados[0];
			   $cod_barra         = $dados[1];
			   $destino           = $dados[2];
			   $nu_lista          = $dados[3];
			   $dt_lista_entrega  = $dados[4];
			   $entregador        = $dados[5];
			  ?>
			  <tr>
				 <td class="cell1" align="left" id="controle"> <?php echo $controle;?> </td>
				 <td class="cell2" align="left"> <?php echo $cod_barra;?> </td>
				 <td class="cell5" align="left"> <?php echo $destino;?> </td>				 
				 <td class="cell6">
				     <select id="nu_lista" name="nu_lista" class="form-control">
						<option value="">Selecione a lista de entrega</option>
						<?php
						$sqlli = "SELECT DISTINCT movimento.nu_lista_entrega,pessoa.nome,
						date_format(movimento.dt_lista_entrega,'%d/%m/%Y')
						FROM movimento,pessoa
						WHERE movimento.nu_lista_entrega<>''
						AND movimento.dt_entrega='1000-01-01'
						AND movimento.entregador=pessoa.cnpj_cpf";
						$resulis = mysqli_query($cone,$sqlli) or die ("Não foi possivel acessar as tabelas da SQL");
						while ( $linha = mysqli_fetch_array($resulis)) {
							$select = $nu_lista == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[0].' - '.$linha[1]." - ".$linha[2]."</option>";
						}					
						?>
					 </select>
				 </td>
				 <td class="cell3" id="data_lista"><?php echo $dt_lista_entrega; ?></td>
				 <td class="cell6" id="entregador"><?php echo $entregador; ?></td>
				 <td class="cell4" onclick="alteraDados(this)" id="l_alteracao"><span class="glyphicon glyphicon-pencil text-primary"></span></td>
				 <td class="cell4" onclick="retirahawb(this)" id="l_remocao"><span class="glyphicon glyphicon-remove text-danger"></span></a></td>
			  </tr>
			  <?php
			}
			?>
		  </tbody>
	   </table>
	   <div style="width:1153px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:100px;margin-top:0px;">
	   
		   <div style="width:85px;height:40px;float:left;position:relative;margin-left:7px;margin-top:6px;">
			   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
		   </div>
		   <div id="qtdade_hawb" style="width:300px;height:25px;float:left;position:relative;margin-left:300px;margin-top:13px;text-align:center;">
				<font face="arial" size="3" color="#ffffff"><b>Quantidade de HAWBs :</b><?php echo $total_hawb;?></font>
		   </div>
		   <div style="width:85px;height:40px;float:left;position:relative;margin-left:80px;margin-top:6px;">
			   <button type="Submit" name="imp_lista" id="imp_lista" class="btn btn-primary">Imprime a lista</button>
		   </div>
		   <div style="width:80px;height:40px;float:left;position:relative;margin-left:210px;margin-top:5px;">
			  <a href="#?acao=1&tabela=<?php echo $tabela;?>"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Inclui</button></a>
		   </div>
	   </div> 
	   <div class="input-group" id="cod_barras">
			 <?php if(!isset($codi_barra)) {
			   $codi_barra ='';
			 }
			 ?>
			 <span class="input-group-addon">Cod. Barras</span>
			 <input type="text" name="codi_barra" class="form-control" id="codi_barra" placeholder=""  onChange="salva(this)">
			 <script language="JavaScript">
				 document.getElementById('codi_barra').focus()
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
   </form>
   <script type="text/javascript">
 	    $('input#txt_consulta').quicksearch('table#tab_entra tbody tr');
	   
	    //Função submete o form após digitar o campo numero hawb
	    function salva(campo){
           cadastro.submit();
        }
	   
	   function alteraDados(botao){
		   /*inicializando o array com os dados selecionados*/
		   var dados=[];
		   
		   /*Dados obtidos das td pre-preenchidas*/
	       $(botao).closest("tr").find("td:not(:last-child)").map(function(){
               dados.push($(this).text().trim());
           }).get();
		   
		   /*deixa no arrey somente com o numero do conteole*/
		   dados.splice(1);
		   
		   /*adiciona ao array o codigo do entregador*/
		   $(botao).closest("tr").find("select").map(function(){
               dados.push($(this).val());
           }).get();
		     
		   //Pegando os valores do array alterado
		   contro     = dados[0];
		   nu_lista   = dados[1];
		   
		   //alert(contro+' - '+nu_lista);
		   
		   $.ajax({
              type: "POST",
              url: "altera_gravacao_lista_entrega.php",
              data:{'controle':contro,'lista':nu_lista},
              success: function (result) {
				 var div = document.querySelector("#mensagem_s");
				 div.style.display = "block";
                 $('#mensagem_s').html(result).show();
				 setTimeout(function(){ 
                     cadastro.submit();
                 }, 1000);
              },
              error: function (result) {
				  var div = document.querySelector("#mensagem_n");
				  div.style.display = "block";
                  $('#mensagem_n').html(result).show();
              }
           });  
	   }
	   
	   function retirahawb(linha){
		   /*inicializando o array com os dados selecionados*/
		   var campos=[];
		   
		   /*Dados obtidos das td pre-preenchidas*/
	       $(linha).closest("tr").find("td:not(:last-child)").map(function(){
               campos.push($(this).text().trim());
           }).get();
		   
		   /*deixa no arrey somente com o numero do conteole*/
		   campos.splice(1);
		   
		   //Pegando o valor do array alterado
		   contro     = campos[0];
		   
		   //alert(contro);
		   
		   $.ajax({
              type: "POST",
              url: "retira_hawb_lista_entrega.php",
              data:{'controle':contro},
              success: function (result) {
				 var div = document.querySelector("#mensagem_s");
				 div.style.display = "block";
                 $('#mensagem_s').html(result).show();
				 setTimeout(function(){ 
                     cadastro.submit();
                 }, 1000);
              },
              error: function (result) {
				  var div = document.querySelector("#mensagem_n");
				  div.style.display = "block";
                  $('#mensagem_n').html(result).show();
              }
           }); 
	   }
	   
	   /* Função para limpar a barra de endereço após serem recuperados os gets */
	   
	   if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://localhost/N_entregas/altera_lista_entrega.php");
	   }
	   
   </script>
</body>
</html>