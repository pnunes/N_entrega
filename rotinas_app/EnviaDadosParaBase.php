<?php
  
   require_once('../Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   date_default_timezone_set('America/Sao_Paulo');
   
   if(isset($_POST['dados'])) {
      $dados  = $_POST['dados']; 
	  
	  $dados = json_decode($dados, true);
	  
	  $total = count($dados);
	  
	  $conta = 0;
	  foreach($dados as $value) {
		 foreach($value as $i=>$valor){ 
		    if($i == "n_hawb"){
				$n_hawb = $valor;
				$pega_entregador = "SELECT entregador,cod_barra,tentativa_entrega FROM movimento WHERE n_hawb = '$n_hawb'";
				$query_entrega = mysqli_query($cone,$pega_entregador) or die ("Nao foi possivel acessar o banco");
				$total = mysqli_num_rows($query_entrega);
				if($total > 0){
				   for($ic=0; $ic<$total; $ic++){
					  $row = mysqli_fetch_row($query_entrega);
			          $entregador       = $row[0];
					  $cod_barra        = $row[1];
					  $tenta_entrega    = $row[2];
				   }
				}else {
					$entregador ='ENTREGADOR';
					$cod_barra = $n_hawb;
				} 
				$tenta_entrega = $tenta_entrega+1;
			} 
			//Registrando baixa na tabela movimento
            $recebe_hawb = "UPDATE movimento SET $i = '$valor' WHERE n_hawb = '$n_hawb'";
			mysqli_query($cone,$recebe_hawb);
			
			//pegando dados para atualizar a tabela historico_hawb
			if($i == "ocorrencia"){
				$ocorrencia = $valor;
			}
			if($i == "dt_entrega"){
                $dt_evento = $valor;			    	
			}
			if($i == "hr_entrega"){
                $hora_registro = $valor;			    	
			}
			
		 }
         
		 //Atualiza tentativa_entrega na tabela movimento
		 $nume_entrega = "UPDATE movimento SET tentativa_entrega = '$tenta_entrega' WHERE n_hawb = '$n_hawb'";
		 mysqli_query($cone,$nume_entrega);
		 
		 //Insere o registro de baixa da hawb na tabela historico HAWB
		 $atua_historico ="INSERT INTO historico_hawb (n_hawb,ocorrencia,dt_evento,usuario,hora_registro,cod_barra)
		 VALUES ('$n_hawb','$ocorrencia','$dt_evento','$entregador','$hora_registro','$cod_barra')";
		 mysqli_query($cone,$atua_historico);
		  
		 $conta++;
	  }
	  if($conta == $total) {
	     $response = "Registros gravados com sucesso!";
	     mysqli_close($cone);
	     echo json_encode($response);
      }else {
		 $response = "Problemas na gravacao dos registros! Verifique :";
		 mysqli_close($cone);
	     echo json_encode($response);
	  }
   } else {
	 
	   $response = "Dados não foram recebeidos! Verifique.!";
	   mysqli_close($cone);
	   echo json_encode($response); 
   }
?>