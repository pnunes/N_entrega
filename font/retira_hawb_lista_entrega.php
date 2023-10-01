<?php
if ( session_status() !== PHP_SESSION_ACTIVE ) {
     session_start();
}

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

    ////////////////////////////////////////////PEGA DADOS DA HAWB PARA REGISTRAR NO ARQUIVO HISTORICO_HAWB////////////////////
    $pega_dados ="SELECT n_hawb,cod_barra FROM movimento WHERE controle='$controle'";
    $query_dados = mysqli_query($cone,$pega_dados) or die ("Não foi possivel acessar o banco 1");
	$achou_d = mysqli_num_rows($query_dados);
	for($ic=0; $ic<$achou_d; $ic++){
		  $mostra = mysqli_fetch_row($query_dados);
		  $n_hawb     = $mostra[0];
		  $codi_barra = $mostra[1];
	}
	   
	//////////////////////////////////////////////EXCLUI A HAWB DALISTA DE ENTREGA////////////////////////////////////////////////   
	$alteracao = "UPDATE movimento SET nu_lista_entrega='',dt_lista_entrega='1000-01-01',entregador='',tentativa_entrega='0',
	ocorrencia='01',estatus_hawb='BIP1' WHERE controle='$controle'";
	if (mysqli_query($cone,$alteracao)) {
		$resp_grava = 'Retirada da HAWB da lista com sucesso!'; 

          /////////////////////////////////Atualiza a tabela de histórico HAWB com registro da exclusão da HAWB //////////////////
										  
		  $usuario = $_SESSION['cpf_m'];
		  $ocorrencia ='07';
		  $hora = date('H:i');
		  $data = date('Y-m-d');
		 
		  $atualiza="INSERT INTO historico_hawb (n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro)
		  VALUES('$n_hawb','$codi_barra','$ocorrencia','$data','$usuario','$hora')";
		  mysqli_query($cone,$atualiza);
        		
	} else {
		$resp_grava = 'Problemas na retirada da HAWB da lista! Verifique.';
	}
	echo(json_encode($resp_grava));
?>