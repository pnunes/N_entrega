<?php 
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
    require_once('Conexao.php');
    $conn = new Conexao();
    $cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');
	
	//Recebe o numero que define qual arquivo de estilo (CSS) será utilizado
    if(isset($_GET['estilo'])) {
	   $estilo =$_GET['estilo'];
	   $_SESSION['estilo_m'] = $_GET['estilo']; 
    }
    else {
	   $estilo = $_SESSION['estilo_m'];
    }
?>

<html lang="pt-br">
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <?php
   if ($estilo == '1') {
   ?>
      <link rel="stylesheet" type="text/css" href="css/estilo_pg_entra_cadastro_2.css">
   <?php
   }
   ?>
   <?php
   if ($estilo == '2') {
   ?>
      <link rel="stylesheet" type="text/css" href="css/estilo_pg_entra_cadastro_3.css">
   <?php
   }
   ?>
   
   <link rel="stylesheet" href="css/bootstrap.min.css"> 
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.min.js"></script> 
   <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
   <script src="js/bootstrap-datepicker.min.js"></script> 
   <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script>

   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <script>
 	  $('input#i_busca').quicksearch('div.cell2');
   </script>
</head>
<body style="background-color:#ffffff;">
   
   <?php
       //Chama a rotina que recebe os parametros passados pela rotina entrada.php
	   require_once('recebe_parametros_entrada.php');
	   
	   //Pega o nome do USUARIO da variavel global, que é mostrado no cabeçalho da pagina
	   $nome_usu  = $_SESSION['nome_m'];
	   
	   //Chama a rotina que monta o cabeçalho da pagina
       require_once('cabeca_paginas_internas.php'); 
	   
	   ?>
	  		   
       <form name="cadastro" id="cadastro" align="center" action="" method="post"> 
            <div id="cabecalho"><?php echo $titulo_form;?></div>	   
    		<div class="d0">
				<div class="input-group ps-7" id="busca">
					  <div id="navbar-search-autocomplete" class="form-outline">
						  <input type="text" id="i_busca" class="form-control" />
					  </div>
					  <button type="button" class="btn btn-primary" id="bt_busca">
						<i class="fas fa-search">Buscar</i>
					  </button>
				</div>
                <div class="cell1"><?php echo $cab_1;?></div>
                <div class="cell2"><?php echo $cab_2;?></div>
				<?php if($cab_3<>'') {?>
				  <div class="cell3"><?php echo $cab_3;?></div>
				<?php }?>
				<?php if($cab_4<>'') {?>
				   <div class="cell2"><?php echo $cab_4;?></div>
				<?php }?>
                <div class="cell4">Altera.</div>
			    <div class="cell4">Exclui.</div>
				<?php if($imprime == 'S') { ?>
				   <div class="cell4">Impri.</div>
				<?php } ?>
            </div>		
			<div class="d1">
				  <?php
				    //Chama a rotina que recebe o codigo da rotina sql e trata a mesma para mostrar no gride
	                require_once('trata_script_sql.php');
	   
					if($script_sql<>''){
					  // echo "<p>Script :".$script_sql;
					   $query = mysqli_query($cone,$script_sql) or die (mysqli_errno($cone)." - ".mysqli_error($cone));
					   $total = mysqli_num_rows($query);

					   for($i=0; $i<$total; $i++){
						   $dados = mysqli_fetch_row($query);
						   $var1       = $dados[0];
						   $var2       = $dados[1];
						   if(isset($dados[2])) {
						      $var3       = $dados[2];
						   }
						   if(isset($dados[3])) {
						      $var4       = $dados[3];
						   }
						   $idLinha = "linha$i";
						   echo "<div class='d2'>";
						      echo "<div class='cell1'><font face=\"arial\" size=\"2\">$var1</font></div>";
						      echo "<div class='cell2'><font face=\"arial\" size=\"2\">$var2</font></div>";
						      if(isset($dados[2])) {
						         echo "<div class='cell3'><font face=\"arial\" size=\"2\">$var3</font></div>";
						      }
						      if(isset($dados[3])) {
							     echo "<div class='cell2'><font face=\"arial\" size=\"2\">$var4</font></div>";  
						      }
						      echo "<div class='cell4'><a href='cria_formulario.php?codigo=$var1&acao=2&tabela=$tabela'><span class=\"glyphicon glyphicon-pencil text-primary\"></span></a></div>";
						      echo "<div class='cell4'><a href='cria_formulario.php?codigo=$var1&acao=3&tabela=$tabela'><span class=\"glyphicon glyphicon-remove text-danger\"></span></a></div>";
                              if($imprime == 'S') {
							     echo "<div class='cell4'><a href='imprime_documento_fpdf.php?codigo=$var1&tabela=$tabela&titulo_doc=$titulo_doc&documento=procuracao.php'><img src=\"img/impre_azul.jpg\" border=\"none\"></a></div>"; 
						      }
						   echo"</div>"; 
				       }
                    }					   
				?>          
			</div>
	   </form>
	   <?php if($estilo =='1') { ?>
	   <div style="width:700px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:320px;margin-top:290px;border: 1px solid #4682B4;">
       <?php } ?>
	   <?php if($estilo =='2') { ?>
	   <div style="width:1050px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:150px;margin-top:323px;border: 1px solid #4682B4;">
       <?php } ?>
		   <div style="width:20px;height:20px;float:left;position:relative;margin-left:5px;margin-top:6px;">
			  <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
		   </div>
		   <div id="mensagem" style="width:300px;height:25px;float:left;position:relative;margin-left:350px;margin-top:10px;text-align:center;">
			   <font face="arial" size="3" color="#ffffff"><?php echo "$resp_grava";?></font>
			</div>
		   <?php if($estilo =='1') { ?>
		   <div style="width:20px;height:20px;float:left;position:relative;margin-left:619;margin-top:-28px;">
		   <?php } ?>
		   <?php if($estilo =='2') { ?>
		   <div style="width:20px;height:20px;float:left;position:relative;margin-left:295;margin-top:5px;">
		   <?php } ?>
		      <a href="cria_formulario.php?acao=1&tabela=<?php echo $tabela;?>"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Inclui</button></a>
		   </div>
	   </div>
	   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
       <script src="js/bootstrap.min.js"></script>
</body>
</html>

