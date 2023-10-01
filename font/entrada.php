<?php
   if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
   }
   
   require_once('Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   $pega_nomesis = "SELECT form_hawb_inclui,prazo_entrega FROM aparencia_site";
   $nome_s = mysqli_query($cone,$pega_nomesis);
   $n_regi = mysqli_num_rows($nome_s);	
   for($ii=0; $ii<$n_regi; $ii++){
		$row = mysqli_fetch_row($nome_s); 
		$orienta_inc_hawb = $row[0];
		$prazo_entrega    = $row[1];
   }
   $_SESSION['prazo_entrega_m'] = $prazo_entrega;
   
   // Limpa variaveis globais não utilizaveis em outras rotinas
   
   // Variaveis utilizadas na rotina altera_lista_entrega.php e rotina elabora_lista_entrega.php
   unset($_SESSION['co_servico_m']);
   unset($_SESSION['nu_lista_m']);
   unset($_SESSION['n_entregador_m']);
   unset($_SESSION['dt_lista_ent_m']);
   unset($_SESSION['controle_m']);
   
   //Chama a rotina de permissão de acesso a rotinas OPERACIONAL
   require_once('permissao_rotinas_operacao.php'); 
?>

<!DOCTYPE html>
<html lang="pt-Br">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/estilo_entrada.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-light" style="background-color: #000066;">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- 
			PARA CADA LINK EXISTEM AS SEGUINTES VARIAVEIS QUE DEVEM SER ENVIADAS PARA A ROTINA  tela_entra_cadastro.php : 
			TABELA: Nome da tabela que sera usada; 
			QTDC: numero de itens do cabeçalho da pagina;
            TITULO: Titulo a ser atribuido a tela cadastro;
			CAB_1: Nome do primeiro campo do cabeçalho;
			CAB_2: Nome do segundo campo do cabeçalho;
			CAB_3: Nome do terceiro campo do cabeçalho;
			CODIGO_SQL: Nome da SQL que será utilizada na tela cadastro;
			MODULO: Nome do modulo do sistema que deve ser impresso na tela;
			IMPRIME: Deve ser passado S ou N para os casos em que imprime relação dos registros mostrados na tela;
			TITULO_DOC: Deve passar o titulo do relatorio, nos casos em que da tela de entrada do cadastro for disponibilizado relatorio
			ESTILO: informar o codigo 1 ou 2 para definir qual estilo (CSS) deve ser usado para montar a tela entrada de cadastro
			-->
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cadastros<span class="caret"></span></a>
						<ul class="dropdown-menu">
						    <li><a href="tela_entra_cadastro.php?tabela=pessoa&qtdc=4&titulo=Atualização Cadastro Pessoas&cab_1=CPF/CNPJ&cab_2=NOME&cab_3=TIPO&cab_4=CATEGO&codigo_sql=SQL_PES&modulo=CADASTROS&titulo_doc=&estilo=2&sentido=&volta=tela_entra_cadastro.php&CI=PES1&CA=PES2&CE=PES3">Pessoa</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=destino&qtdc=4&titulo=Atualização Cadastro Destinos&cab_1=CODIGO&cab_2=NOME&cab_3=CIDADE&cab_4=ESTADO&codigo_sql=SQL_DES&modulo=CADASTROS&imprime=N&titulo_doc=&estilo=2&volta=tela_entra_cadastro.php&CI=DES1&CA=DES2&CE=DES3">Destinos</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=escritorio&qtdc=4&titulo=Atualização Cadastro Filiais&cab_1=CODIGO&cab_2=NOME&cab_3=SIGLA&cab_4=ESTADO&codigo_sql=SQL_ESC&modulo=CADASTROS&imprime=N&titulo_doc=&estilo=2&volta=tela_entra_cadastro.php&CI=ESC1&CA=ESC2&CE=ESC3">Filiais</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=servico&qtdc=2&titulo=Atualização Cadastro Serviços&cab_1=CODIGO&cab_2=DESCRIÇÃO&cab_3=&cab_4=&codigo_sql=SQL_SER&modulo=CADASTROS&imprime=N&titulo_doc=&estilo=1&volta=tela_entra_cadastro.php&CI=SER1&CA=SER2&CE=SER3">Serviços</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=tabela_preco&qtdc=4&titulo=Atualização Tabela Preços&cab_1=CODIGO&cab_2=SERVIÇO&cab_3=CLASSE SERVICO&cab_4=CLIENTE&codigo_sql=SQL_TAP&modulo=CADASTROS&imprime=N&titulo_doc=&estilo=2&volta=tela_entra_cadastro.php&CI=TAP1&CA=TAP2&CE=TAP3">Tabela de Preço</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=ocorrencia&qtdc=2&titulo=Atualização Cadastro Ocorrências&cab_1=CODIGO&cab_2=DESCRIÇÃO&cab_3=&cab_4=&codigo_sql=SQL_OCO&modulo=CADASTROS&imprime=N&titulo_doc=&estilo=1&volta=tela_entra_cadastro.php&CI=OCO1&CA=OCO2&CE=OCO3">Ocorrências</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=intervalo_cep&qtdc=2&titulo=Atualização Cadastro Classe CEP&cab_1=CODIGO&cab_2=NOME CLASSE&cab_3=UF&cab_4=CIDADE&codigo_sql=SQL_CCP&modulo=CADASTROS&imprime=N&titulo_doc=&estilo=2&volta=tela_entra_cadastro.php&CI=CEP1&CA=CEP2&CE=CEP3">Classe CEP</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Operacao<span class="caret"></span></a>
						<ul class="dropdown-menu">	
							<li><a href="tela_entra_cadastro_operacao.php?tabela=movimento&qtdc=4&titulo=Lançamento de HAWB&cab_1=COD. BARRA&cab_2=N. HAWB&cab_3=CLIENTE&cab_4=DT. ENTRADA&codigo_sql=SQL_OP1&modulo=Operação&titulo_doc=&estilo=2&sentido=<?php echo $orienta_inc_hawb;?>&titulo_form=Inclusão de HAWB no Sistema&CI=BI11&CA=BI12&CE=BI13"><b>Bip 1</b> - Lança <b>HAWB</b></a></li>
							<li role="separator" class="divider"></li>
							<?php if($permissao_ele == 'S') { ?>
							   <li><a href="elabora_lista_entrega.php?modulo=OPERAÇÃO&titulo_form=Elabora Lista de Entrega"><b>Bip 2</b> - Elabora Lista Entrega <b>(Leitora)</b></a></li>
							   <li role="separator" class="divider"></li>
							<?php } ?>
							<?php if($permissao_lim == 'S') { ?>
							   <li><a href="lista_entrega_manual.php?modulo=OPERAÇÃO&titulo_form=Lista de Entrega Manual"><b>Bip 2</b> - Elabora Lista Entrega <b>(Manual)</b></a></li>
							   <li role="separator" class="divider"></li>
							<?php } ?>
							<?php if($permissao_ale == 'S') { ?>
							   <li><a href="altera_lista_entrega.php?tabela=movimento&qtdc=4&titulo=Altera Lista de Entrega&cab_1=CONT.&cab_2=COD. BARRA&cab_3=DESTINATARIO&cab_4=N. LISTA.&cab_5=DT. LISTA&cab_6=ENTREGADOR&modulo=Operação&titulo_doc=&estilo=2&sentido=<?php echo $orienta_inc_hawb;?>&titulo_form=Altera lista de entrega"><b>Bip 2</b> - Altera Lista Entrega</b></a></li>
							   <li role="separator" class="divider"></li>
							<?php } ?> 
							<?php if($permissao_bhe == 'S') { ?>
							   <li><a href="baixa_hawb_entregue.php?tabela=movimento&qtdc=4&titulo=Lançamento de HAWB&cab_1=CONT.&cab_2=COD. BARRA&cab_3=DT. BAIXA&cab_4=DT. ENTR.&cab_5=HORA&cab_6=ENTREGADOR&cab_7=RECEBEDOR&cab_8=DOCUMENTO&cab_9=OCORRÊNCIA.&modulo=Operação&titulo_doc=&estilo=2&sentido=<?php echo $orienta_inc_hawb;?>&titulo_form=Baixa de HAWB entregue"><b>Bip 3</b> - Baixa <b>HAWB</b> Entregue</a></li>
							   <li role="separator" class="divider"></li>
							<?php } ?>
							<?php if($permissao_abe == 'S') { ?>
							   <li><a href="altera_baixa_hawb_entregue.php?tabela=movimento&qtdc=4&titulo=Lançamento de HAWB&cab_1=CONT.&cab_2=COD. BARRA&cab_3=DT. BAIXA&cab_4=DT. ENTR.&cab_5=HORA&cab_6=ENTREGADOR&cab_7=RECEBEDOR&cab_8=DOCUMENTO&cab_9=OCOR.&modulo=Operação&titulo_doc=&estilo=2&sentido=<?php echo $orienta_inc_hawb;?>&titulo_form=Altera Baixa de HAWB entregue"><b>Bip 3</b> - Altera Baixa <b>HAWB</b> Entregue</a></li>
							   <li role="separator" class="divider"></li>
							<?php } ?>
							<?php if($permissao_lid == 'S') { ?>
							   <li><a href="elabora_lista_devolucao_origem.php?modulo=OPERAÇÃO&titulo_form=Elabora Lista Devolução HAWB ao Cliente"><b>Bip 4</b> - Devolve <b>HAWB</b> ao Cliente</a></li>
							<?php } ?>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Relatório<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li id="li_operacao"><b>OPERAÇÕES</b></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprime_lista_entrega.php?&modulo=Relatórios">Lista de entrega</b></a></li>
							<li role="separator" class="divider"></li>
							<li id="li_cadastro"><b>CADASTROS</b></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=entregador">Entregadores</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=cliente">Clientes</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=ocorrencia">Ocorrencias</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=tab_preco">Tabela de Preço</a></li>
							<li role="separator" class="divider"></li>
							<li id="li_fatura"><b>FATURAMENTO</b></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprime_relatorio_fatura_periodo.php?&modulo=Relatorios Faturamento&titulo_form=Faturamento Por Período">Por Período</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprime_relatorio_fatura_cliente.php?&modulo=Relatorios Faturamento&titulo_form=Faturamento Por Cliente">Por Cliente</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprime_relatorio_fatura_escritorio.php?&modulo=Relatorios Faturamento&titulo_form=Faturamento Por Escritorio">Por Escritorio</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprime_relatorio_fatura_servico.php?&modulo=Relatorios Faturamento&titulo_form=Faturamento Por Serviço">Por Serviço</b></a></li>
						</ul>
					</li>
					
					<!--<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Relatorio Cadastros<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="imprime_lista_entrega.php?&modulo=Relatórios">Lista de entrega</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=entregador">Entregadores</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=cliente">Clientes</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=ocorrencia">Ocorrencias</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="imprimeRelatorio_cadastros.php?tipo_cada=tab_preco">Tabela de Preço</a></li>
						</ul>
					</li-->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Consulta<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="consulta_hawb.php?&modulo=Consulta&titulo_form=Consulta a HAWB">HAWB</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="mostra_historico_hawb.php?&modulo=Consulta&titulo_form=Hstorico de HAWB">Histórico HAWB</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configurações<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="atualiza_permissoes_usuario.php?modulo=CONFIGURAÇÕES&titulo=Atualização Permissões Usuarios Sistema&CI=&CA=&CE=">Permissões</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=tipo_pessoa&qtdc=2&titulo=Atualização Cadastro Tipo Pessoa&cab_1=CODIGO&cab_2=DESCRIÇÃO&codigo_sql=SQL_TPE&modulo=CONFIGURAÇÕESS&imprime=N&titulo_doc=&estilo=1">Tipo Pessoa</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="tela_entra_cadastro.php?tabela=categoria_pessoa&qtdc=2&titulo=Atualização Cadastro Categoria Pessoa&cab_1=CODIGO&cab_2=DESCRIÇÃO&codigo_sql=SQL_CPE&modulo=CONFIGURAÇÕES&imprime=N&titulo_doc=&estilo=1">Categoria Pessoa</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">Empresa Usuaria Sistema</a></li>
						</ul>
					</li>
					<?php if($cpf_usuario == '29843111915') { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Restrito<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="tela_entra_cadastro.php?tabela=cad_rotinas&qtdc=2&titulo=Cadastro Rotinas do Sistema&cab_1=CODIGO&cab_2=NOME ROTINA&titulo_doc=&codigo_sql=SQL_CRO&estilo=2&modulo=Restrito">Rotinas Sistema</b></a></li>
								<li role="separator" class="divider"></li>
								<li><a href="CriaTabela.php?modulo=RESTRITO">Trata Tabelas</b></a></li>
								<li role="separator" class="divider"></li>
								<li><a href="tela_entra_cadastro.php?tabela=sql_padrao&qtdc=3&titulo=Atualização Script SQLs Sistema&cab_1=CODIGO&cab_2=NOME SQL&cab_3=FINALIDADE&titulo_doc=&codigo_sql=SQL_SQL&estilo=2&modulo=Restrito">Cadastro SQL</b></a></li>
								<li role="separator" class="divider"></li>
								<li><a href="cria_formulario.php?tabela=aparencia_site&titulo=Aparencia Sistema&titulo_doc=Configurações Aparência Sistema&estilo=2&modulo=Restrito&acao=2&codigo_sql=SQL_APA">Aparencia Sistema</b></a></li>
							</ul>
						</li>
					<?php } ?>
				    <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sobre<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Propriedade</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">Estrutura</b></a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">Versão</a></li>
						</ul>
					</li>
					<li><a href="index.php">Sair</a></li>
				</ul>
			</div>
		</div>
		</nav>
		<div id="tabela_entrada">
		   <?php
           require_once('estatisticas.php');
		   ?>
	           <table class="table table-sm table-striped table-bordered table-hover">
		           <thead
				      <tr><th colspan="6" style="text-align:center; color:#ffffff; background-color:#4682B4;border: 1px solid #ffffff;">POSIÇÃO GERAL DOS TRABALHOS</th></tr>
					  <tr><th colspan="6" style="text-align:center; color:#ffffff; background-color: #000000;border: 1px solid #ffffff;"></th></tr>
					  <tr>
					     <th colspan="3" style="text-align:center; color:#ffffff; background-color:#4682B4;border: 1px solid #ffffff;">TRABALHOS NO MÊS EM CURSO</th>
						 <th colspan="3" style="text-align:center; color:#ffffff; background-color:#4682B4;border: 1px solid #ffffff;">TRABALHOS NO MÊS EM CURSO</th>
					  </tr>
			          <tr>
				         <th style="text-align:center; color:#ffffff; background-color:#363636;border: 1px solid #363636;">SITUAÇÃO</th>
				         <th style="text-align:center; color:#ffffff; background-color:#363636;border: 1px solid #363636;">N. POD´S</th>
						 <th style="text-align:center; color:#ffffff; background-color:#363636;border-rigth: 1px solid #ffffff;border-bottom: 1px solid #000000;">DETALHE</th>
						 <th style="text-align:center; color:#ffffff; background-color:#363636;border: 1px solid #363636;">SITUAÇÃO</th>
				         <th style="text-align:center; color:#ffffff; background-color:#363636;border: 1px solid #363636;">N. POD´S</th>
						 <th style="text-align:center; color:#ffffff; background-color:#363636;border: 1px solid #363636;">DETALHE</th>
			          </tr>
		           </thead>
		           <tbody>
				       <tr>
                          <td valign="middle" height="40" class="d-none d-sm-table-cell" >POD´s recebidos no Mes :</td><td valign="middle" class="dados_2"><?php echo number_format($total_m,0,',','.');?></td>
						  <td width="50" height="40" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_mes','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
						  <td valign="middle" class="d-none d-sm-table-cell">Não entregue no mês (Fora Prazo) :</td><td valign="middle" class="dados_2"><?php echo number_format($total_efp,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=ent_fora_prazo','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
					  </tr>
				       <tr>
                          <td valign="middle" height="40" class="d-none d-sm-table-cell">POD´s recebidos Hoje :</td><td valign="middle" class="dados_2"><?php echo number_format($total_d,0,',','.');?></td>
						  <td width="50" height="40" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_dia','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
					      <td valign="middle" class="d-none d-sm-table-cell">Devolvido Origem Hoje   :</td><td valign="middle" class="dados_2"><?php echo number_format($total_cd,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=dev_orig_hoje','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
                       </tr>
                       <tr>
                          <td valign="middle" class="d-none d-sm-table-cell">Na Base Para Entrega :</td><td valign="middle" class="dados_2"><?php echo number_format($total_b,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_base','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
					      <td valign="middle" class="d-none d-sm-table-cell">Devolvido Origem no Mês   :</td><td valign="middle" class="dados_2"><?php echo number_format($total_cm,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=dev_orig_mes','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
                       </tr>
                       <tr>
                          <td valign="middle" class="d-none d-sm-table-cell">Em Lista Entrega     :</td><td valign="middle" class="dados_2"><?php echo number_format($total_r,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_lista','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
					      <th colspan="3" style="text-align:center; color:#ffffff; background-color:#4682B4;border: 1px solid #ffffff; line-height:25px;">PENDÊNCIAS DE OUTROS MESES</th>
                       </tr>
                       <tr>
                          <td valign="middle" class="d-none d-sm-table-cell">Total Entregue Hoje  :</td><td valign="middle" class="dados_2"><?php echo number_format($total_h,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_hoje','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
						  <td valign="middle" class="d-none d-sm-table-cell">Entrega pendente outros meses  :</td><td valign="middle" class="dados_2"><?php echo number_format($total_fp,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=ent_outro_mes','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
                       </tr>
					   <tr>
                          <td valign="middle" class="d-none d-sm-table-cell">Total Entregue no Mês  :</td><td valign="middle" class="dados_2"><?php echo number_format($total_em,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_en_mes','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
						  <td valign="middle">Entregue em virada de mês (No Prazo)  :</td><td valign="middle" class="dados_2"><?php echo number_format($total_np,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=movi_vira_mes','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
                       </tr>
                       <tr>
                          <td valign="middle"></td><td valign="middle" class="dados_2"></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#"></a></td>
						  <td valign="middle">Prontas para Devolver Origem  :</td><td valign="middle" class="dados_2"><?php echo number_format($total_do,0,',','.');?></td>
                          <td width="50" align="center" class="d-none d-sm-table-cell"><a href="#" onClick="window.open('mostra_movimento_hawb.php?tipo=total_dev_origem','janela_1',
							   'scrollbars=yes,resizable=yes,width=920,height=550');" style="width:100;height:30;color=red;font: bold 16px Arial; text-align:center"><img src="img/lupa_b.png" border="none"></a></td>
                       </tr>
					   <tr><th colspan="6" style="text-align:center; color:#ffffff; background-color:#4682B4;border: 1px solid #4682B4; height:30px;"></th></tr>
		           </tbody>
	           </table>  
	    </div>
	</body>
</html>

