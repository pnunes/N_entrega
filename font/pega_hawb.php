<?php
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
    
	require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    
    // recebe o numero da hawb enviado pela função javascript
    $n_hawb = $_POST['nume_hawb'];
	
    $verifica = "SELECT n_hawb FROM movimento WHERE n_hawb='$n_hawb'";
    $vquery = mysqli_query($cone,$verifica);
    if(($vquery) AND ($vquery->num_rows != 0 )){
	    echo 'HAWB já foi lançada no sistema! Verifique.';
    }
?>