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
  <title>atualiza_permissões_sistema.php</title>
  <head>
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta http-equiv="content-type" content="text/html; charset=utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1">
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="css/bootstrap.min.css">
     <link rel="stylesheet" type="text/css" href="css/estilo_perfil_usuario.css" />
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
	if(isset($_GET['titulo'])){
		$titulo_form = $_GET['titulo'];
		$_SESSION['titulo_form_m'] = $_GET['titulo'];
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

	if(!isset($usuario)){
		$usuario = '';
	}
	
	//Chama a rotina que monta o cabeçalho da pagina
	require_once('cabeca_paginas_internas.php'); 
  
	//Chama a rotina que recebe os parametros passados pela rotina entrada.php
	require_once('recebe_parametros_entrada.php');
	?>
	<form method="POST" action="atualiza_permissoes_usuario.php">
	    <?php
		switch (get_post_action('atualiza','seleciona_usu')) {
			case 'atualiza':
			
			     $usuario = $_SESSION['usuario_m'];
				 
				 if($usuario == ''){
					 ?>
					 <script language="javascript"> window.location.href=("atualiza_permissoes_usuario.php")
					   alert('Você precisa selecionar um USUARIO !');
					 </script>
					 <?php
				 } 
				 else {
				     if(isset($_POST['progra'])){
						 $num=0;
						 foreach($_POST['progra'] as $rotina){

							 //retira espaços brancos do codigo da rotina - IMPORTANTE
							 $rotina =  str_replace(' ', '', $rotina);
							 
							 //Pega o nome do programa para gravar no arquivo
							 $verifi_r="SELECT nome_rotina FROM cad_rotinas
							 WHERE rotina='$rotina'";
							 $query_r = mysqli_query($cone,$verifi_r) or die ("Não foi possivel acessar o banco");
							 $total_r = mysqli_num_rows($query_r);
							 
							 for($ir=0; $ir < $total_r; $ir++){
							   $row = mysqli_fetch_row($query_r);
							   $nome_rotina       = $row[0];
							 }
							 //echo "<p>Qtdade Reg :".$total;
							 //echo "<p>Usuario :".$usuario;
							 //echo "<p>Nome Rotina :".$nome_rotina;
							
							 //Verifica se a permissão já esta gravada no arquivo

							$declara = "SELECT cpf FROM permissoes
							WHERE ((cpf = '$usuario') AND (rotina = '$rotina'))";
							$query_d = mysqli_query($cone,$declara) or die ("Não foi possivel acessar o banco");
							$achou = mysqli_num_rows($query_d);
							
							//echo "<p> Achou ".$achou;
							
							If ($achou == 0 ) {
							   // Isere o registro no arquivo permissoes
							   mysqli_query($cone,"INSERT INTO permissoes (cpf,rotina,nome_rotina)
										   VALUES ('$usuario','$rotina','$nome_rotina')");
							}
							else {
							   // apaga o registro o marcado
							   mysqli_query($cone,"DELETE FROM permissoes WHERE cpf='$usuario' AND rotina='$rotina'");
							}
						   $num++;
						 }
					 }
					 else {
						 ?>
						 <script language="javascript"> window.location.href=("atualiza_permissoes_usuario.php")
						   alert('Para relizar atualização, você tem que marcar alguma rotina !');
						 </script>
						 <?php
				     }
				 }
			break;
			case 'seleciona_usu':
				$usuario = $_POST['usuario'];
		        $_SESSION['usuario_m'] = $_POST['usuario'];
			break;
			default:
		}
	  ?>
	
      <table id="perfil_usu">
		  <thead>
		     <tr><td colspan="4" id="titulo_tab"><?php echo $titulo_form; ?></td></tr>
             <tr>
                <td colspan="4" id="busca_usu">					
					 <select id="usuario" name="usuario" class="form-control">
						<option value="">Selecione o Usuario</option>
						<?php
						if(isset($_SESSION['usuario_m'])){
							$usuario = $_SESSION['usuario_m'];
						}else {
							$usuario ='';
						}
						$sql2 = "SELECT cnpj_cpf,nome FROM pessoa WHERE ativo='S'";
						$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
						while ( $linha = mysqli_fetch_array($resul)) {
							$select = $usuario == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
						}					
						?>
					 </select>
					 <div id="sele_usu">
						<button class="btn btn-primary" type="submit" name="seleciona_usu">Seleciona</button>
					 </div>
				</td>
             </tr>
		  </thead>
		  <tbody>
			  <tr>
				 <th class="cabe_tab"><b>Rotina Sistema</b></th>
				 <th class="cabe_tab"><b>Status</b></th>
				 <th class="cabe_tab"><b>Rotina Sistema</b></th>
				 <th class="cabe_tab"><b>Status</b></th>
			  </tr>
			  <?php
				$sql = "SELECT rotina,nome_rotina
				FROM cad_rotinas ORDER BY rotina";
				$sql_q = mysqli_query($cone,$sql);
				$linhas = mysqli_num_rows($sql_q);
				if ($linhas==0) {
					echo "Não há rotinas cadastradas, verifique!";
				}
				else{
				  $conta=0;
				  $usuario = $_SESSION['usuario_m'];
				  echo "<div>";
				  echo "<tr>";
				  while ($x  = mysqli_fetch_array($sql_q)) {
					 $rotina      = $x['rotina'];
					 $nome_rotina = $x['nome_rotina'];
					 $loca_permi = mysqli_query($cone,"SELECT rotina FROM permissoes WHERE cpf='$usuario' AND rotina ='$rotina' ORDER BY rotina");
					 if (mysqli_num_rows($loca_permi)> 0) {
						if($conta > 1) {
						   echo "<tr>";
						   $conta=0;
						}
						echo "<td>";
						echo "<b>".$rotina." - "."</b><font face=\"arial\" size=\"2\"><input type =\"checkbox\" name = \"progra[]\" id=\"progra\" value=\"$rotina\">"."  -  ". $nome_rotina;
						echo "</td>";
						echo "<td><font face=\"arial\" size=\"1\" color=\"blue\"><b>SIM</b></font></td>";
					 }
					 else {
						if($conta > 1) {
						  echo "<tr>";
						  $conta=0;
						}
						echo "<td>";
						echo "<b>".$rotina." - "."</b><font face=\"arial\" size=\"2\"><input type =\"checkbox\" name = \"progra[]\" id=\"progra\" value=\" $rotina\">"."  -  ". $nome_rotina;
						echo "</td>";
						echo "<td><font face=\"arial\" size=\"1\" color=\"red\"><b>NÃO</b></font></td>";
					 }
					 $conta++;
					 if ($conta > 1) {
						echo "</tr>";
					 }
					 echo "</div>";
				  }
				}
				?>
				<tr>
				  <td colspan="2" align="left" style="width:20px;height:20px;background-color:#4682B4;">
					 <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
				  </td>
				  <td colspan="2" align="right" style="width:20px;height:20px;background-color:#4682B4;">
					 <button class="btn btn-primary" type="submit" name="atualiza"><span class="glyphicon glyphicon-plus"></span>Atualiza</button></a>
				  </td>
				</tr>
		  </tbody>
	  </table>
    </form>
</body>
</html>

