<?php
   if ( session_status() !== PHP_SESSION_ACTIVE ) {
    session_start();
   }
   echo "Cheguei aqui sem problemas";
   require_once('Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');

   $codigo = $_GET['codigo'];

   $exclui = "DELETE FROM estrutura_tabela WHERE controle='$codigo'";
   $exclui_campo = mysqli_query($cone,$exclui);
   if($exclui_campo){
       $resp_grava = 'Campo excluidocom sucesso';
       $_SESSION['mensagem'] = $resp_grava;
   }else {
       $resp_grava = 'Problema na exclusão do campo! Verifique.';
       $_SESSION['mensagem'] = $resp_grava; 
   }
   header("location: CriaTabela.php");
?>