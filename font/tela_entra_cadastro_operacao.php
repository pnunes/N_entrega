<?php 
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
?>
<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/estilo_pg_entra_cadastro_operacao.css">
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
	   
	   // Pega o cpf do usuario da memoria 
	   $cpf_usuario = $_SESSION['cpf_m'];
	   
	   //Pega o nome do USUARIO da variavel global, que é mostrado no cabeçalho da pagina
	   $nome_usu  = $_SESSION['nome_m'];
	   
	   //Chama a rotina que monta o cabeçalho da pagina
       require_once('cabeca_paginas_internas.php');   
   ?>
   <form name="cadastro" id="cadastro" align="center" action="" method="post">
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
						<div id="impressora">
						    <a href='imprime_documento_fpdf.php?codigo=$var1&tabela=$tabela&titulo_doc=$titulo_doc&documento=procuracao.php'><span class="glyphicon glyphicon-print text-primary" id="impre"></span></a>
						</div>
			         </div>
				 </td>
			  </tr>
			  <tr id="cabe_tab">
				  <td class="cell1 cabe_td"><?php echo $cab_1;?></td>
				  <td class="cell2 cabe_td"><?php echo $cab_2;?></td>
				  <?php if ($cab_3 <> '') { ?>
					 <td class="cell2 cabe_td"><?php echo $cab_3;?></td>
				  <?php } ?>
				  <?php if ($cab_4 <> '') { ?>
					 <td class="cell3 cabe_td"><?php echo $cab_4;?></td>
				  <?php  } ?>
				  <?php if ($cab_5 <> '') { ?>
					 <td class="cell3 cabe_td"><?php echo $cab_5;?></td>
				  <?php  } ?>
				  <td class="cell4 cabe_td">Altera</td>
				  <td class="cell4 cabe_td">Exclui</td>
			  </tr>
		  </thead>
		  <tbody>
			<?php
			//Verifica as permissões definidas para o usuario - buscando na tabela PERMISSÕES.
			
			// Permissão para INCLUSÃO de dados
			$permi_i  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$pi'");
			$ok_permi = mysqli_num_rows($permi_i);
			if($ok_permi > 0){
				$permissao_i = 'S';
			} else {
				$permissao_i = 'N';
			}
			
			// Permissão para ALTERAÇÃO de dados
			$permi_a = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$pa'");
			$ok_perma = mysqli_num_rows($permi_a);
			if($ok_perma > 0){
				$permissao_a = 'S';
			} else {
				$permissao_a = 'N';
			}
			
			// Permissão para ALTERAÇÃO de dados
			$permi_e = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$pe'");
			$ok_perme = mysqli_num_rows($permi_e);
			if($ok_perme > 0){
				$permissao_e = 'S';
			} else {
				$permissao_e = 'N';
			}
			
			//Chama a rotina que recebe o codigo da rotina sql e trata a mesma para mostrar no gride
	        require_once('trata_script_sql.php');
			//echo "<p> Script :".$script_sql;
			if($script_sql<>''){
				$query = mysqli_query($cone,$script_sql) or die (mysqli_errno($cone)." - ".mysqli_error($cone));
				$total = mysqli_num_rows($query);
				
				for($i=0; $i<$total; $i++) {
				   $dados = mysqli_fetch_row($query);
				   $var1       = $dados[0];
				   $var2       = $dados[1];
				   if(isset($dados[2])) {
					  $var3    = $dados[2];
				   }
				   if(isset($dados[3])) {
					  $var4    = $dados[3];
				   }
				   if(isset($dados[4])) {
					  $var5    = $dados[4];
				   }
				  ?>
				  <tr>
					 <td class="cell1"> <?php echo $var1;?> </td>
					 <td class="cell2"> <?php echo $var2;?> </td>
					 <?php if (isset($dados[2])) { ?>
						<td class="cell2"><?php echo $var3;?></font></td>
					 <?php } ?>
					 <?php if (isset($dados[3])) { ?>
						<td class="cell3"><?php echo $var4;?></font></td>  
					 <?php } ?>
					 <?php if (isset($dados[4])) { ?>
						<td class="cell3"><?php echo $var5;?></font></td>  
					 <?php } ?>
					 <?php if($permissao_a == 'S'){ ?>
					    <td class="cell4"><a href='altera_exclui_hawb.php?codigo=<?php echo $var1;?>&acao=2&tabela=<?php echo $tabela;?>'><span class="glyphicon glyphicon-pencil text-primary"></span></a></td>
					 <?php } ?>
					 <?php if($permissao_e == 'S'){ ?>
					    <td class="cell4"><a href='altera_exclui_hawb.php?codigo=<?php echo $var1;?>&acao=3&tabela=<?php echo $tabela;?>'><span class="glyphicon glyphicon-remove text-danger"></span></a></td>
					 <?php } ?>
				  </tr>
				  <?php
				}
			}
			?>
		  </tbody>
	   </table>
   </form>
   <div style="width:1055px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:150px;margin-top:0px;">
   
	   <div style="width:85px;height:40px;float:left;position:relative;margin-left:7px;margin-top:6px;">
		   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
	   </div>
	   
	   <div id="mensagem" style="width:300px;height:25px;float:left;position:relative;margin-left:300px;margin-top:13px;text-align:center;">
			<font face="arial" size="3" color="#ffffff"><b>Total de HAWBs :</b><?php echo $total;?></font>
	   </div>
	   <div style="width:80px;height:40px;float:left;position:relative;margin-left:283px;margin-top:5px;">
	      <?php if($permissao_i == 'S'){ ?>
		      <a href="inclusao_hawb_h.php?acao=1&tabela=<?php echo $tabela;?>"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Inclui</button></a>
		  <?php } ?>
	   </div>
   </div>
   <script>
 	   $('input#txt_consulta').quicksearch('table#tab_entra tbody tr');
   </script>
</body>
</html>