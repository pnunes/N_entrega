<?php
      		
	   /////////////////////////////////////////////////////////////////////////////////////////////
       //Inicializa a variavel usada para passar mensagens do sistema - MENSAGEM   
	   if(!isset($resp_grava)) {
           $resp_grava=''; 
       }
	   if(isset($_GET['resposta'])) {
	      $resp_grava = $_GET['resposta'];
	   }
	   else {
	      $resp_grava ='';
	   }
	   
	   //Pega variavel que identifica qual parte do sistema esta sendo usado e mostra seu conteudo como titulo da tela - MODULO
       if(isset($_GET['modulo'])){
		   $modulo = $_GET['modulo'];
		   $_SESSION['modulo_m'] = $_GET['modulo'];
	   }else {
		   $modulo ='';
	   }
	   
	   //Recebe o titulo do gride que mostrara os registros da tabela e imprime no cabeçalho do gride - TITULO GRIDE ENTRADA
	   if(isset($_GET['titulo'])) {
		   $titulo_form         =$_GET['titulo'];
		   $_SESSION['titulo_m'] = $_GET['titulo'];
	   }
	   else {
		   if(isset($_SESSION['titulo_m'])) {
		     $titulo_form =$_SESSION['titulo_m'];
		   }
	   }
	   
	   //Recebe numero de campos que comporão a tela da tabela de entrada dos cadastros - QUANTIDADE CAMPOS GRIDE ENTRADA
	   if(isset($_GET['qtdc'])) {
		   $qtdc =$_GET['qtdc'];
		   $_SESSION['qtdc_m'] = $_GET['qtdc']; 
	   }
	   else {
		   if(isset($_SESSION['qtdc_m'])){
		      $qtdc = $_SESSION['qtdc_m'];
		   }else {
			  $qtdc = ''; 
		   }
	   }
	   
	   /////////////////////////////////////////////////////////////////////////////////////////////
	   //Recebendo variaveis que trazem informações para cabeçalho da tabela - CABEÇALHO DO GRIDE DE ENTRADA
	   
	   if(isset($_GET['cab_1'])) {
	      $cab_1 = $_GET['cab_1'];
		  $_SESSION['cab_1_m'] = $_GET['cab_1'];
	   }
	   else {
	      $cab_1 ='';
	   }
       if(isset($_GET['cab_2'])) {
	      $cab_2 = $_GET['cab_2'];
		  $_SESSION['cab_2_m'] = $_GET['cab_2'];
	   }
	   else {
	      $cab_2 ='';
	   }
       if(isset($_GET['cab_3'])) {
	      $cab_3 = $_GET['cab_3'];
		  $_SESSION['cab_3_m'] = $_GET['cab_3'];
	   }
	   else {
	      $cab_3 ='';
	   }
	   if(isset($_GET['cab_4'])) {
	      $cab_4 = $_GET['cab_4'];
		  $_SESSION['cab_4_m'] = $_GET['cab_4'];
	   }
	   else {
	      $cab_4 ='';
	   }
	   if(isset($_GET['cab_5'])) {
	      $cab_5 = $_GET['cab_5'];
		  $_SESSION['cab_5_m'] = $_GET['cab_5'];
	   }
	   else {
	      $cab_5 ='';
	   }
	   if(isset($_GET['cab_6'])) {
	      $cab_6 = $_GET['cab_6'];
		  $_SESSION['cab_6_m'] = $_GET['cab_6'];
	   }
	   else {
	      $cab_6 ='';
	   }
	   if(isset($_GET['cab_7'])) {
	      $cab_7 = $_GET['cab_7'];
		  $_SESSION['cab_7_m'] = $_GET['cab_7'];
	   }
	   else {
	      $cab_7 ='';
	   }
	   if(isset($_GET['cab_8'])) {
	      $cab_8 = $_GET['cab_8'];
		  $_SESSION['cab_8_m'] = $_GET['cab_8'];
	   }
	   else {
	      $cab_8 ='';
	   }
	   if(isset($_GET['cab_9'])) {
	      $cab_9 = $_GET['cab_9'];
		  $_SESSION['cab_9_m'] = $_GET['cab_9'];
	   }
	   else {
	      $cab_9 ='';
	   }
	   if(isset($_SESSION['cab_1_m'])){
		  $cab_1 =$_SESSION['cab_1_m']; 
	   }
	   if(isset($_SESSION['cab_2_m'])){
		  $cab_2 =$_SESSION['cab_2_m']; 
	   }
	   if(isset($_SESSION['cab_3_m'])){
		  $cab_3 =$_SESSION['cab_3_m']; 
	   }
	   if(isset($_SESSION['cab_4_m'])){
		  $cab_4 =$_SESSION['cab_4_m']; 
	   }   
	   if(isset($_SESSION['cab_5_m'])){
		  $cab_5 =$_SESSION['cab_5_m']; 
	   }
	   if(isset($_SESSION['cab_6_m'])){
		  $cab_6 =$_SESSION['cab_6_m']; 
	   }
	   if(isset($_SESSION['cab_7_m'])){
		  $cab_7 =$_SESSION['cab_7_m']; 
	   }
	   if(isset($_SESSION['cab_8_m'])){
		  $cab_8 =$_SESSION['cab_8_m']; 
	   }
	   if(isset($_SESSION['cab_9_m'])){
		  $cab_9 =$_SESSION['cab_9_m']; 
	   }
	   
	   //Recebe o nome da tabela que sera usada - TABELA EM USO
	   if(isset($_GET['tabela'])) {
			$tabela               = $_GET['tabela'];
			$_SESSION["tabela_m"] = $_GET['tabela'];
	   }
	   else {
		   if(isset($_SESSION['tabela_m'])) {
			  $tabela  = $_SESSION['tabela_m'];
		   }
	   }
	   
	   //Pega variavel que contem o codigo da SQL que devera ser usada para manusear os dados - SCRIPT SQL
	   if(isset($_GET['codigo_sql'])) {
		   $codigo_sql =$_GET['codigo_sql'];
		   $_SESSION['codigo_sql_m'] = $_GET['codigo_sql'];
	   }
	   else {
		   $codigo_sql='';
	   }
	   //echo "<p>".$codigo_sql;
	  
	   //Pega variavel que contem o titulo do relatorio, quando ouver impressão de relatório - CABEÇALHO RELATORIO
	   if(isset($_GET['titulo_doc'])) {
		   $titulo_doc =$_GET['titulo_doc'];
		   $_SESSION['titulo_doc_m'] = $_GET['titulo_doc'];
	   } else {
		   if(isset($_SESSION['titulo_doc_m'])){
		      $titulo_doc = $_SESSION['titulo_doc_m'];
		   } else {
			  $titulo_doc =''; 
		   }
	   }
	   
	   //Variavel que define o estilo do gride de entrada - TIPOS: APENAS DUAS COLUNAS OU MAIS DE DUAS COLUNAS
	   if(isset($_GET['estilo'])) {
		   $estilo =$_GET['estilo'];
		   $_SESSION['estilo'] = $_GET['estilo'];
		   
		   if($estilo == '2' ) {
		      if(isset($_SESSION['cab_3_m'])) {
		         $cab_3 =$_SESSION['cab_3_m'];
              }
              if(isset($_SESSION['cab_4_m'])){		   
		         $cab_4 =$_SESSION['cab_4_m']; 
              }
           }			  
	   } else {
	      if(isset($_SESSION['estilo'])){
		     $estilo = $_SESSION['estilo'];
	      }else {
		     $estilo =''; 
	      }
       }
	   //Recebe parametro para a orientação de tela da entrada de POD - ESTILO DA TELA
	   if(isset($_GET['sentido'])){
		   $sentido =$_GET['sentido'];
		   $_SESSION['sentido'] = $_GET['sentido']; 
	   } else {
		   if(isset($_SESSION['sentido'])){
		      $sentido = $_SESSION['sentido'];
		   }
	   }
	   
		//Pega variavel que identifica função do formulario - FUNÇÃO DO FORMULARIO
		if(isset($_GET['titulo_form'])){
			$titulo_form = $_GET['titulo_form'];
			$_SESSION['titulo_form_m'] = $_GET['titulo_form'];
		}else {
			if (isset($_SESSION['titulo_form_m'])){
				$titulo_form = $_SESSION['titulo_form_m'];
			}
		}
		
		//Pega a variavel que determina para qual rotina deve voltar - ROTINA DE RETORNO
		if(isset($_GET['volta'])){
			$progra = $_GET['volta'];
			$_SESSION['volta_m'] = $_GET['volta'];
		}else {
			if (isset($_SESSION['volta_m'])){
				$progra = $_SESSION['volta_m'];
			}
		}
     
		//Pega a variavel que determina permissões para acessar as rotinas do sistema

		//Inclusão
		if(isset($_GET['CI'])){
			$pi = $_GET['CI'];
		    //retira espaços brancos do codigo da rotina - IMPORTANTE
			$pi = str_replace(' ', '', $pi);
			$_SESSION['ci_m'] = $pi;
		}
		else {
			$pi = $_SESSION['ci_m'];
		}
		
		//Alteração
		if(isset($_GET['CA'])){
			$pa = $_GET['CA'];
			//retira espaços brancos do codigo da rotina - IMPORTANTE
			$pa = str_replace(' ', '', $pa);
			$_SESSION['ca_m'] = $pa;
		}
		else {
			$pa = $_SESSION['ca_m'];
		}
		
		//Exclusão
		if(isset($_GET['CE'])){
			$pe = $_GET['CE'];
			//retira espaços brancos do codigo da rotina - IMPORTANTE
			$pe = str_replace(' ', '', $pe);
			$_SESSION['ce_m'] = $pe;
		}
		else {
			$pe = $_SESSION['ce_m'];
		}
?>