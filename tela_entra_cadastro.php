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
	<?php
	
	//Chama a rotina que recebe os parametros passados pela rotina entrada.php
	require_once('recebe_parametros_entrada.php');
	
	if ($estilo == '1') {
	?>
      <link rel="stylesheet" type="text/css" href="css/estilo_pg_entra_cadastro_2.css">
	<?php
	}
	if ($estilo == '2') {
	?>
      <link rel="stylesheet" type="text/css" href="css/estilo_pg_entra_cadastro_3.css">
	<?php
	}
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
					 <td class="cell3 cabe_td"><?php echo $cab_3;?></td>
				  <?php } ?>
				  <?php if ($cab_4 <> '') { ?>
					 <td class="cell3 cabe_td"><?php echo $cab_4;?></td>
				  <?php  } ?>
				  <td class="cell4 cabe_td">Altera</td>
				  <td class="cell4 cabe_td">Exclui</td>
			  </tr>
		  </thead>
		  <tbody>
			<?php
			
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
					
				  ?>
				  <tr>
					 <td class="cell1 itens_td"> <?php echo $var1;?> </td>
					 <td class="cell2 itens_td"> <?php echo $var2;?> </td>
					 <?php if (isset($dados[2])) { ?>
						<td class="cell3 itens_td"><font face="arial" size="2"><?php echo $var3;?></font></td>
					 <?php } ?>
					 <?php if (isset($dados[3])) { ?>
						<td class="cell3 itens_td"><font face="arial" size="2"><?php echo $var4;?></font></td>  
					 <?php } ?>
					 <td class="cell4 itens_td"><a href='cria_formulario.php?codigo=<?php echo $var1;?>&acao=2&tabela=<?php echo $tabela;?>'><span class="glyphicon glyphicon-pencil text-primary"></span></a></td>
					 <td class="cell4 itens_td"><a href='cria_formulario.php?codigo=<?php echo $var1;?>&acao=3&tabela=<?php echo $tabela;?>'><span class="glyphicon glyphicon-remove text-danger"></span></a></td>
				  </tr>
				  <?php
				}
			}
			?>
		  </tbody>
	   </table>
   </form>
   
   <?php if ($estilo == '1') { ?>
   <div style="width:705px;height:50px;background-color:#191970;vertical-align:middle !important;position:relative;margin-left:320px;margin-top:0px;border: 1px solid #4682B4;">
       <div style="width:80px;height:45px;float:left;position:relative;margin-left:5px;margin-top:6px;">
		   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
	   </div>
	   
	   <div id="mensagem" style="width:300px;height:35px;float:left;position:relative;margin-left:210px;margin-top:10px;text-align:center;">
			<font face="arial" size="3" color="#ffffff"><?php echo "$resp_grava";?></font>
	   </div>

	   <div style="width:80px;height:45px;float:right;position:relative;margin-right:74;margin-top:5px;">
		  <a href="cria_formulario.php?acao=1&tabela=<?php echo $tabela;?>"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Inclui</button></a>
	   </div>
    </div>
   <?php } ?>
   
   <?php if ($estilo == '2') { ?>
   <div style="width:1055px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:150px;margin-top:0px;">
   
	   <div style="width:85px;height:40px;float:left;position:relative;margin-left:5px;margin-top:6px;">
		   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
	   </div>
	   
	   <div id="mensagem" style="width:300px;height:25px;float:left;position:relative;margin-left:300px;margin-top:10px;text-align:center;">
			<font face="arial" size="3" color="#ffffff"><?php echo "$resp_grava";?></font>
	   </div>

	   <div style="width:80px;height:40px;float:left;position:relative;margin-left:285px;margin-top:5px;">
			<a href="cria_formulario.php?acao=1&tabela=<?php echo $tabela;?>"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Inclui</button></a>
	   </div>
   </div>
   <?php } ?>
   <script>
 	   $('input#txt_consulta').quicksearch('table#tab_entra tbody tr');
   </script>
</body>
</html>