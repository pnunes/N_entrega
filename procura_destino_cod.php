<?php
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
    
	require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');
	
	// Executa a função retorna, chamada no GET abaixo, para realizar a pesquisa
	function retorna($cod_desti, $cone){
	    // Cria o select para localizar o registro
		$resultado = "SELECT nome_desti,cnpj_desti,cep_desti,rua_desti,numero_desti,
	    comple_desti,bairro_desti,cidade_desti,estado_desti,classe_cep 
	    FROM destino WHERE codigo_desti = '$cod_desti' LIMIT 1";
		
		//Executa o select e joga o resultado na variavel dados
		$dados = mysqli_query($cone, $resultado);
	    
		//Verifica se foi localizado o registro e joga os dados na variavel valores
	    if($dados->num_rows){
		    $row = mysqli_fetch_assoc($dados);
		    $valores['nome_desti']   = $row["nome_desti"];
			$valores['cnpj_desti']   = $row["cnpj_desti"];
			$valores['cep_desti']    = $row["cep_desti"];
			$valores['rua_desti']    = $row["rua_desti"];
			$valores['numero_desti'] = $row["numero_desti"];
			$valores['comple_desti'] = $row["comple_desti"];
			$valores['bairro_desti'] = $row["bairro_desti"];
			$valores['cidade_desti'] = $row["cidade_desti"];
			$valores['estado_desti'] = $row["estado_desti"];
			$valores['classe_cep']   = $row["classe_cep"];
	    }else{
		    $valores['nome_desti'] = 'Destino não localizado!';
	    }
	    return json_encode($valores);
    }

    // recebe o codigo do destino enviado pela função javascript 
    if(isset($_GET['codigo'])){
	    echo retorna($_GET['codigo'], $cone);
    }
?>