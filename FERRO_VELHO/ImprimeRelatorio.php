<?php
if ( session_status() !== PHP_SESSION_ACTIVE ) {
     session_start();
}

require_once('Conexao.php');
mysqli_set_charset($cone,'UTF8');

//referenciando o namespace do dompdf
use Dompdf\Dompdf;


class ImprimeRelatorio {
     // Atributtos
     private $campos;
     private $tabela;
     private $condicao;
     private $nome_relatorio;
	 private $titulo_campo;
	 private $tama_campo;
     private $resultado;

     public function __construct($camp,$tabe,$cond,$nrel,$tcam,$tacam){
         $this->campos          = $camp; 
         $this->tabela          = $tabe; 
         $this->condicao        = $cond;
         $this->nome_relatorio  = $nrel;
         $this->titulo_campo    = $tcam;
		 $this->tama_campo      = $tacam;
     }

     public function Listar(){
		  //Faz connexao com o banco de dados
          $conn = new Conexao();
          $cone = $conn->Conecta();
		  
		  $nome_relatorio = $this->nome_relatorio;
		  $cabe_relatorio = $this->titulo_campo;
		  $campos_tabela  = $this->campos;
		  $tabela         = $this->tabela;
		  $condicao       = $this->condicao;
		  
          //desmembra a variavel que tem o tamanho dos campos transformando num array para dar dois espaços entre as colunas do relatorio
		  $ta_campo   = explode(",",$this->tama_campo);
		  
		  //Pega o nome do relatorio e começa a contar o relarorio
		  $html='<table width=800>';
			  $html.='<thead style="font-family: Arial, Tahoma, sans-serif; font-size:12px">';
				  $html.='<tr>';	 
					   $html.='<td colspan=6 align=center>'.$nome_relatorio.'</td>';
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
		  
		  //echo "<p> Arquivo :".$html;
		  
	     //carregando a classe DOMPDF
		  require_once 'Dompdf/autoload.inc.php';

		  // instanciando o dompdf
		  $dompdf = new Dompdf();
		  //inserindo o HTML que queremos converter
		  $dompdf->loadHtml($html);
		  
		  // Definindo o papel e a orientação do mesmo
		  $dompdf->setPaper('A4', 'Landscape');
		  // Renderizando o HTML como PDF
		  $dompdf->render();
		
		  // Enviando o PDF para o browser
		  $dompdf->stream('relatorio.pdf',array('Attachment'=>0));
		  
         // return $this->$Relatorio;
        //  mysqli_close($cone);
     }
}
?>