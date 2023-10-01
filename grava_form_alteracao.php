<?php
    //Esta rotina recebe parametros da rotina cria_form_formulario_altera_exclui.php 
	//e prepara os parametros para passar para a classe classe CrudMysqli para a alteração de registro
	
	
	$campos    = $_SESSION['campos_m'];
   // echo "<p>Conteudo variavel campos :".$campos;
	//explode a variavel campos para pegar o nome de cada campo para contruir o $_POST
	$conteudo_campos = explode(",",$campos);
	//var_dump($conteudo_campos);
	//Conta quantos elementos tem para serem POSTADOS 
	$conta_campo = count($conteudo_campos);
	//Pelo fato do explode gerar um array, cuja contagem começa do 0 tenho que diminuir 1 do toral de campos.
	$conta_campo = $conta_campo -1;
   // print_r($conteudo_campos);
	$conta_campo;
	//Este FOR prepara as variaveis com o conteudo necessario para fazer UPDATE na tabela
	$conteudo_update='';
	for($i=1; $i <= $conta_campo; $i++) {
		
		//verifica se é o ultimo campo para montar o sql com virgula ou sem virgula no final
		if($i < ($conta_campo)) {
			//verifica se trata-se de um campo tipo data para mudar o formato adequado pra gravação na tabela - yyyy-mm-dd
			
			if(substr($conteudo_campos[$i],0,4) == 'data') {
				$data_i = $_POST[$conteudo_campos[$i]];
				if($data_i =='00/00/0000') {
				   $data_i = '01/01/1000';
				}
				$data = explode("/",$data_i);
				$data_grava = $data[2]."-".$data[1]."-".$data[0];
				$conteudo_update.=$conteudo_campos[$i]."="."'".$data_grava."'".",";
			}
			else {
				$conteudo_update.=$conteudo_campos[$i]."="."'".$_POST[$conteudo_campos[$i]]."'".",";
			}  
		}
		else	{
			//verifica se trata-se de um campo tipo data para mudar o formato adequado pra gravação na tabela - yyyy-mm-dd
			
			if(substr($conteudo_campos[$i],0,4) == 'data') {
				$data_i = $_POST[$conteudo_campos[$i]];
				if($data_i =='00/00/0000') {
				   $data_i = '01/01/1000';
				}
				$data = explode("/",$data_i);
				$data_grava = $data[2]."-".$data[1]."-".$data[0];
				$conteudo_update.=$conteudo_campos[$i]."="."'".$data_grava."'";
			}
			else {
				$conteudo_update.=$conteudo_campos[$i]."="."'".$_POST[$conteudo_campos[$i]]."'";
			}		   
		}
	} 
	$tabela         = $_SESSION['tabela_m'];
	$campo_pesquisa = $_SESSION['c_indice_m'];
	$chave_pesquisa = $_SESSION['chave_pesquisa_m'];
	
	//echo "<p>Contuedo script :".$conteudo_update;
	//echo "<p>Tabela :".$tabela;
    //echo "<p>Campo P :".$campo_pesquisa;
	//echo "<p>Chave P :".$chave_pesquisa;
	
    if($chave_pesquisa <>'') {
	   $condicao =$campo_pesquisa.'='."'".$chave_pesquisa."'";
       //echo "<p>Condição :".$condicao;
       // Chama a classe crud
	   require_once('CrudMysqli.php'); 
	   //Instancia a classe crud
	   $crudi   = new CrudMysqli('',$tabela,$conteudo_update,$condicao,'');
	   $altera = $crudi->alterar();
	}else {
	   // Chama a classe crud
	   require_once('CrudMysqli.php'); 
	   //Instancia a classe crud
	   $crudi   = new CrudMysqli('',$tabela,$conteudo_update,'','');
	   $altera = $crudi->alterar();
	}
	
	//Carrega a variavel $resp_grava com o resultado do metodo alterar da classe CrudMysqli para mostrar na tela
	if($altera == '1A') {
	   $cor_msg = 'S';
	   $resp_grava = 'Atualizado feita com sucesso!';
    }
    if($altera == '2A') {
	   $cor_msg = 'D';
	   $resp_grava = 'Problemas na atualização! Verifique.';
    }  
?>