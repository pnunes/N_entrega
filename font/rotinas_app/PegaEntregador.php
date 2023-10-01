<?php
  
   require_once('../Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   $cnpjcpf = $_POST['entregador'];
   
   $busca_entregador = "SELECT cnpj_cpf,nome,usuario,senha FROM pessoa WHERE ativo = 'S' and cnpj_cpf = '$cnpjcpf'";

   $query_oc = mysqli_query($cone,$busca_entregador);
   $total_oc = mysqli_num_rows($query_oc);
   
   if($total_oc > 0){
	  while ($dado = mysqli_fetch_assoc($query_oc)){ 
         $response[] = array(
			'cnpjcpf' => $dado['cnpj_cpf'],
			'nome'    => utf8_encode($dado['nome']),
			'usuario' => $dado['usuario'],
			'senha'   => $dado['senha']);
      } 
   }else {
	 $response["mensagem"] = "Tabela está vazia! Verifique.";
   }
   mysqli_close($cone);
   echo json_encode($response);
   
?>