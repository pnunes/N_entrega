<?php
	
	echo 'Estou aqui na pagina!';
	
	//Conecta com o banco de dados
	/*require_once('Conexao.php');
	$conn = new Conexao();
	$cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');*/
		
	// recebe as variaveis enviadas pela rotina altera_lista_entrega.php
	/*if(isset($_POST['controle'])) {
	   $controle = $_POST['controle'];
	} else {
	   $controle = '';
	}

	/*if(isset($_GET['data_baixa'])) {
	   $dt_baixa = $_GET['dtata_baixa'];
	} else {
	   $dt_baixa = '';
	}
	
    $data_baixa   = explode("/",$dt_baixa);
    $v_data_baixa = $data_baixa[2]."-".$data_baixa[1]."-".$data_baixa[0];
	
	if(isset($_GET['data_entrega'])) {
	   $dt_entrega = $_GET['data_entrega'];
	} else {
	   $dt_entrega = '';
	}

    $data_entrega   = explode("/",$dt_entrega);
    $v_$dt_entrega = $data_entrega[2]."-".$data_entrega[1]."-".$data_entrega[0];

	if(isset($_GET['h_entrega'])) {
	   $hr_entrega = $_GET['h_entrega'];
	} else {
	   $hr_entrega = '';
	}

	if(isset($_GET['recebe'])) {
	   $recebedor = $_GET['recebe'];
	} else {
	   $recebedor = '';
	}

	if(isset($_GET['docume'])) {
	   $documento = $_GET['docume'];
	} else {
	   $documento = '';
	}

	if(isset($_GET['ocorre'])) {
	   $ocorrencia = $_GET['ocorre'];
	} else {
	   $ocorrencia = '';*/
	}
    echo "<p>Controle :".$controle;
/*
$baixando = "UPDATE movimento SET dt_baixa='$v_data_baixa',dt_entrega='$v_$dt_entrega',hr_entrega='$hr_entrega',
ocorrencia='$ocorrencia',estatus_hawb='BIP3',recebedor='$recebedor',documento='$documento' WHERE controle='$controle'";
if (mysqli_query($cone,$alteracao)) {
    $cor_msg = 'S';
	$resp_grava = 'HAWB retirada da lista com sucesso!';  
} else {
    $cor_msg = 'D';
	$resp_grava = 'Problemas ao retirar a HAWB da lista! Verifique.';
}
//echo(json_encode($resp_grava));

header("location: baixa_hawb_entregue.php?resposta=".$resp_grava."&cor=".$cor_msg);
*/	
?>