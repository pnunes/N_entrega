<?php
  //Pega o nome do relatorio e começa a contar o relarorio
  $html='<p>Este é um teste para impressãoPDF com DOMPDF</p>'; 
  // referenciando o namespace do dompdf
  use Dompdf\Dompdf;
  //carregando a classe DOMPDF
  //require_once 'vendor/autoload.php';
  require_once 'dompdf/autoload.inc.php';
  // instanciando o dompdf
  $dompdf = new Dompdf();
  //lendo o arquivo HTML correspondente
  //$html = file_get_contents('exemplo.html');
  //inserindo o HTML que queremos converter
  $dompdf->loadHtml($html);
  // Definindo o papel e a orientação do mesmo
  $dompdf->setPaper('A4', 'Landscape');
  // Renderizando o HTML como PDF
  $dompdf->render();
  // Enviando o PDF para o browser
  $dompdf->stream('relatorio.pdf',array('Attachment'=>0));
?>