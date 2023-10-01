<?php
	if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	
    require_once('Conexao.php');
	$conn = new Conexao();
	$cone = $conn->Conecta();
	mysqli_set_charset($cone,'UTF8');
	
	//Pega variavel tipo relatorio
	if(isset($_GET['tipo_rela'])){
		$tipo_relato = $_GET['tipo_rela'];
	}
	
    if(!isset($soma_geral)){
	   $soma_geral =0.00;
    }
    

    if($tipo_relato == 'fatu_periodo'){	
	   //pegando as data de pesquisa de variaveis globais.
       $dt_ini  = $_SESSION['dt_ini_m'];// Para mostrar titulo relatorio
	   $dt_fim  = $_SESSION['dt_fim_m'];// Para mostrar titulo relatorio
	   
       $data_inicio = $_SESSION['data_inicio_m'];//data para comparação SQL
       $data_final  = $_SESSION['data_fim_m'];//data para comparação SQL
   				
	   $nome_relatorio = 'FATURAMENTO POR PERÍODO :'.$dt_ini.' A '.$dt_fim;  
	   $cabe_relatorio = 'HAWB,SERVIÇO,CLIENTE,QTDADE,UNITÁRIO,TOTAL';
	   $campos_tabela  = 'movimento.n_hawb,servico.descri_se,pessoa.nome,movimento.qtdade,tabela_preco.valor_cli,(movimento.qtdade*tabela_preco.valor_cli)';
	   $tama_campo     = '100,150,250,100,100,100';
	   $tabela         = 'movimento,servico,pessoa,tabela_preco';
	   $condicao       = 'WHERE movimento.dt_rece_hawb >= "'.$data_inicio.'" AND movimento.dt_rece_hawb <= "'.$data_final.'"
						AND movimento.co_servico=servico.codigo_se AND movimento.codi_cli=pessoa.cnpj_cpf
						AND movimento.co_servico=tabela_preco.tipo_servi AND movimento.classe_cep=tabela_preco.classe_cep';
    }

	if($tipo_relato == 'fatu_cliente'){	
	   //pegando as data de pesquisa de variaveis globais.
   
	   $dt_ini      = $_SESSION['dt_ini_m']; // data para cabecalho relatorio
	   $dt_fim      = $_SESSION['dt_fim_m']; //data para cabecalho relatorio
       $cliente     = $_SESSION['cliente_m'];// codigo cliente
       $data_inicio = $_SESSION['data_inicio_m'];// data para comparação SQL
       $data_final  = $_SESSION['data_fim_m'];// data para comparação SQL
       $nome_cli    = $_SESSION['nome_cli_m'];//Nome cliente	   

	   $nome_relatorio = 'FATURAMENTO COM O CLIENTE :'.$nome_cli.' - PERÍODO: '.$dt_ini.' A '.$dt_fim;  
	   $cabe_relatorio = 'HAWB,SERVIÇO,DESTINO,QTDADE,UNITÁRIO,TOTAL';
	   $campos_tabela  = 'movimento.n_hawb,servico.descri_se,movimento.nome_desti,movimento.qtdade,tabela_preco.valor_cli,(movimento.qtdade*tabela_preco.valor_cli)';
	   $tama_campo     = '100,150,210,100,100,100';
	   $tabela         = 'movimento,servico,tabela_preco';
	   $condicao       = 'WHERE movimento.dt_rece_hawb >= "'.$data_inicio.'" AND movimento.dt_rece_hawb <= "'.$data_final.'"
						AND movimento.codi_cli = "'.$cliente.'"
						AND movimento.co_servico=servico.codigo_se AND movimento.co_servico=tabela_preco.tipo_servi 
						AND movimento.classe_cep=tabela_preco.classe_cep';
    }	  
	
	if($tipo_relato == 'fatu_escritorio'){	
	   //pegando as data de pesquisa de variaveis globais.
       
	   $dt_ini      = $_SESSION['dt_ini_m']; // data para cabecalho relatorio
	   $dt_fim      = $_SESSION['dt_fim_m']; //data para cabecalho relatorio
       $escritorio  = $_SESSION['escritorio_m'];// codigo escritorio
       $data_inicio = $_SESSION['data_inicio_m'];// data para comparação SQL
       $data_final  = $_SESSION['data_fim_m'];// data para comparação SQL
       $nome_esc    = $_SESSION['nome_esc_m'];//Nome Escritorio	   

	   $nome_relatorio = 'FATURAMENTO DO ESCRITÓRIO :'.$nome_esc.' - PERÍODO: '.$dt_ini.' A '.$dt_fim;  
	   $cabe_relatorio = 'HAWB,SERVIÇO,DESTINO,QTDADE,UNITÁRIO,TOTAL';
	   $campos_tabela  = 'movimento.n_hawb,servico.descri_se,movimento.nome_desti,movimento.qtdade,tabela_preco.valor_cli,(movimento.qtdade*tabela_preco.valor_cli)';
	   $tama_campo     = '100,150,210,100,100,100';
	   $tabela         = 'movimento,servico,tabela_preco';
	   $condicao       = 'WHERE movimento.dt_rece_hawb >= "'.$data_inicio.'" AND movimento.dt_rece_hawb <= "'.$data_final.'"
						AND movimento.escritorio = "'.$escritorio.'"
						AND movimento.co_servico=servico.codigo_se AND movimento.co_servico=tabela_preco.tipo_servi 
						AND movimento.classe_cep=tabela_preco.classe_cep';
    }	  
	
	if($tipo_relato == 'fatu_servico'){	
	   //pegando as data de pesquisa de variaveis globais.
       
	   $dt_ini        = $_SESSION['dt_ini_m']; // data para cabecalho relatorio
	   $dt_fim        = $_SESSION['dt_fim_m']; //data para cabecalho relatorio
       $s_servico     = $_SESSION['s_servico_m'];// codigo escritorio
       $data_inicio   = $_SESSION['data_inicio_m'];// data para comparação SQL
       $data_final    = $_SESSION['data_fim_m'];// data para comparação SQL
       $descri_servi  = $_SESSION['descri_serve_m'];//Nome Escritorio	   

	   $nome_relatorio = 'FATURAMENTO POR SERVIÇO :'.$descri_servi.' - PERÍODO: '.$dt_ini.' A '.$dt_fim;  
	   $cabe_relatorio = 'HAWB,SERVIÇO,DESTINO,QTDADE,UNITÁRIO,TOTAL';
	   $campos_tabela  = 'movimento.n_hawb,pessoa.nome,movimento.nome_desti,movimento.qtdade,tabela_preco.valor_cli,(movimento.qtdade*tabela_preco.valor_cli)';
	   $tama_campo     = '100,210,210,100,100,100';
	   $tabela         = 'movimento,pessoa,tabela_preco';
	   $condicao       = 'WHERE movimento.dt_rece_hawb >= "'.$data_inicio.'" AND movimento.dt_rece_hawb <= "'.$data_final.'"
						AND movimento.co_servico = "'.$s_servico.'"
						AND movimento.codi_cli=pessoa.cnpj_cpf AND movimento.co_servico=tabela_preco.tipo_servi 
						AND movimento.classe_cep=tabela_preco.classe_cep';
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
			     $html.='<td colspan=6>---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>';
			  $html.='</tr>';
			  $html.='<tr>';			  
				  $cabe_campo = explode(",",$cabe_relatorio);
				  $conta = count($cabe_campo);
				  for($ic=0; $ic < $conta; $ic++) {
					 $html.='<td width='.$ta_campo[$ic].'><b>'.$cabe_campo[$ic].'</b></td>'; 
				  }
			  $html.='</tr>';
			 $html.='<tr>';	 
			   $html.='<td colspan=6>---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<td>';
			 $html.='</tr>';
		  $html.='</thead>';
	  $html.='</table>';
	  $html.='<table width=900>';
	  $pega_dados = "SELECT $campos_tabela FROM $tabela $condicao";
	  $query = mysqli_query($cone,$pega_dados);
	  $total = mysqli_num_rows($query);
	  $n_campos   = explode(",",$campos_tabela);
	  $tocam      = count($n_campos)-1;
	  
	  for($ic=0; $ic<$total; $ic++){
		  $html.='<tr>';
			 $dados = mysqli_fetch_row($query);
			 for($ca=0;$ca <= $tocam; $ca++) {
				if(($ca == 4) or ($ca == 5)) {
				   $html.='<td width='.$ta_campo[$ca].' style="font-family: Arial, Tahoma, sans-serif; font-size:11px">'.str_replace(".",",",$dados[$ca]).'</td>';
				   if($ca == 5){
				      $soma_geral = $soma_geral + (floatval($dados[$ca]));
				   }
				}else {
				   $html.='<td width='.$ta_campo[$ca].' style="font-family: Arial, Tahoma, sans-serif; font-size:11px">'.$dados[$ca].'</td>';
				}
			 }
		  $html.='</tr>';
	  }
	  $html.='<tr>';	 
		$html.='<td colspan=6>-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<td>';
	  $html.='</tr>';
	  $html.='<tr>';	 
		$html.='<td colspan=4></td>';
		$html.='<td><b>T O T A L ...</b></td>';
		$html.='<td><b>'.number_format($soma_geral,'2',',','.').'</b></td>';
	  $html.='</tr>';
	  $html.='<tr>';	 
		$html.='<td colspan=6>-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<td>';
	  $html.='</tr>';
	  $html.='</table>';
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
      //echo "<p> Relatorio :".$html;
	  
	 //referenciar o DomPDF com namespace
     use Dompdf\Dompdf;
	 
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
		"relatorio_faturamento.pdf", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
     );
?>