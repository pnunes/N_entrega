<?php
    if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
   
    require_once('Conexao.php');
    $conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');
   
    $data_hoje = date('Y-m-d');
	
    $prazo_entrega = $_SESSION['prazo_entrega_m'];
	 
	$data_limite = date('d/m/Y', strtotime("+{$prazo_entrega} days",strtotime($data_hoje)));
	  
	$dt_limite   = explode("/",$data_limite);
	$v_data_limi = $dt_limite[2]."-".$dt_limite[1]."-".$dt_limite[0];
	  
	//echo "<p>Limite entrega :".$v_data_limi;
	  
	$mes = substr($data_hoje, 5,2);
	//  echo "<p>Mês :".$mes;
	$ano = substr($data_hoje, 0,4);
	//  echo "<p>Ano :".$ano;
	$dia = substr($data_hoje, 8,2);
	//  echo "<p>Dia :".$dia;
	if($mes =='01' or $mes =='03' or $mes =='05' or $mes =='07' or $mes =='08' or $mes =='10' or $mes =='12'){
		  $data_inicio = $ano.'-'.$mes.'-'.'01';
		  $data_fim    = $ano.'-'.$mes.'-'.'31';
	} else {
		  $data_inicio = $ano.'-'.$mes.'-'.'01';
		  $data_fim    = $ano.'-'.$mes.'-'.'30'; 
	}
   
	require_once('Conexao.php');
	mysqli_set_charset($cone,'UTF8');
	$conn = new Conexao();
	$cone = $conn->Conecta();
    
    $tipo_movi	= $_GET['tipo'];
	
	//HAWBs recebidas no mes em curso
	if($tipo_movi == 'movi_mes'){
		 $nome_relatorio = 'HAWB RECEBIDAS NO MÊS EM CURSO';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE dt_rece_hawb BETWEEN "'.$data_inicio.'" AND "'.$data_fim.'"';
	}
	
	//HAWBs recebidas no dia
    if($tipo_movi == 'movi_dia'){
		 $nome_relatorio = 'HAWB RECEBIDAS NO DIA';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE dt_rece_hawb = "'.$data_hoje.'"';
	}
	
	//HAWB na base para serem entregues
    if($tipo_movi == 'movi_base'){
		 $nome_relatorio = 'HAWB NA BASE PARA SER ENTREGUE';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE nu_lista_entrega="" AND dt_rece_hawb BETWEEN "'.$data_inicio.'" AND "'.$data_fim.'"';
	}
	
	//HAWB EM LISTA DE ENTREGA
	if($tipo_movi == 'movi_lista'){
		 $nome_relatorio = 'HAWB EM LISTA DE ENTREGA';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE ((nu_lista_entrega<>"") AND (dt_entrega="1000-01-01") AND (dt_rece_hawb BETWEEN "'.$data_inicio.'" AND "'.$data_fim.'"))';
	}	
	
    //HAWB ENTREGUE HOJE
	if($tipo_movi == 'movi_hoje'){
		 $nome_relatorio = 'HAWB EM LISTA DE ENTREGA';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE dt_entrega = "'.$data_hoje.'"';
	}

    //HAWB ENTREGUE NO MES
	if($tipo_movi == 'movi_en_mes'){
		 $nome_relatorio = 'HAWB ENTREGUES NO MÊS';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE dt_entrega BETWEEN "'.$data_inicio.'" AND "'.$data_fim.'"';
	}
	
	//HAWB ENTREGUE FORA DO PRAZO
	if($tipo_movi == 'ent_fora_prazo'){
		 $nome_relatorio = 'HAWB ENTREGUES FORA DO PRAZO';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE ((data_previ_entrega < "'.$data_hoje.'") AND (dt_rece_hawb BETWEEN "'.$data_inicio.'" AND "'.$data_fim.'") AND (dt_entrega = "1000-01-01"))';
	}		
	
	//DEVOLVIDO A ORIGEM HOJE
	if($tipo_movi == 'dev_orig_hoje'){
		 $nome_relatorio = 'HAWB DEVOLVIDA A ORIGEM HOJE';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE dt_dev_origem = "'.$data_hoje.'"';
	}		
	
	//DEVOLVIDO A ORIGEM NO MES EM CURSO
	if($tipo_movi == 'dev_orig_mes'){
		 $nome_relatorio = 'HAWB DEVOLVIDA A ORIGEM NO MES EM CURSO';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE dt_dev_origem BETWEEN "'.$data_inicio.'" AND "'.$data_fim.'"';
	}

	//PENDENCIA ENTREGA DE OUTROS MESES
	if($tipo_movi == 'ent_outro_mes'){
		 $nome_relatorio = 'HAWB DE OUTROS MESES NÃO ENTREGUE E FORA DO PRAZO';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE data_previ_entrega < "'.$data_hoje.'" AND dt_entrega ="1000-01-01" AND dt_rece_hawb < "'.$data_fim.'"';
	}
	//PENDENCIA DE ENTREGA DE OUTROS MESES
	if($tipo_movi == 'movi_vira_mes'){
		 $nome_relatorio = 'HAWB DENTRO PRAZO EM VIRADA DE MÊS';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE ((data_previ_entrega > "'.$data_hoje.'") AND (dt_entrega ="1000-01-01") AND (dt_rece_hawb < "'.$data_inicio.'"))';
	}
	
	//TOTAL DE HAWB PRONTAS PARA DEVOLUCAO A ORIGEM
	if($tipo_movi == 'total_dev_origem'){
		 $nome_relatorio = 'TOTAL HAWB PRONTAS PARA DEVOLUÇÃO A ORIGEM';  
		 $cabe_relatorio = 'HAWB,DESTINATARIO,RUA,CIDADE,CEP,UF';
		 $campos_tabela  = 'n_hawb,nome_desti,rua_desti,cep_desti,cidade_desti,estado_desti';
		 $tama_campo     = '50,210,210,50,100,100';
		 $tabela         = 'movimento';
		 $condicao       = 'WHERE ((lote_devo_origem = "") AND (dt_entrega <>"1000-01-01"))';
	}
    //var_dump($nome_relatorio,$cabe_relatorio,$campos_tabela,$tama_campo,$tabela,$condicao);
	 
	//desmembra a variavel que tem o tamanho dos campos transformando num array para dar dois espaços entre as colunas do relatorio
	  $ta_campo   = explode(",",$tama_campo);
	 
	 
	  ?>
	 
	  <table width="900">
		  <thead style="font-family: Arial, Tahoma, sans-serif; font-size:12px">
			  <tr>	 
				   <td colspan="6" align="center" style="font-size:16px"><b><?php echo $nome_relatorio; ?></b></td>
			  </tr>
			  <tr>	 
				   <td colspan="6"><hr size="2" width="100%"></td>
			  </tr>
			  <tr>			  
			  <?php	$cabe_campo = explode(",",$cabe_relatorio);
				  $conta = count($cabe_campo);
				  for($ic=0; $ic < $conta; $ic++) { ?>
					 <td width="<?php echo $ta_campo[$ic]?>"><b><?php echo $cabe_campo[$ic] ?></b></td> 
			  <?php } ?>
			  </tr> 
		  </thead>
	  </table>
	  <table width="900">
		  <?php
		  $pega_dados = "SELECT $campos_tabela FROM $tabela $condicao";
		  $query = mysqli_query($cone,$pega_dados);
		  $total = mysqli_num_rows($query);
		  $n_campos   = explode(",",$campos_tabela);
		  $tocam      = count($n_campos)-1;
		  
		  for($ic=0; $ic<$total; $ic++){ ?>
			  <tr> <?php
				 $dados = mysqli_fetch_row($query);
				 for($ca=0;$ca <= $tocam; $ca++) { ?>
					<td width="<?php echo $ta_campo[$ca]; ?>" style="font-family: Arial, Tahoma, sans-serif; font-size:11px"><?php echo $dados[$ca]; ?></td><?php
		         } ?>
			  </tr><?php
	      } ?>
		  <tr>	 
		     <td colspan="6"><hr size="5" width="100%"></td>
		  </tr>
		  <tr>	 
			 <td colspan="6" align="center"><b>Total de HAWB recebidas no mês : <?php echo $total; ?></b></td>
		  </tr>
		  <tr>	 
		     <td colspan="6"><hr size="5" width="100%"></td>
		  </tr>
	  </table>
<?php	  
?>