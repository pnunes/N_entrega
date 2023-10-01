<?php
  //Conecta com o banco de dados
	require_once('Conexao.php');
	$conn = new Conexao();
	$cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');
	
	// recebe as variaveis enviadas pela rotina altera_lista_entrega.php
	if(isset($_POST['controle'])) {
	   $controle = $_POST['controle'];
	} else {
	   $controle = '';
	}
	
	if(isset($_POST['data_baixa'])) {
	   $dt_baixa = $_POST['data_baixa']; 
	} else {
	   $dt_baixa = '';
	}
	
	if(isset($_POST['data_entre'])) {
	   $dt_entrega = $_POST['data_entre'];
	} else {
	   $dt_entrega = '';
	}

	if(isset($_POST['h_entrega'])) {
	   $hr_entrega = $_POST['h_entrega'];
	} else {
	   $hr_entrega = '';
	}

	if(isset($_POST['recebe'])) {
	   $recebedor = $_POST['recebe'];
	} else {
	   $recebedor = '';
	}

	if(isset($_POST['docume'])) {
	   $documento = $_POST['docume'];
	} else {
	   $documento = '';
	}

	if(isset($_POST['ocorre'])) {
	   $ocorrencia = $_POST['ocorre'];
	} else {
	   $ocorrencia = '';
	}
	if(isset($_POST['entregador'])) {
	   $entregador = $_POST['entregador'];
	} else {
	   $entregador = '';
	}
    
	$alterando = "UPDATE movimento SET dt_baixa='$dt_baixa',dt_entrega='$dt_entrega',hr_entrega='$hr_entrega',
    ocorrencia='$ocorrencia',estatus_hawb='BIP3',recebedor='$recebedor',documento='$documento',entregador='$entregador' 
	WHERE controle='$controle'";
	if (mysqli_query($cone,$alterando)) {
		$resp_grava = 'Dados de alteracao de baixa da HAWB gravados com sucesso!';  
	} else {
		$resp_grava = 'Problemas na gravacao dos dados de alteracao da baixa da HAWB! Verifique.';
	}
	echo(json_encode($resp_grava));
?>