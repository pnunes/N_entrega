<?php
	//restaura o valor da variavel $script_sql para não dar erro ao voltar de um inclui, altera ou exclui 
	if (isset($_SESSION['script_sql_m'])){
	   $script_sql = $_SESSION['script_sql_m'];
	}
	else {
	   $script_sql ='';
	}
	
	//restaura o valor da variavel $codigo_sql para não dar erro ao voltar de um inclui, altera ou exclui
	if(isset($_SESSION['codigo_sql_m'])) {
	   $codigo_sql = $_SESSION['codigo_sql_m'];
	}
	else {
	   $codigo_sql ='';
	}
	
	//echo "<p> Script: ".$script_sql;
	//echo "<p> Codigo :".$codigo_sql;
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	//Pega o script da SQL da tabela SQL_PADRAO
	$sql = "SELECT escript_sql FROM sql_padrao WHERE codigo='$codigo_sql'";
	$query_sql = mysqli_query($cone,$sql) or die (mysqli_errno($con)." - ".mysqli_error($con));
	$total_sql = mysqli_num_rows($query_sql);

	for($i=0; $i<$total_sql; $i++){
	  $dados = mysqli_fetch_row($query_sql);
	  $script_sql  = $dados[0];
	  $_SESSION['script_sql_m'] = $script_sql; 
	}
	
	//adiciona ao script de pesquisa o codigo do USUARIO ou do GRUPO, de acordo com o if anterior
	if(isset($_SESSION['script_sql_m'])){
		if($codigo_sql=='USU_RES') {
		   $script_sql = $_SESSION['script_sql_m']."WHERE vinculo=".$codigo_pesq_usu;
		}
		if($codigo_sql=='SQL_USU') {
		   $script_sql = $_SESSION['script_sql_m']."AND servico_usuario.codi_usu=".$codigo_pesq_usu;
		}
		if($codigo_sql=='SQL_ASO') {
			if($_SESSION['vinculo_m']<>''){
			   $script_sql = $_SESSION['script_sql_m']." WHERE vinculo=".$codigo_pesq_usu;
			}
			else {
			   $script_sql = $_SESSION['script_sql_m']." WHERE nu_oab=".$codigo_pesq_usu;
			}
		}
		if($codigo_sql=='SQL_TCL') {
			if($_SESSION['vinculo_m']<>''){
			   $script_sql = $_SESSION['script_sql_m']." WHERE advo_respo=".$codigo_pesq_usu;
			}
			else {
			   $script_sql = $_SESSION['script_sql_m']." WHERE advo_respo=".$codigo_pesq_usu;
			}
		}
		if($codigo_sql=='DOC_USU') {
			if($_SESSION['vinculo_m']<>''){
			   $script_sql = $_SESSION['script_sql_m']." AND vinculo=".$codigo_pesq_usu;
			}
			else {
			   $script_sql = $_SESSION['script_sql_m']." AND nu_oab=".$codigo_pesq_usu;
			}
		}
		if($codigo_sql=='OAB_REG') {
			if($_SESSION['vinculo_m']<>''){
			   $script_sql = $_SESSION['script_sql_m'].$codigo_pesq_usu;
			}
			else {
			   $script_sql = $_SESSION['script_sql_m'].$codigo_pesq_usu;
			}
		}
	}
	else {
		$script_sql ='';
	}

?>