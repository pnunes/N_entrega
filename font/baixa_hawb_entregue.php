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
	if(!isset($radio_b)){
		$radio_b='';
	}
	if(!isset($total_hawb)){
		$total_hawb=0;
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
	<link rel="stylesheet" type="text/css" href="css/estilo_baixa_hawb_entregue.css">
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

       switch (get_post_action('sele_box')) {
		  case 'sele_box':
		       $radio_b = $_POST['lista'];
			   $_SESSION['radio_b_m'] = $radio_b;
	      break;
		  default:
	   }
	   
   ?>
   <form name="cadastro" id="cadastro" align="center" action="" method="post">
       <div id="mensagem">
			<div class="alert alert-success" role="alert" id="mensagem_s"></div>
			<div class="alert alert-danger" role="alert" id="mensagem_n"></div>
	   </div>		
	   <table id="tab_entra" class="table-wrapper">
		  <thead id="cabe_tab">
		      <tr id="linha_cab">
				<td colspan="2">
				    <div id="tit_form"><?php echo $titulo_form;?></div>
					<div id="div_x_hawb">
						<div id="lie">
						  <input type="radio" name="lista" id="lista" name="hawb" value="em_lista">
						  <label for="lista" class="legen">Em Lista</label>
						</div>
						<div id="lif">
						  <input type="radio" name="lista" id="fora_l" name="hawb" value="fora_l">
						  <label for="fora_l" class="legen">Fora Lista</label>
						</div>
						<div id="lit">
						  <input type="radio" name="lista" id="todas" name="hawb" value="todas">
						  <label for="todas" class="legen">Todas</label>
						</div>
						<div id="botao">
						  <button type="Submit" id="sele_box" name="sele_box" class="btn btn-primary">Selecione</button>
						</div>
					</div>
				</td>
			  </tr>
			  <tr>
			     <td colspan="10">
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
				  <td class="cell4 cabe_td">Baixa</td>
			  </tr>
		  </thead>
		  <tbody>
			<?php
			if(isset($_SESSION['radio_b_m'])) {
				$radio_b = $_SESSION['radio_b_m'];
			}
			if($radio_b == 'todas'){
			   //mostra todas as hawbs
			   $seleciona_hawb ="SELECT controle,cod_barra,entregador,ocorrencia FROM movimento WHERE dt_entrega='1000-01-01'";
			}	
			if($radio_b == 'em_lista'){
				//mostra so hawbs em lista de entrega
			    $seleciona_hawb ="SELECT controle,cod_barra,entregador,ocorrencia FROM movimento WHERE dt_entrega='1000-01-01' AND nu_lista_entrega <> ''";
			}
			if($radio_b == 'fora_l'){
				//Mostra so hawbs fora de lista de entrega
			    $seleciona_hawb ="SELECT controle,cod_barra,entregador,ocorrencia FROM movimento WHERE dt_entrega='1000-01-01' AND nu_lista_entrega = ''";
			}
						
			if($radio_b <> ''){
				$query_hawb = mysqli_query($cone,$seleciona_hawb) or die (mysqli_errno($cone)." - ".mysqli_error($cone));
				$total_hawb = mysqli_num_rows($query_hawb);
				
				for($i=0; $i<$total_hawb; $i++) {
				   $dados = mysqli_fetch_row($query_hawb);
				   $controle    = $dados[0];
				   $cod_barra   = $dados[1];
				   $entregador  = $dados[2];
				   $ocorrencia  = $dados[3];			   
				  ?>
				  <tr class="linha">
					 <td class="cell1 inpe" align="left" id="controle"> <?php echo $controle;?> </td>
					 <td class="cell2 inpe" align="left"> <?php echo $cod_barra;?> </td>
					 <td class="cell3"><input name="dt_baixa" id="data_ba" type="date" class="cell3 inpe"></td>
					 <td class="cell3"><input name="dt_entrega" id="data_entre" type="date" class="cell3 inpe"></td>
					 <td class="cell4"><input name="hr_entrega" id="hora_entre" type="time" class="cell4 inpe"></td>  
					 <td class="cell5">
						 <select id="entregador" name="entregador" class="form-control cell5 inpe">
							<option value="">Selecione</option>
							<?php
							$sql2 = "SELECT cnpj_cpf,nome FROM pessoa WHERE ativo='S' AND categoria='02' OR categoria='04' ";
							$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
							while ( $linha = mysqli_fetch_array($resul)) {
								$select = $entregador == $linha[0] ? "selected" : "";
								echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
							}					
							?>
						</select>
					 </td>
					 <td class="cell5"><input name="recebedor" id="recebe" type="text" class="cell5 inpe"></td>
					 <td class="cell6"><input name="documento" id="docume" type="text" class="cell6 inpe"></td>
					 <td class="cell5">
						 <select id="cd_ocorrencia" name="cd_ocorrencia" class="form-control cell5 inpe">
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
					 <td class="cell4" id="removeLinha" onClick="enviaDadosBaixa(this)" name="botao"><span class="glyphicon glyphicon-pencil text-primary"></span></td>
				  </tr>
				  <?php
				}
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
              url: "grava_baixa_hawb.php",
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
				  /*setTimeout(function(){ 
                     cadastro.submit();
                  }, 500);*/
              }
          });  
	   }
   </script>
</body>
</html>