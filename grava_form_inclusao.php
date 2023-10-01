<?php
    //Esta rotina recebe parametros da rotina cria_form_formulario_inclusao.php 
	//e prepara os parametros para passar para a classe classe CrudMysqli para a inclusão de registro



    //Constroi o post para cada campo para pegar os dados digitados
	$campos    = $_SESSION['campos_m'];
	//echo "<p>Campos :".$campos;
	//explode a variavel campos para pegar o nome de cada campo para contruir o $_POST
	$conteudo_campos = explode(",",$campos);
	//Conta quantos elementos tem para serem POSTADOS 
	$conta_campo = count($conteudo_campos);
	//print_r($conteudo_campos);

	$conteudo_post='';
	for($i=0; $i <= ($conta_campo -1); $i++) {
		
		//verifica se é o ultimo campo para montar o sql com virgula ou sem virgula no final
		if($i < ($conta_campo - 1)) {
			
			//verifica se trata-se de um campo tipo data para mudar o formato adequado pra gravação na tabela - yyyy-mm-dd
			if(substr($conteudo_campos[$i],0,4) == 'data') {
				$data_v = $_POST[$conteudo_campos[$i]];
				if($data_v<>''){
				   $data_n = explode("/",$data_v);
				   $data_grava = $data_n[2]."-".$data_n[1]."-".$data_n[0];
				   $conteudo_post.="'".$data_grava."'".",";
				}
			}
			else {
				$conteudo_post.="'".$_POST[$conteudo_campos[$i]]."'".","; 
			}
		}
		if($i == ($conta_campo - 1)){
			//verifica se trata-se de um campo tipo data para mudar o formato adequado pra gravação na tabela - yyyy-mm-dd
			if(substr($conteudo_campos[$i],0,4) == 'data') {
				$data_v = $_POST[$conteudo_campos[$i]];
				if($data_v<>''){
				   $data_n = explode("/",$data_v);
				   $data_grava = $data_n[2]."-".$data_n[1]."-".$data_n[0];
				   $conteudo_post.="'".$data_grava."'";
				}
			}
			else {
				$conteudo_post.="'".$_POST[$conteudo_campos[$i]]."'"; 
			}
		}		   
	}
	$campos         = $_SESSION['campos_m'];
	$tabela         = $_SESSION['tabela_m'];
	
	// Chama a classe crud
	require_once('CrudMysqli.php'); 
	//Instancia a classe crud
	$crudi   = new CrudMysqli($campos,$tabela,$conteudo_post,'','');
	$incluir = $crudi->Inserir();
	
	if($incluir == '1I') {
		$cor_msg = 'S';
		$resp_grava = 'Inclusão feita com sucesso!';
	}
	if($incluir == '2I') {
		$cor_msg = 'D';
		$resp_grava = 'Problemas na inclusão! Verifique.';
	}

?>