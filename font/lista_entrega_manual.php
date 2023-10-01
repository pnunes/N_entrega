<?php
  if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
	require_once('Conexao.php');
	$conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');
?>
<html>
  <title>Lista Entrega Manual</title>
  <head>
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta http-equiv="content-type" content="text/html; charset=utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1">
	 <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
	 <script src="js/jquery.min.js"></script>
     <script src="js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="css/bootstrap.min.css">
     <link rel="stylesheet" type="text/css" href="css/estilo_perfil_entregador.css" />
  </head>
  <body>
      <?php
	    // Função para pegar o name dos submits
		function get_post_action($name) {
			$params = func_get_args();
			foreach ($params as $name) {
			   if (isset($_POST[$name])) {
				   return $name;
			   }
			}
		}
		////////////////////////////////////////////////////////////////////////
		//Pega variavel que identifica função do formulario
		if(isset($_GET['titulo_form'])){
			$titulo_form = $_GET['titulo_form'];
			$_SESSION['titulo_form_m'] = $_GET['titulo_form'];
		}else {
			$titulo_form = $_SESSION['titulo_form_m'];
		}
		
		////////////////////////////////////////////////////////////////////////
		//Pega variavel que identifica qual parte do sistema esta sendo usado
		if(isset($_GET['modulo'])){
			$modulo = $_GET['modulo'];
			$_SESSION['modulo_m'] = $_GET['modulo'];
		}
		
		if(!isset($resp_grava)){
			$resp_grava='';
		}
		if(!isset($cor_msg)) {
			$cor_msg ='';
		}

        if(!isset($entregador)){
			$entregador = '';
		}
		if(!isset($conta)){
			$conta =0;
		}

		//Chama a rotina que monta o cabeçalho da pagina
        require_once('cabeca_paginas_internas.php'); 
	  
	    //Chama a rotina que recebe os parametros passados pela rotina entrada.php
	    require_once('recebe_parametros_entrada.php');
		
		
		switch (get_post_action('grava','gn_lista')) {
			case 'gn_lista':
		        // Setando o padrão de hora para pegar a hora correta.
		        date_default_timezone_set('America/Sao_Paulo');
				
		        // Salvando a data da lista e guardando numa variavel global
                $data_lista = $_POST['data_lista'];
				$_SESSION['data_lista_m'] = $data_lista;
				
				$dt_lista     = explode("/",$data_lista);
			    $v_dt_lista_g = $dt_lista[2]."-".$dt_lista[1]."-".$dt_lista[0];
				$_SESSION['data_lista_g'] = $v_dt_lista_g;
				
				// Salvando o codigo do entregador e gravando numa variavel global
				$entregador = $_POST['entregador'];
				$_SESSION['entregador_m'] = $entregador;
				
				// Tirando as barras da data para compor o numero da remessa
				$d_lista    = str_replace('/','',$data_lista);
				
				// Pegando a hora, tirando os caracteres : para compor o numero da remessa
                $h_lista    = date('h:i:s');
				$h_lista    = str_replace(':','',$h_lista);

                // Compondo o numero da lista com data + hora,minuto e segundo e guardando numa variavel global 
                $nu_lista = $d_lista.$h_lista;
                $_SESSION['nu_lista_m'] = $nu_lista;				
              
            break;
			case 'grava':
			     
			     if(!isset($_SESSION['entregador_m']) OR !isset($_SESSION['data_lista_g']) OR !isset($_SESSION['nu_lista_m'])){
					 $resp_grava = "Você esqueceu de definir data, entregador, e gerar o numero da lista!";
				 } else {
					 $entregador   = $_SESSION['entregador_m'];
					 $v_dt_lista_g = $_SESSION['data_lista_g'];
					 $nu_lista     = $_SESSION['nu_lista_m'];
					 $usuario      = $_SESSION['cpf_m'];
					 $hora         = date('H:i');
					 
					 $nao=0;
					 $sim=0;
					 foreach($_POST['hawb_sele'] as $nu_hawb){
						$atualiza = "UPDATE movimento SET entregador='$entregador',dt_lista_entrega='$v_dt_lista_g',
						nu_lista_entrega='$nu_lista',ocorrencia='02',estatus_hawb='BIP2'
						WHERE n_hawb = '$nu_hawb'";
						
						if(mysqli_query($cone,$atualiza)or die ("Não foi possivel atualizar a tabela MOVIMENTO.")){
							$sim++;
						}else {
							$nao++;
						}
						
						//Pega o codigo de barra da hawb
						$pega_codbarra = "SELECT cod_barra FROM movimento WHERE n_hawb = '$nu_hawb'";
						$query_barra = mysqli_query($cone,$pega_codbarra) or die ("Nao foi possivel acessar o banco");
						$total = mysqli_num_rows($query_barra);
						if($total > 0){
						   for($ic=0; $ic<$total; $ic++){
							  $row = mysqli_fetch_row($query_barra);
							  $cod_barra        = $row[0];
						   }
						}else {
						   $cod_barra        = $nu_hawb;
						}
						
						//Atualizando a tabela historico_hawb
						$atualiza_historico ="INSERT INTO historico_hawb (n_hawb,ocorrencia,dt_evento,usuario,hora_registro,cod_barra)
						VALUES ('$nu_hawb','02','$v_dt_lista_g','$usuario','$hora','$cod_barra')";
						mysqli_query($cone$atualiza_historico);
						
					 }
					 $resp_grava = "$sim HAWBs foram adicionadas a lista, $nao apresentaram problema. Verifique";
				 }
			break;
			default:
	  }

	  ?>
      <form method="POST" action="lista_entrega_manual.php">
        <table class="table table-primary" id="tb_dados_fixos_lista">
		  <thead>
                 <tr>
				     <td id="t_form" colspan="2"><?php echo $titulo_form;?></td>
				 </tr>
				 <tr>
				    <td class="col-md-7" id="c_dt_entrega">
						<div class="input-group">
						<?php if(isset($_SESSION['data_lista_m'])) {
							 $data_lista = $_SESSION['data_lista_m'];
						} else {
							$data_lista = date('d/m/Y');
						}?>
						<span class="input-group-addon">Data Lista</span>
						<div class="input-group date">
								<input type="text" class="form-control" id="data_lista" value="<?php echo $data_lista;?>" name="data_lista">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<script type="text/javascript">
								$('#data_lista').datepicker({	
								format: "dd/mm/yyyy",	
								language: "pt-BR",
								/*startDate: '+0d',*/
							});
						</script>
						</div>	
					</td>
					<td class="input-group" id="c_entregador">
						<?php if(isset($_SESSION['entregador_m'])) {
							$entrega = $_SESSION['entregador_m'];
						} else {
							$entrega ='';
						}?>
						<span class="input-group-addon">Entregador</span>
						<div class="col-md-13">
							<select id="entregador" name="entregador" class="form-control">
							    <option value="">Selecione o Entregador</option>
								<?php
								$sql2 = "SELECT cnpj_cpf,nome,cidade,estado FROM pessoa WHERE ativo='S' AND (categoria='02' OR categoria='04')";
								$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
								while ( $linha = mysqli_fetch_array($resul)) {
									$select = $entregador == $linha[0] ? "selected" : "";
									echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1].' - '.$linha[2].' - '.$linha[3]."</option>";
								}					
								?>
							</select>
						</td>
					</div>	
				</tr>
				<tr> 
                   <td colspan="2" id="n_lista_entrega">				
					   <div class="col-md-3" id="n_lista">
						   <?php if(isset($_SESSION['nu_lista_m'])) {
							  $nu_lista = $_SESSION['nu_lista_m'];
						   }else {
							  $nu_lista =''; 
						   }
						   ?>
						   <div class="input-group">
							  <span class="input-group-addon">Numero Lista</span>
							  <input id="nu_lista" name="nu_lista" class="form-control" type="text" value ="<?php echo $nu_lista;?>">
						   </div>	
					   </div>
					   <button type="Submit" id="gn_lista_cabe" name="gn_lista" class="btn btn-primary">Gera Numero Lista</button>
				   </td>
				</tr>
		  </thead>
		</table>
		<div id="mensagem">
		  <div class="alert alert-success" role="alert" class="msg_conteudo">
			  <?php echo $resp_grava; ?>
		  </div>
	    </div>
		<div class="table-wrapper">
			<table  id="tab_lista">
			  <thead>
				  <tr>
					 <th class="cabe_tab"><b>HAWB</b></th>
					 <th class="cabe_tab"><b>DESTINO</b></th>
					 <th class="cabe_tab"><b>RUA</b></th>
					 <th class="cabe_tab"><b>BAIRRO</b></th>
				  </tr>
			  </thead>
			  <tbody>
				  <?php
					$sql = "SELECT n_hawb,nome_desti,rua_desti,bairro_desti
					FROM movimento
					WHERE entregador=''
					ORDER BY rua_desti,bairro_desti";
					$sql_q = mysqli_query($cone,$sql);
					$linhas = mysqli_num_rows($sql_q);
					if ($linhas==0) {
						echo "Não existe HAWB para entrega, verifique!";
					}
					else{
					  $conta=0;
					  while ($x  = mysqli_fetch_array($sql_q)) {
						 $hawb         = $x['n_hawb'];
						 $nome_desti   = $x['nome_desti'];
						 $rua_desti    = $x['rua_desti'];
						 $bairro_desti = $x['bairro_desti'];
						 
						 echo "<tr class=\"l_dados\">";
						 echo "<td>";
						 echo "<b>".$hawb." - </b><font face=\"arial\" size=\"2\"><input type =\"checkbox\" name = \"hawb_sele[]\" id=\"hawb_sele\" value=\"$hawb\"";
						 echo "</td>";
						 echo "<td>";
						 echo "<font face=\"arial\" size=\"2\">".$nome_desti;
						 echo "</td>";
						 echo "<td>";
						 echo "<font face=\"arial\" size=\"2\">".$rua_desti;
						 echo "</td>";
						 echo "<td>";
						 echo "<font face=\"arial\" size=\"2\">".$bairro_desti;
						 echo "</td>";
						 $conta++;
						 echo "</tr>";
					  }
					$_SESSION['conta_me'] = $conta;
					}
					?>
			  </tbody>
		  </table>
	   </div>
	   <div id="rodape">
			 <div id="bt_volta">
				 <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
			 </div>
			 <div id="tot_hawb">
			     <?php if(isset($_SESSION['conta_me'])){
					 $conta = $_SESSION['conta_me'] 
				 } else {
					 $conta =0;
				 }?>
				 <b>Total HAWB:<?php echo $conta; ?></b>
			 </div>
			 <div id="bt_grava">
				 <button class="btn btn-primary" type="submit" name="grava" id="bt_grava"><span class="glyphicon glyphicon-plus"></span>Gravar</button></a>
			 </div>
	   </div>
    </form>
</body>
</html>

