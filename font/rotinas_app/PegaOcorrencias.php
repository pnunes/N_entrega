<?php
  
   require_once('../Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   
   $busca_ocorrencia = "SELECT codigo,descricao FROM ocorrencia ORDER BY codigo";

   $query_oc = mysqli_query($cone,$busca_ocorrencia);
   $total_oc = mysqli_num_rows($query_oc);
   
   if($total_oc > 0){
	  while ($dado = mysqli_fetch_assoc($query_oc)){ 
         $response[] = array(
			'codigo'	=> $dado['codigo'],
			'descricao' => utf8_encode($dado['descricao']),
		 );
      } 
   }else {
	 $response["mensagem"] = "Tabela está vazia! Verifique.";
   }
 
   mysqli_close($cone);
   echo json_encode($response);
?>