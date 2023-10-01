<?php
if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
 }

// recebe ocodigo do cliente selecionado enviado pela função pegacli() javascript
if(isset($_POST["codigo"])) {
    $codi_cli = $_POST["codigo"];
    $_SESSION['codi_cli_m'] = $codi_cli;
    // imprime na tela em formato json
    echo json_encode(
	    array(
		   "codigo" => $codi_cli 
	    )
    );
}; 
?>