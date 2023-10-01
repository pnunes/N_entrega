<?php 
    require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');

	$cliente = $_REQUEST['cliente'];
	
	$result_servi = "SELECT DISTINCT tabela_preco.tipo_servi, servico.descri_se
	FROM tabela_preco,servico
	WHERE ((tabela_preco.tipo_servi=servico.codigo_se)
	AND (tabela_preco.ativo='S')
	AND  (tabela_preco.codi_cli='$cliente'))";
	
	$resultado_servico= mysqli_query($cone, $result_servi);
	
	while ($row_servi = mysqli_fetch_assoc($resultado_servico) ) {
		$servico_sele[] = array(
			'tipo_servi'	=> $row_servi['tipo_servi'],
			'descri_se'     => utf8_encode($row_servi['descri_se']),
		);
	}
	
	echo(json_encode($servico_sele));
