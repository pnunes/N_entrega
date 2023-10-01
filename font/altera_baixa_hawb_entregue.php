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
	
	if(!isset($dt_ini)){
		$dt_ini ='';
	}
	
	if(!isset($dt_fim)){
		$dt_fim ='';
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
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
   <!-- <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
	
	<link rel="stylesheet" type="text/css" href="css/estilo_altera_baixa_hawb_entregue.css">
	<?php
	
	//Chama a rotina que recebe os parametros passados pela rotina entrada.php
	require_once('recebe_parametros_entrada.php');
	?>
  </head>
<body>
   <?php
       require_once('Conexao.php');
       $conn = new Conexao();
       $cone = $conn->Conecta();
	   mysqli_set_charset($cone,'UTF8');
	   
	   //Pega o nome do USUARIO da variavel global, que é mostrado no cabeçalho da pagina
	   $nome_usu  = $_SESSION['nome_m'];
	   
	   //Chama a rotina que monta o cabeçalho da pagina
       require_once('cabeca_paginas_internas.php'); 

       switch (get_post_action('datas')) {
		  case 'datas':
		      $dt_ini      = $_POST['data_inicio'];
			  
			  // formatando data para pesquisar na tabela
		      $data_ini    = explode("/",$dt_ini);
			  $data_inicio = $data_ini[2].$data_ini[1].$data_ini[0];
			  
		      $dt_fim     = $_POST['data_fim'];
			  
			  // formatando data para pesquisar na tabela
		      $data_fim    = explode("/",$dt_fim);
			  $data_final   = $data_fim[2].$data_fim[1].$data_fim[0];
			  
			  //colocando as data de pesquisa numa variavel global.
			  $_SESSION['data_inicio_m'] = $data_inicio;
			  $_SESSION['data_fim_m']    = $data_final;
		  break;
		  default:
	   } 
	   
   ?>
   <form name="cadastro" id="cadastro" align="center" action="" method="post">
        <table id="tb_datas">
		   <tr>
			  <td id="t_form" colspan="2"><?php echo $titulo_form;?></td>
		   </tr>
		   <tr>
		      <td id="data_pesquisa">
				<div class="input-group" id="c_dt_inicio">
				   <?php if(isset($_SESSION['data_ini_m'])) {
					   $data_lista = $_SESSION['data_ini_m'];
				   } else {
					   $data_lista = date('d/m/Y');
				   }?>
				   <span class="input-group-addon">Data Inicio</span>
				   <div class="input-group date">
						<input type="text" class="form-control" id="data_inicio" value="<?php echo $dt_ini;?>" name="data_inicio">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
				   </div>
				   <script type="text/javascript">
						$('#data_inicio').datepicker({	
						format: "dd/mm/yyyy",	
						language: "pt-BR",
						/*startDate: '+0d',*/
					});
				   </script>
				</div>				
			    <div class="input-group" id="c_dt_fim">
				   <?php if(isset($_SESSION['data_fim_m'])) {
					   $data_lista = $_SESSION['data_fim_m'];
				   } else {
					   $data_lista = date('d/m/Y');
				   }?>
				   <span class="input-group-addon">Data fim</span>
				   <div class="input-group date">
						<input type="text" class="form-control" id="data_fim" value="<?php echo $dt_fim;?>" name="data_fim">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
				   </div>
				   <script type="text/javascript">
						$('#data_fim').datepicker({	
						format: "dd/mm/yyyy",	
						language: "pt-BR",
						/*startDate: '+0d',*/
					});
				   </script>
				</div>	
			    <button type="Submit" id="bt_data" name="datas" class="btn btn-primary">Informa Periodo</button>
		     </td>
		  </tr>
	    </table>
        <div id="mensagem">
			<div class="alert alert-success" role="alert" id="mensagem_s"></div>
			<div class="alert alert-danger" role="alert" id="mensagem_n"></div>
	    </div>		
	    <table id="tab_entra" class="table-wrapper">
		  <thead id="cabe_tab">
		      <tr id="cabecalho">
				<td colspan="2"><?php echo $titulo_form;?></td>
			  </tr>
			  <tr>
			     <td colspan="2">
                     <div class="form-group input-group" id="div_busca">
				        <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				        <input name="consulta" id="txt_consulta" placeholder="Procurar" type="text" class="form-control">
						<script language="JavaScript">
					        document.getElementById('txt_consulta').focus()
			            </script>
						<div id="impressora">
						    <a href='imprime_documento_fpdf.php?codigo=$var1&tabela=$tabela&titulo_doc=$titulo_doc&documento=procuracao.php'><span class="glyphicon glyphicon-print text-primary" id="impre"></span></a>
						</div>
			         </div>
				 </td>
			  </tr>
			  <tr id="cabe_tab">
				  <td class="cell1 cabe_td"><?php echo $cab_1;?></td>
				  <td class="cell2 cabe_td"><?php echo $cab_2;?></td>
				  <td class="cell3 cabe_td"><?php echo $cab_3;?></td>
				  <td class="cell3 cabe_td"><?php echo $cab_4;?></td>
				  <td class="cell4 cabe_td"><?php echo $cab_5;?></td>
				  <td class="cell5 cabe_td"><?php echo $cab_6;?></td>
				  <td class="cell5 cabe_td"><?php echo $cab_7;?></td>
				  <td class="cell6 cabe_td"><?php echo $cab_8;?></td>
				  <td class="cell5 cabe_td"><?php echo $cab_9;?></td>
				  <td class="cell4 cabe_td">Grava</td>
			  </tr>
		  </thead>
		  <tbody>
			<?php
			if(isset($_SESSION['data_inicio_m'])){
			   $data_inicio = $_SESSION['data_inicio_m']; 	
			}else {
			   $data_inicio ='';
			}
			
			if(isset($_SESSION['data_fim_m'])){
			   $data_final = $_SESSION['data_fim_m']; 	
			}else {
			   $data_final ='';
			}
			
			$seleciona_hawb ="SELECT controle,cod_barra,dt_baixa,dt_entrega,hr_entrega,entregador,recebedor,documento,ocorrencia FROM movimento 
			WHERE dt_baixa <> '1000-01-01' AND dt_baixa BETWEEN '$data_inicio' AND '$data_final'
			ORDER BY dt_baixa";
			
			$query_hawb = mysqli_query($cone,$seleciona_hawb) or die (mysqli_errno($cone)." - ".mysqli_error($cone));
			$total_hawb = mysqli_num_rows($query_hawb);
			
			for($i=0; $i<$total_hawb; $i++) {
			   $dados = mysqli_fetch_row($query_hawb);
			   $controle    = $dados[0];
			   $cod_barra   = $dados[1];
			   $dt_baixa    = $dados[2];
			   $dt_entrega  = $dados[3];
			   $hr_entrega  = $dados[4];
			   $entregador  = $dados[5];
			   $recebedor   = $dados[6];
			   $documento   = $dados[7];
               $ocorrencia  = $dados[8];			   
			  ?>
			  <tr>
				 <td class="cell1" align="left" id="controle"> <?php echo $controle;?> </td>
				 <td class="cell2" align="left"> <?php echo $cod_barra;?> </td>
				 <td class="cell3"><input name="dt_baixa" id="data_ba" type="date" class="cell3" value="<?php echo $dt_baixa; ?>"></td>
				 <td class="cell3"><input name="dt_entrega" id="data_entre" type="date" class="cell3" value="<?php echo $dt_entrega; ?>"></td>
				 <td class="cell4"><input name="hr_entrega" id="hora_entre" type="time" class="cell4" value="<?php echo $hr_entrega; ?>"></td>  
				 <!--<td class="cell5"><input name="entregador" type="text" class="cell5"></td>-->
				 <td class="cell5">
				     <select id="entregador" name="entregador" class="form-control cell5">
						<option value="">Selecione</option>
						<?php
						$sql2 = "SELECT cnpj_cpf,nome FROM pessoa WHERE ativo='S' AND categoria='02'";
						$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
						while ( $linha = mysqli_fetch_array($resul)) {
							$select = $entregador == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
						}					
						?>
					</select>
				 </td>
                 <td class="cell5"><input name="recebedor" id="recebe" type="text" class="cell5" value="<?php echo $recebedor; ?>"></td>
				 <td class="cell6"><input name="documento" id="docume" type="text" class="cell6" value="<?php echo $documento; ?>"></td>
				 <td class="cell5">
				     <select id="cd_ocorrencia" name="cd_ocorrencia" class="form-control cell5">
						<option value="">Selecione</option>
						<?php
						$sqloc = "SELECT codigo,descricao FROM ocorrencia";
						$resuloc = mysqli_query($cone,$sqloc) or die ("Não foi possivel acessar o banco");
						while ( $linha = mysqli_fetch_array($resuloc)) {
							$select = $ocorrencia == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
						}					
						?>
					 </select>
				 </td>
				 <td class="cell4" onclick="enviaDadosBaixa(this)" id="removeLinha"><span class="glyphicon glyphicon-pencil text-primary"></span></td>
			  </tr>
			  <?php
			}
			?>
		  </tbody>
	   </table>
   </form>
   <div style="width:1155px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:100px;margin-top:0px;">
   
	   <div style="width:85px;height:40px;float:left;position:relative;margin-left:7px;margin-top:6px;">
		   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
	   </div>
	   
	   <div id="qtdade_hawb" style="width:300px;height:25px;float:left;position:relative;margin-left:300px;margin-top:13px;text-align:center;">
			<font face="arial" size="3" color="#ffffff"><b>Quantidade de HAWBs :</b><?php echo $total_hawb;?></font>
	   </div>
	   <div style="width:80px;height:40px;float:left;position:relative;margin-left:380px;margin-top:5px;">
		  <a href="#?acao=1&tabela=<?php echo $tabela;?>"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Inclui</button></a>
	   </div>
   </div>
   <script type="text/javascript">
 	   $('input#txt_consulta').quicksearch('table#tab_entra tbody tr');
	   
	   function enviaDadosBaixa(botao){
		   /*inicializando o array com os dados selecionados*/
		   var dados=[];
		   
		   /*Dados obtidos das td pre-preenchidas*/
	       $(botao).closest("tr").find("td:not(:last-child)").map(function(){
               dados.push($(this).text().trim());
           }).get();
		   var dados = dados.filter(function (i) {
              return i;
           });
		   /*Dados obtidos dos selects*/
		   $(botao).closest("tr").find("select").map(function(){
               dados.push($(this).val());
           }).get();
		   
		   /*Dados obtidos dos inputs*/
		   $(botao).closest("tr").find("input").map(function(){
               dados.push($(this).val());
           }).get();
		    
		   /*retira elementos desnecessarios do array para deixa-lo pronto para uso*/
		   dados.splice(1,3);
		   
	       /* os dados finais integrante do array estaão na seguinte ordem: controle, entregador, ocorrencia, data_baixa,
		   data_entrega, recebedor e documento*/
		   
		   //Pegando os valores do array criado
		   contro       = dados[0];
		   entregador   = dados[1];
		   ocorrencia   = dados[2];
		   dt_baixa     = dados[3];
		   dt_entrega   = dados[4];
		   hora_entrega = dados[5];
		   recebedor    = dados[6];
		   documento    = dados[7];
		       
		  //alert(contro+' - '+dt_baixa+' - '+entregador+' - '+dt_entrega+' - '+hora_entrega+' - '+recebedor+' - '+documento+' - '+ocorrencia);
		   
		   $.ajax({
              type: "POST",
              url: "altera_gravacao_baixa_hawb.php",
              data:{'controle':contro,'data_baixa':dt_baixa,'data_entre':dt_entrega,'h_entrega':hora_entrega,
			  'recebe':recebedor,'docume':documento,'ocorre':ocorrencia,'entregador':entregador},
              success: function (result) {
				 var div = document.querySelector("#mensagem_s");
				 div.style.display = "block";
                 $('#mensagem_s').html(result).show();
				 setTimeout(function(){ 
                     cadastro.submit();
                 }, 800);
              },
              error: function (result) {
				  var div = document.querySelector("#mensagem_n");
				  div.style.display = "block";
                  $('#mensagem_n').html(result).show();
              }
          });  
	   }
   </script>
</body>
</html>