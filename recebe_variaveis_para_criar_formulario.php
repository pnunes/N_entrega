<?php
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Recebe o titulo do formulario
	if(isset($_GET["titulo"])) {
	   $titulo_form =$_GET["titulo"];
    }
    else {
	   $titulo_form ='';
    }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Pega o titulo da tela caso venha por GET
    if(isset($_GET["titulo_doc"])) {
		$titulo_form =$_GET["titulo_doc"];
		$_SESSION['titulo_m'] = $titulo_form;
	 }
	 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Recebe o codigo para pesquisa do registro quando se tratar de alteração ou exclusão
	
	if(isset($_GET['codigo'])) {
	    $chave_pesquisa =	$_GET['codigo'];
		$_SESSION['pesquisa_m'] = $_GET['codigo'];
	}else {
		$chave_pesquisa =	'';
		$_SESSION['pesquisa_m'] = '';
	}
	
	//echo "<p>Codigo - chave pesquisa :".$chave_pesquisa;
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Recebe informação sobre o tipo de ação : inclusao, alteração ou exclusão
	if(isset($_GET['acao'])) {
	   $acao =	$_GET['acao'];
	   $_SESSION['acao_m'] = $_GET['acao'];
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Define o nome do botão do submit em função da ação determinada
	if(isset($acao)) {
		if($acao == 1) {
		   $nome_botao ='Incluir';	
		}
		if($acao == 2) {
		   $nome_botao ='Alterar';	
		}
		if($acao == 3) {
		   $nome_botao ='Excluir';	
		}
		$_SESSION['botao_m'] = $nome_botao;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Recebe o nome da tabela de onde vai pegar o nome, tipo e tamanho dos campos
	if(isset($_GET["tabela"])) {
	    $tabela = $_GET["tabela"];
        $_SESSION["tabela_m"] = $_GET["tabela"];		
	}
	//echo "<p>Tabela a usar :".$tabela;
	//Incicia a variavel de mensagens
    if(!isset($resp_grava)) {
       $resp_grava=''; 
    }
	
	// carrega a variavel para mensagem se houver conteudo para isso
	if(isset($_GET['resposta'])) {
	   $resp_grava = $_GET['resposta'];
	}
	else {
	   $resp_grava ='';
	}		
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Pega o nome do banco de dados da varivel global
	if(isset($_SESSION['banco_m'])) {
	   $banco  =$_SESSION['banco_m'];
	}
	
    //Pega o nome do unsuario das variaveis globais
	if(isset($_SESSION['nome_m'])){
		$nome_usu    = $_SESSION['nome_m'];
	}else{
		$nome_usu    ='NOME USUARIO NÃO LOCALIZADO';
	}
	
	
	//Pega o nome do formulario das variveis globais
	if(isset($_SESSION['titulo_m'])) {
	    $titulo_form = $_SESSION['titulo_m'];
	}else {
		$titulo_form = 'NOME FORMULAIO NAO LOCALIZADO';
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Pega a variavel modulo enviada pela rotina entrada para imprimir na tela
	if(isset($_GET["modulo"])) {
		$modulo =$_GET["modulo"];
		$_SESSION['modulo_m'] = $modulo;
	 }

?>