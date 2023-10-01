<?php
  // header('Content-Type: application/json charset=utf-8');
   
   $response = array();
   $response["erro"] = true;
   
   require_once('../Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   
   if($_POST['usuario'] and $_POST['senha']) {
	   
	     $response["erro"] = false;
	   
		 $usuario     = $_POST['usuario'];
		 $senha       = $_POST['senha'];
		
		 $declara = "SELECT cnpj_cpf,nome,usuario,senha FROM pessoa
		 WHERE usuario='$usuario' and senha='$senha' and ativo='S'";

		 $query = mysqli_query($cone,$declara) or die ("Nao foi possivel acessar o banco");
		 $total = mysqli_num_rows($query);
		 
		 if($total > 0){
			 $registro = mysqli_fetch_array($query);
			 $response["registros"] = $total;
			 $response["usuario"]   = $registro['usuario'];
			 $response["senha"]     = $registro['senha'];
			 $response["nome"]      = $registro['nome'];
			 $response["cnpjcpf"]  = $registro['cnpj_cpf'];
			 
			 $response["erro"]    = false;
		 }else {
		     $response["mensagem"] = "Usuario não localizado! Verifique.";
			 $response["erro"]    = true;
		 }
		 
		 mysqli_close($cone);
   }
   echo json_encode($response);
?>