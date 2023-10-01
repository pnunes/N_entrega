<?php
    
	require_once('Conexao.php');
	$conn = new Conexao();
	$cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');
    
    $tipo_cadastro	= $_GET['tipo_cada'];
	
	//Dados para gerar relatorio de entregadores
	if($tipo_cadastro == 'entregador'){
		 $nome_arquivo   = 'lista_entregadores.pdf';
		 $nome_relatorio = 'RELAÇÃO DE PRESTADORES DE SERVIÇOS - ENTREGADORES';  
		 $cabe_relatorio = 'CNPJ/CPF,NOME,CIDADE,CEP,UF,TELEFONE';
		 $campos_tabela  = 'cnpj_cpf,nome,cidade,cep,estado,telefone';
		 $tama_campo     = '100,210,150,50,50,100';
		 $tabela         = 'pessoa';
		 $condicao       = 'WHERE ativo ="S" AND categoria="04"';
	}
	
	//Dados para gerar relatorio de clientes
    if($tipo_cadastro == 'cliente'){
		 $nome_arquivo   = 'lista_clientes.pdf';
		 $nome_relatorio = 'RELAÇÃO DE CLIENTES';  
		 $cabe_relatorio = 'CNPJ/CPF,NOME,CIDADE,CEP,UF,TELEFONE';
		 $campos_tabela  = 'cnpj_cpf,nome,cidade,cep,estado,telefone';
		 $tama_campo     = '100,210,150,50,50,100';
		 $tabela         = 'pessoa';
		 $condicao       = 'WHERE ativo ="S" AND categoria="01"';
	}
	
	//Dados para gerar relatorio de ocorrencias
    if($tipo_cadastro == 'ocorrencia'){
		 $nome_arquivo   = 'lista_ocorrencias.pdf';
		 $nome_relatorio = 'RELAÇÃO DE OCORRÊNCIAS CADASTRADAS';  
		 $cabe_relatorio = 'CODIGO,DESCRIÇÃO,BIP,STATUS';
		 $campos_tabela  = 'codigo,descricao,tipo_bip,posicao';
		 $tama_campo     = '40,210,100,100';
		 $tabela         = 'ocorrencia';
		 $condicao       = '';
	}
	
	//Dados para gerar relatorio da tabela de preços
	if($tipo_cadastro == 'tab_preco'){
		 $nome_arquivo   = 'lista_preco.pdf';
		 $nome_relatorio = 'TABELA DE PREÇO';  
		 $cabe_relatorio = 'REGIÃO,SERVIÇO,CLIENTE,VR CLIENTE,VR TERCEIR.';
		 $campos_tabela  = 'nome_classe,(SELECT descri_se FROM servico WHERE codigo_se=tipo_servi),(SELECT nome FROM pessoa WHERE cnpj_cpf=codi_cli),valor_cli,valor_ter';
		 $tama_campo     = '150,150,210,100,100';
		 $tabela         = 'tabela_preco';
		 $condicao       = '';
	}	


	
   // var_dump($nome_relatorio,$cabe_relatorio,$campos_tabela,$tama_campo,$tabela,$condicao);
	  
	  //desmembra a variavel que tem o tamanho dos campos transformando num array para dar dois espaços entre as colunas do relatorio
	  $ta_campo   = explode(",",$tama_campo);
	 // var_dump($ta_campo);
	  //Pega o nome do relatorio e começa a contar o relarorio
	  $html='<table width=900>';
		  $html.='<thead style="font-family: Arial, Tahoma, sans-serif; font-size:12px">';
			  $html.='<tr>';	 
				   $html.='<td colspan=6 align=center style="font-size:16px"><b>'.$nome_relatorio.'</b></td>';
			  $html.='</tr>';
			  $html.='<tr>';	 
				   $html.='<td colspan=6>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>';
			  $html.='</tr>';
			  $html.='<tr>';			  
				  $cabe_campo = explode(",",$cabe_relatorio);
				  $conta = count($cabe_campo);
				  for($ic=0; $ic < $conta; $ic++) {
					 $html.='<td width='.$ta_campo[$ic].'><b>'.$cabe_campo[$ic].'</b></td>'; 
				  }
			  $html.='</tr>';
			 $html.='<tr>';	 
				   $html.='<td colspan=6>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<td>';
			 $html.='</tr>';
		  $html.='</thead>';
	  $html.='</table>';
	  $html.='<table width=800>';
	  $pega_dados = "SELECT $campos_tabela FROM $tabela $condicao";
	  $query = mysqli_query($cone,$pega_dados);
	  $total = mysqli_num_rows($query);
	  $n_campos   = explode(",",$campos_tabela);
	  $tocam      = count($n_campos)-1;
	  
	  for($ic=0; $ic<$total; $ic++){
		  $html.='<tr>';
			 $dados = mysqli_fetch_row($query);
			 for($ca=0;$ca <= $tocam; $ca++) {
				$html.='<td width='.$ta_campo[$ca].' style="font-family: Arial, Tahoma, sans-serif; font-size:11px">'.$dados[$ca].'</td>';
			 }
		  $html.='</tr>';
	  }
	  $html.='<tr>';	 
				   $html.='<td colspan=6>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<td>';
	  $html.='</tr>';
	  $html.='</table>';
	  
	  
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF();

	// Carrega seu HTML
	$dompdf->load_html($html);
	
	// Definindo o papel e a orientação do mesmo
	$dompdf->setPaper('A4', 'Landscape');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"$nome_arquivo", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
  
?>