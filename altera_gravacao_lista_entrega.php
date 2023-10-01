<?php
  //Conecta com o banco de dados
	require_once('Conexao.php');
	$conn = new Conexao();
	$cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');
	
	// recebe as variaveis enviadas pela rotina altera_gravacao_lista_entrega.php
	if(isset($_POST['controle'])) {
	   $controle = $_POST['controle'];
	} else {
	   $controle = '';
	}
	
	if(isset($_POST['lista'])) {
	   $nu_lista = $_POST['lista'];
	} else {
	   $nu_lista = '';
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
	
	// pega a data da lista da nova lista selecionada para colocar no registro alterado
	
	$pega_dt_lista ="SELECT DISTINCT dt_lista_entrega,entregador FROM movimento WHERE nu_lista_entrega = '$nu_lista'";
	$query_p = mysqli_query($cone,$pega_dt_lista) or die ("Não foi possivel acessar o banco 1");
	$total_p = mysqli_num_rows($query_p);
    for($ip=0; $ip<$total_p; $ip++){
	   $mostra_p = mysqli_fetch_row($query_p);
	   $dt_lista_en   = $mostra_p[0];
	   $entrega       = $mostra_p[1];
    }
	
	$alterando = "UPDATE movimento SET dt_lista_entrega='$dt_lista_en',entregador='$entrega',nu_lista_entrega='$nu_lista' 
	WHERE controle='$controle'";
	if (mysqli_query($cone,$alterando)) {
		$resp_grava = 'Alteracao da lista de entrega feita com sucesso!'; 
		
		/////////////////////////////////Atualiza a tabela de histórico HAWB com registro alteração de lista de entrega da HAWB //////////////////
										  
	    $usuario = $_SESSION['cpf_m'];
	    $ocorrencia ='09';
	    $hora = date('H:i');
	    $data = date('Y-m-d');
	 
	    $atualiza="INSERT INTO historico_hawb (n_hawb,cod_barra,ocorrencia,dt_evento,usuario,hora_registro)
        VALUES('$n_hawb','$codi_barra','$ocorrencia','$data','$usuario','$hora')";
	    mysqli_query($cone,$atualiza);

		
	} else {
		$resp_grava = 'Problemas na alteracao da lista de entrega! Verifique.';
	}
	echo(json_encode($resp_grava));
?>