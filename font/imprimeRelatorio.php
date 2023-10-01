<?php

         
	require_once('Conexao.php');
	$conn = new Conexao();
	$cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');

	$nome_arquivo   = $_GET['nome_arqui'];
    $nome_relatorio	= $_GET['nome_rel'];
	$cabe_relatorio = $_GET['ca_rela'];
	$campos_tabela  = $_GET['campos'];
	$tama_campo     = $_GET['t_campos'];
	$tabela         = $_GET['tab'];
	$condicao       = $_GET['condi'];
	
    //referenciar o DomPDF com namespace
	use Dompdf\Dompdf;
	
    //var_dump($nome_relatorio,$cabe_relatorio,$campos_tabela,$tama_campo,$tabela,$condicao);
	  
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
	  $pega_dados = "SELECT $campos_tabela FROM $tabela WHERE $condicao";
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
	  
	  //echo "<p>Relatorio :".$html;
	  
	  // include autoloader
	  require_once("Dompdf/autoload.inc.php");

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
			"Attachment" => false //Para realizar o download automatico somente alterar para true
		 )
	  );
?>