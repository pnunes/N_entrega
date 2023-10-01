<?php
require_once('Conexao.php');
$conn = new Conexao();
$cone = $conn->Conecta();
mysqli_set_charset($cone,'UTF8');

function retorna($no_destino, $cone){
	$localiza = "SELECT * FROM destino WHERE nome_desti = '$no_destino' LIMIT 1";
	$resultado = mysqli_query($cone, $localiza);
	if($resultado->num_rows){
		$row_regi = mysqli_fetch_assoc($resultado);
		$valores['cod_desti']     = $row_regi['codigo_desti'];
		$valores['cep_desti']     = $row_regi['cep_desti'];
		$valores['cl_cep']        = $row_regi['classe_cep'];
		$valores['rua_desti']     = $row_regi['rua_desti'];
		$valores['nume_desti']    = $row_regi['numero_desti'];
		$valores['comple_desti']  = $row_regi['comple_desti'];
		$valores['bairro_desti']  = $row_regi['bairro_desti'];
		$valores['cidade_desti']  = $row_regi['cidade_desti'];
		$valores['estado_desti']  = $row_regi['estado_desti'];
	}else{
		$valores['no_destino'] = 'Aluno não encontrado';
	}
	return json_encode($valores);
}

if(isset($_GET['no_destino'])){
	echo retorna($_GET['no_destino'], $cone);
}
?>