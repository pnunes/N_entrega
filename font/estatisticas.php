<?php
  $data_hoje = date('Y-m-d');
  
  $prazo_entrega = $_SESSION['prazo_entrega_m'];
  
  $data_limite = date('d/m/Y', strtotime("+{$prazo_entrega} days",strtotime($data_hoje)));
  
  $dt_limite   = explode("/",$data_limite);
  $v_data_limi = $dt_limite[2]."-".$dt_limite[1]."-".$dt_limite[0];
  
  //echo "<p>Limite entrega :".$data_hoje;
  
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
 
  //conta o total de trabalhos recebidos no mês
  
  $traba_mes ="SELECT COUNT(*) As total_mes FROM movimento
  WHERE dt_rece_hawb BETWEEN '$data_inicio' AND '$data_fim'";
  $query_m   = mysqli_query($cone,$traba_mes) or die ("Não foi possivel acessar o banco(Estatisticas -1)");
  $conta_m = mysqli_num_rows($query_m);
  for($ic_m=0; $ic_m<$conta_m; $ic_m++){
     $mostra_m = mysqli_fetch_row($query_m);
     $total_m       = $mostra_m[0];
  }
  
  //conta o total de trabalhos recebidos no dia
  
  $traba_dia ="SELECT COUNT(*) As total_dia FROM movimento
  WHERE dt_rece_hawb = '$data_hoje'";
  $query_d   = mysqli_query($cone,$traba_dia) or die ("Não foi possivel acessar o banco(Estatisticas -1)");
  $conta_d = mysqli_num_rows($query_d);
  for($ic_d=0; $ic_d<$conta_d; $ic_d++){
     $mostra_d = mysqli_fetch_row($query_d);
     $total_d       = $mostra_d[0];
  }
  
  //conta os trabalhos PARADOS NA BASE a serem colocados em rota
  
  $traba_base ="SELECT COUNT(*) As total_base FROM movimento
  WHERE ((nu_lista_entrega='')
  AND (dt_rece_hawb BETWEEN '$data_inicio' AND '$data_fim'))";
  $query_b = mysqli_query($cone,$traba_base) or die ("Não foi possivel acessar o banco (Estatisticas -2)");
  $conta_b = mysqli_num_rows($query_b);
  for($ic_d=0; $ic_d<$conta_b; $ic_d++){
     $mostra_b = mysqli_fetch_row($query_b);
     $total_b       = $mostra_b[0];
  }
   //conta os trabalhos JÁ EM LISTA DE ENTREGA
  $traba_rota ="SELECT COUNT(*) As total_rota FROM movimento
  WHERE ((nu_lista_entrega<>'')
  AND (dt_entrega='1000-01-01')
  AND (dt_rece_hawb BETWEEN '$data_inicio' AND '$data_fim'))";
  $query_r = mysqli_query($cone,$traba_rota) or die ("Não foi possivel acessar o banco (Estatisticas -3)");
  $conta_r = mysqli_num_rows($query_r);
  for($ic_d=0; $ic_d<$conta_r; $ic_d++){
     $mostra_r = mysqli_fetch_row($query_r);
     $total_r       = $mostra_r[0];
  }
  
   //conta os trabalhos ENTREGUES hoje
  $traba_hoje ="SELECT COUNT(*) As total_entreh FROM movimento
  WHERE dt_entrega = '$data_hoje'";
  $query_h = mysqli_query($cone,$traba_hoje) or die ("Não foi possivel acessar o banco (Estatisticas -4)");
  $conta_h = mysqli_num_rows($query_h);
  for($ic_h=0; $ic_h<$conta_h; $ic_h++){
     $mostra_h = mysqli_fetch_row($query_h);
     $total_h       = $mostra_h[0];
  }
  
   //conta os trabalhos ENTREGUES no mes
  $traba_em ="SELECT COUNT(*) As total_entrem FROM movimento
  WHERE dt_entrega BETWEEN '$data_inicio' AND '$data_fim'";
  $query_em = mysqli_query($cone,$traba_em) or die ("Não foi possivel acessar o banco (Estatisticas -4.1)");
  $conta_em = mysqli_num_rows($query_em);
  for($ic_em=0; $ic_em<$conta_em; $ic_em++){
     $mostra_em = mysqli_fetch_row($query_em);
     $total_em       = $mostra_em[0];
  }
  
   //Não entregues e fora do prazo de entrega
  $traba_efp ="SELECT COUNT(*) As total_fora FROM movimento
  WHERE data_previ_entrega < '$data_hoje'
  AND dt_rece_hawb BETWEEN '$data_inicio' AND '$data_fim'
  AND dt_entrega = '1000-01-01'";
  $query_efp = mysqli_query($cone,$traba_efp) or die ("Não foi possivel acessar o banco (Estatisticas -4.2)");
  $conta_efp = mysqli_num_rows($query_efp);
  for($ic_efp=0; $ic_efp<$conta_efp; $ic_efp++){
     $mostra_efp = mysqli_fetch_row($query_efp);
     $total_efp       = $mostra_efp[0];
  }
   //pods devolvidos a horigem hoje
  $traba_cd ="SELECT COUNT(*) As total_devh FROM movimento
  WHERE dt_dev_origem = '$data_hoje'";
  $query_cd = mysqli_query($cone,$traba_cd) or die ("Não foi possivel acessar o banco (Estatisticas -5)");
  $conta_cd = mysqli_num_rows($query_cd);
  for($ic_cd=0; $ic_cd<$conta_cd; $ic_cd++){
     $mostra_cd = mysqli_fetch_row($query_cd);
     $total_cd       = $mostra_cd[0];
  }

  //pods devolvidos a horigem no mês
  $traba_cm ="SELECT COUNT(*) As total_devm FROM movimento
  WHERE dt_dev_origem BETWEEN '$data_inicio' AND '$data_fim'";
  $query_cm = mysqli_query($cone,$traba_cm) or die ("Não foi possivel acessar o banco (Estatisticas -5)");
  $conta_cm = mysqli_num_rows($query_cm);
  for($ic_cm=0; $ic_cm < $conta_cm; $ic_cm++){
     $mostra_cm = mysqli_fetch_row($query_cm);
     $total_cm       = $mostra_cm[0];
  }
  
  // CALCULO DOS TEABALHOS PENDENTES - DE OUTROS MESES.
  
  //de outros meses e ainda não entregue e fora do prazo entrega
  $traba_fp ="SELECT COUNT(*) As total_omes FROM movimento
  WHERE data_previ_entrega < '$data_hoje'
  AND dt_entrega ='1000-01-01'
  AND dt_rece_hawb < '$data_inicio'";
  $query_fp = mysqli_query($cone,$traba_fp) or die ("Não foi possivel acessar o banco (Estatisticas -4)");
  $conta_fp = mysqli_num_rows($query_fp);
  for($ic_fp=0; $ic_fp<$conta_fp; $ic_fp++){
     $mostra_fp = mysqli_fetch_row($query_fp);
     $total_fp       = $mostra_fp[0];
  }
  
  //de outros meses não entregue mas ainda dentro do prazo entrega - exemplo: virada de mês
  $traba_np ="SELECT COUNT(*) As total_atrazo FROM movimento
  WHERE ((data_previ_entrega > '$data_hoje')
  AND (dt_entrega <>'1000-01-01')
  AND (dt_rece_hawb < '$data_inicio'))";
  $query_np = mysqli_query($cone,$traba_np) or die ("Não foi possivel acessar o banco (Estatisticas -4)");
  $conta_np = mysqli_num_rows($query_np);
  for($ic_np=0; $ic_np<$conta_np; $ic_np++){
     $mostra_np = mysqli_fetch_row($query_np);
     $total_np       = $mostra_np[0];
  }
  
  //total de pods a serem devolvidos ao cliente de origem
  $traba_do ="SELECT COUNT(*) As total_devio FROM movimento
  WHERE ((lote_devo_origem = '')
  AND (dt_entrega <>'1000-01-01'))";
  $query_do = mysqli_query($cone,$traba_do) or die ("Não foi possivel acessar o banco (Estatisticas -4)");
  $conta_do = mysqli_num_rows($query_do);
  for($ic_do=0; $ic_do<$conta_do; $ic_do++){
     $mostra_do = mysqli_fetch_row($query_do);
     $total_do       = $mostra_do[0];
  }
?>
