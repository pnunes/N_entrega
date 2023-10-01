<?php	
    $html='<table>';
	  $html.='<thead style="font-family: Arial, Tahoma, sans-serif; font-size:12px">';
		  $html.='<tr>';	 
			   $html.='<td colspan=8 align=center></td>';
		  $html.='</tr>';
		  $html.='<tr>';	 
			   $html.='<td colspan=8 align=center>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>';
		  $html.='</tr>';
		  $html.='<tr>';
			  $html.='<td width=10>Ola tudo bem aqui e uma td</td>'; 
		  $html.='</tr>';
		 $html.='<tr>';	 
			   $html.='<td colspan=8 align=center>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>';
		 $html.='</tr>';
	  $html.='</thead>';
    $html.='</table>';
    $html.='<table>';
	   $html.='<tr>';	     
		  $html.='<td width=50 style="font-family: Arial, Tahoma, sans-serif; font-size:11px"></td>';		
	   $html.='</tr>';
    $html.='</table>';
	
	//carregando a classe DOMPDF
	//  require_once 'dompdf/autoload.inc.php';
	  // instanciando o dompdf
	//  $dompdf = new Dompdf();
	  //lendo o arquivo HTML correspondente
	  /*$html = file_get_contents('exemplo.html');*/
	  //inserindo o HTML que queremos converter
	//  $dompdf->load_html($html);
	  // Definindo o papel e a orientação do mesmo
	//  $dompdf->setPaper('A4', 'Landscape');
	  // Renderizando o HTML como PDF
	//  $dompdf->render();
	  // Enviando o PDF para o browser
	//  $dompdf->stream('relatorio.pdf',array('Attachment'=>0));
		  
		  
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF();

	// Carrega seu HTML
	$dompdf->load_html($html);

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"relatorio_celke.pdf", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>