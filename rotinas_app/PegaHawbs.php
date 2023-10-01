<?php
  
   require_once('../Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   if(isset($_POST['cnpjcpf'])) {
	   
       $cnpj_cpf = $_POST['cnpjcpf'];
   
	   $busca_hawb = "SELECT n_hawb,nome_desti,rua_desti,numero_desti,bairro_desti,cidade_desti,dt_entrega 
	   FROM movimento WHERE entregador = '$cnpj_cpf' AND dt_entrega = '1000-01-01'";

	   $query_h = mysqli_query($cone,$busca_hawb);
	   $total_h = mysqli_num_rows($query_h);
	   
	   if($total_h > 0){
		  while ($dado = mysqli_fetch_assoc($query_h)){ 
			 $response[] = array(
				'nhawb'   => $dado['n_hawb'],
				'nome'    => utf8_encode($dado['nome_desti']),
				'rua'     => utf8_encode($dado['rua_desti']),
				'numero'  => $dado['numero_desti'],
				'bairro'  => $dado['bairro_desti'],
				'cidade'  => $dado['cidade_desti'],
				'dtentrega'  => $dado['dt_entrega']);
		  } 
	   }else {
		 $response["mensagem"] = "Tabela está vazia! Verifique.";
	   }
	   mysqli_close($cone);
	   echo json_encode($response);
   }
?>