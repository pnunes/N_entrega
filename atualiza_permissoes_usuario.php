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

        if(!isset($usuario)){
			$usuario = '';
		}

		//Chama a rotina que monta o cabeçalho da pagina
        require_once('cabeca_paginas_internas.php'); 
	  
	    //Chama a rotina que recebe os parametros passados pela rotina entrada.php
	    require_once('recebe_parametros_entrada.php');
	  
	  ?>
      <form method="POST" action="atualiza_permissoes_usuario.php">
        <table class="table table-primary" id="perfil_usu">
          <?php
              switch (get_post_action('atualiza','seleciona_usu')) {
                    case 'atualiza':
                    
                         $cpf  = $_SESSION['cpf_m'];
                         $num=0;
                         foreach($_POST['progra'] as $rotina){

                             //Pega o nome do programa para gravar no arquivo

                             $verifi="SELECT nome_rotina FROM cad_rotinas
                             WHERE rotina='$rotina'";
                             $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco");
                             $total = mysqli_num_rows($query);

                             for($ic=0; $ic<$total; $ic++){
                               $row = mysqli_fetch_row($query);
                               $nome_programa       = $row[0];
                             }

                             //Verifica se a permissão já esta gravada no arquivo

                            $declara = "SELECT cpf FROM permissoes
                            WHERE ((cpf='$cpf') AND (rotina='$rotina'))";
                            $query = mysqli_query($cone,$declara) or die ("Não foi possivel acessar o banco");
                            $achou = mysqli_num_rows($query);
                            
                            If ($achou == 0 ) {
                               // Isere o registro no arquivo permissoes
                               mysqli_query("INSERT INTO permissoes (cpf,rotina,nome_rotina)
                                           VALUES ('$cpf','$rotina','$nome_rotina')");
                            }
                            else {
                               // apaga o registro o marcado
                               mysqli_query($cone,"DELETE FROM permissoes WHERE cpf='$cpf' AND rotina='$rotina'");
                            }
                           $num++;
                         }
                         unset($_SESSION['cpf_m']);
                    break;
					case 'seleciona_usu':
					    $usuario = $_POST['usuario'];
					break;
                    default:
              }
          ?>
          <?php
            /*if(isset($_GET['codigo'])) {
               $cpf = $_GET['codigo'];
               $_SESSION['cpf_m'] =$cpf;
            }
			else {
			   $cpf ='';
			}

            //Pega o nome da pessoa

            $verifi="SELECT nome FROM pessoa WHERE cpf='$cpf'";
            $query = mysqli_query($cone,$verifi) or die ("Não foi possivel acessar o banco");
            $total = mysqli_num_rows($query);

            for($ic=0; $ic<$total; $ic++){
               $row = mysqli_fetch_row($query);
               $nome        = $row[0];
            }
			if(!isset($nome)) {
				$nome = '';
			}*/
          ?>
		  <thead>
             <tr>
                <th colspan="4" id="titulo_tab">				   
					 <select id="usuario" name="usuario" class="form-control">
						<option value="">Selecione o Usuario</option>
						<?php
						
						$sql2 = "SELECT cpf,nome FROM pessoa WHERE ativo='S'";
						$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
						while ( $linha = mysqli_fetch_array($resul)) {
							$select = $usuario == $linha[0] ? "selected" : "";
							echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1] . "</option>";
						}					
						?>
					 </select>
					 <div id="sele_usu">
						<button class="btn btn-primary" type="submit" name="seleciona_usu">Envia</button>
					</div>
				</th>
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
				FROM cad_rotinas
				ORDER BY nome_rotina";
				$sql_q = mysqli_query($cone,$sql);
				$linhas = mysqli_num_rows($sql_q);
				if ($linhas==0) {
					echo "Não há rotinas cadastradas, verifique!";
				}
				else{
				  $conta=0;
				  echo "<div>";
				  echo "<tr>";
				  while ($x  = mysqli_fetch_array($sql_q)) {
					 $programa      = $x['rotina'];
					 $nome_programa = $x['nome_rotina'];
					 $loca_permi = mysqli_query($cone,"SELECT rotina FROM permissoes WHERE cpf='$usuario' AND rotina ='$programa'");
					 if (mysqli_num_rows($loca_permi)> 0) {
						if($conta > 1) {
						   echo "<tr>";
						   $conta=0;
						}
						echo "<td>";
						echo "<b>".$programa."</b><font face=\"arial\" size=\"1\"><input type =\"checkbox\" name = \"progra[]\" id=\"progra\" value=\"$programa\">$nome_programa";
						echo "</td>";
						echo "<td><font face=\"arial\" size=\"1\"><b>SIM</b></font></td>";
					 }
					 else {
						if($conta > 1) {
						  echo "<tr>";
						  $conta=0;
						}
						echo "<td>";
						echo "<b>".$programa."</b><font face=\"arial\" size=\"1\"><input type =\"checkbox\" name = \"progra[]\" id=\"progra\" value=\"$programa\">$nome_programa";
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

