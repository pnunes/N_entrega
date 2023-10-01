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
    
	////////////////////////////////////////////PEGA DADOS DA HAWB PARA REGISTRAR NO ARQUIVO HISTORICO_HAWB////////////////////
    $pega_dados ="SELECT n_hawb,cod_barra FROM movimento WHERE controle='$controle'";
    $query_dados = mysqli_query($cone,$pega_dados) or die ("Não foi possivel acessar o banco 1");
	$achou_d = mysqli_num_rows($query_dados);
	for($ic=0; $ic<$achou_d; $ic++){
		  $mostra = mysqli_fetch_row($query_dados);
		  $n_hawb     = $mostra[0];
		  $codi_barra = $mostra[1];
	}

	$baixando = "UPDATE movimento SET dt_baixa='$dt_baixa',dt_entrega='$dt_entrega',hr_entrega='$hr_entrega',
    ocorrencia='$ocorrencia',estatus_hawb='BIP3',recebedor='$recebedor',documento='$documento',entregador='$entregador' 
	WHERE controle='$controle'";
	if (mysqli_query($cone,$baixando)) {
		$resp_grava = 'Dados de baixa da HAWB gravados com sucesso!';  
		
		/////////////////////////////////Atualiza a tabela de histórico HAWB com registro alteração de lista de entrega da HAWB //////////////////
										  
	    $usuario = $_SESSION['cpf_m'];
	    $ocorrencia ='03';
	   
	    $atualiza="INSERT INTO historico_hawb (n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro)
        VALUES('$n_hawb','$codi_barra','$ocorrencia','$dt_entrega','$usuario','$hr_entrega')";
	    mysqli_query($cone,$atualiza);
		
	} else {
		$resp_grava = 'Problemas na gravação da baixa da HAWB! Verifique.';
	}
	echo(json_encode($resp_grava));
?>