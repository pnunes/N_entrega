<?php
    //Esta rotina recebe parametros da rotina cria_form_formulario_altera_exclui.php 
	//e prepara os parametros para passar para a classe classe CrudMysqli para a exclusão do registro

	$tabela         = $_SESSION['tabela_m'];
	$campo_pesquisa = $_SESSION['c_indice_m'];
	$chave_pesquisa = $_SESSION['chave_pesquisa_m'];

    //echo "<p>Tabela :".$tabela;
    //echo "<p>Campo Pesquisa :".$campo_pesquisa;
    //echo "<p>Chave Pesquisa :".$chave_pesquisa;

	$condicao =$campo_pesquisa.'='."'".$chave_pesquisa."'";
	// Chama a classe crud
	require_once('CrudMysqli.php'); 
	//Instancia a classe crud
	$crudi   = new CrudMysqli('',$tabela,'',$condicao,'');
	$exclui = $crudi->Excluir();
	
	//Carrega a variavel $resp_grava com o resultado do metodo alterar da classe CrudMysqli para mostrar na tela
	if($exclui == '1E') {
	   $cor_msg = 'S';
	   $resp_grava = 'Exclusão feita com sucesso!';
    }
    if($exclui == '2E') {
	   $cor_msg = 'D';
	   $resp_grava = 'Problemas na exclusão! Verifique.';
    }  
	
?>