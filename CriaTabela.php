<?php
   if ( session_status() !== PHP_SESSION_ACTIVE ) {
      session_start();
   }
   require_once('Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   $banco = $_SESSION['bancoD'];

   if(!isset($tabela)) {
       $tabela='';
   } 
   if(!isset($resp_grava)){
       $resp_grava ='';
       $_SESSION['mensagem'] = $resp_grava; 
   }
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script src="js/bootstrap.min.js"></script> 
    <link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="js/bootstrap-datepicker.min.js"></script> 
    <script src="js/bootstrap-datepicker.pt-BR.min.js" charset="UTF-8"></script> 
    <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" type="text/css" href="css/estilo_cria_tabela.css"> 
    <title>Cria Estrutura sistema</title>
</head>
<body>
   <?php
       function get_post_action($name) {
           $params = func_get_args();
           foreach ($params as $name) {
              if (isset($_POST[$name])) {
                  return $name;
              }
           }
       }

       switch (get_post_action('sel_tabela','atualiza')) {
            case 'sel_tabela':
                 $tabela = $_POST['tabela'];
                 $_SESSION['tab_m'] = $tabela;
            break;
			case 'atualiza':
                 //pega o nome da tabela em uso
				  $tabela = $_SESSION['tab_m'];
			 
			      $nome       = $_POST['nome_c'];
				  $tipo       = $_POST['tipo_c'];
                  $tamanho    = $_POST['tama_c'];
                  $chave      = $_POST['indice_c'];
				  $etiqueta   = $_POST['etiqueta_c'];
                  $uso        = $_POST['uso_c'];
				  $ordem      = $_POST['ordem_c'];
                  $rela_tab   = $_POST['selec_c'];
				  $extra      = $_POST['extra_c'];
				  $li=0;
				  $inc=0;
				  $alt=0;
					 
				  foreach( $nome as $key => $li ) {
                        //echo "<p>".$li." - ".$tipo[$key]." - ".$tamanho[$key]." - ".$chave[$key]." - ".$etiqueta[$key]." - ".$uso[$key]." - ".$ordem[$key]." - ".$rela_tab[$key]." - ".$extra[$key];
                    
                        //Verifica se o registro ja existe na tabela estrutura_tabela
                        $verifica = "SELECT controle FROM estrutura_tabela WHERE tabela = '$tabela' AND campo='$li'";
                        $procura = mysqli_query($cone,$verifica);
                        $regis = mysqli_num_rows($procura);
						
                        if($regis > 0){
                            $alteracao = "UPDATE estrutura_tabela SET tabela='$tabela',campo='$li',tipo='$tipo[$key]',indice='$chave[$key]',
                            tamanho='$tamanho[$key]',etiqueta='$etiqueta[$key]',uso='$uso[$key]',ordem='$ordem[$key]',relaciona_tab='$rela_tab[$key]',
                            extra='$extra[$key]'
                            WHERE tabela = '$tabela' AND campo='$li'";
                            $altera = mysqli_query($cone,$alteracao);
                            if($altera){
                                $alt = $alt+1;
                            }
                        }else {
                            $inclusao = "INSERT INTO estrutura_tabela (tabela,campo,tipo,tamanho,etiqueta,uso,ordem,relaciona_tab,indice,extra) 
                            VALUES('$tabela','$li','$tipo[$key]','$tamanho[$key]','$etiqueta[$key]','$uso[$key]','$ordem[$key]','$rela_tab[$key]','$chave[$key]','$extra[$key]')";
                            $inclui = mysqli_query($cone,$inclusao);
                            if($inclui){
                                $inc = $inc+1;
                            }
                        }
                        		   
				  }
				  $resp_grava ="Foram incluidos :".$inc." e Alterados :".$alt." Registros";
				  $_SESSION['mensagem'] =$resp_grava; 
				  
            break;
            default:
       }
       // Pega variaveis mandadas na entrada
	   if(isset($_GET['modulo'])){
		   $modulo = $_GET['modulo']; 
           $_SESSION['modulo_m'] = $modulo; 		   
	   }
       require_once('cabeca_paginas_internas.php'); 
   ?>
   <br>
   <form name="tabelas_banco" class="form-horizontal" id="tabelas_banco" action="CriaTabela.php" method="post"> 
     <table id="t_campos_tab" class="table table-striped">
        <thead id="cabe_tab">
            <tr>
                <td id="titulo_tab" colspan="10">
                    MOSTRA ESTRUTURA DAS TABELAS DO BANCO DE DADOS
                </td>
            </tr>
            <tr>
                <td id="t_pesquisa" colspan="10">Selecione a Tabela
                    <select name="tabela" id="sele_tab">
                        <?php
                         $pega_tab ="SHOW TABLES";
                         $resul    = mysqli_query($cone,$pega_tab);
                         while ($linha = mysqli_fetch_array($resul)) {
                            $select = $tabela == $linha[0] ? "selected" : "";
                            echo "<option value=\"". $linha[0] . "\" $select>" . $linha[0] . "</option>";
                         }
                        ?>
                    </select>
                    <input name="sel_tabela" type="submit" value="Enviar" id="bt_enviar" class="btn btn-primary">
                </td>
            </tr>
            <tr>
               <td id="col_1" class="t_cab">CAMPO</td>
               <td id="col_2" class="t_cab">TIPO</td>
               <td id="col_3" class="t_cab">TAMA.</td>
               <td id="col_4" class="t_cab">INDICE</td>
               <td id="col_5" class="t_cab">USA</td>
               <td id="col_6" class="t_cab">ETIQUETA</td>
               <td id="col_7" class="t_cab">SELEÇÃO</td>
               <td id="col_8" class="t_cab">ORDEM</td>
               <td id="col_9" class="t_cab">DETALHES</td>
               <td id="col_10" class="t_cab">AÇÃO</td>
            </tr>
        </thead>
        <tbody id="corpo_tab">
            <?php
			    $pega_base = "SELECT COLUMN_NAME,COLUMN_TYPE,COLUMN_KEY,COLUMN_COMMENT,EXTRA FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = '$banco' AND TABLE_NAME = '$tabela'";
                $mostra_base = mysqli_query($cone,$pega_base);
                $n_base = mysqli_num_rows($mostra_base);
                
                $captura_dados = "SELECT campo,tipo,tamanho,indice,uso,etiqueta,
                relaciona_tab,ordem,extra,controle FROM estrutura_tabela WHERE tabela ='$tabela'";
                $pega_dados = mysqli_query($cone,$captura_dados);
                $tot_estru = mysqli_num_rows($pega_dados);

                if($tot_estru <= $n_base){ // Atualiza e inclui novos registros na tabela estrutura_tabela
                    for($i=0; $i<$n_base; $i++) {
                        $row = mysqli_fetch_row($mostra_base);
                        echo "<tr>";
                            echo "<td id=\"col_1\">";
                                echo "<input type=\"text\" name=\"nome_c[]\" size=\"10\" value =\"$row[0]\" maxlength=\"10\" id=\"n_campo\" class=\"campo\">";
                            echo "</td>"; 
                            $tipo = str_replace(preg_replace('/[^0-9]/', '', $row[1]),'',$row[1]);
                            $tipo1 = preg_replace( '~\(.*\)~' , "", $tipo ); 
                            echo "<td id=\"col_2\">";
                                echo "<input type=\"text\" name=\"tipo_c[]\" size=\"10\" value =\"$tipo1\" maxlength=\"10\" id=\"t_tipo\" class=\"campo\">";
                            echo "</td>";
                            $tamanho = preg_replace('/[^0-9]/', '', $row[1]); 
                            echo "<td id=\"col_3\">";  
                                echo "<input type=\"text\" name=\"tama_c[]\" size=\"8\" value =\"$tamanho\" maxlength=\"8\" id=\"t_tama\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_4\">";
                                echo "<input type=\"text\" name=\"indice_c[]\" size=\"5\" value =\"$row[2]\" maxlength=\"5\" id=\"t_indice\" class=\"campo\">";
                            echo "</td>"; 
                            
                            // Pega dados registrados na tabela estrutura_tabela dos campos
                            $loca_Campo = "SELECT uso,etiqueta,relaciona_tab,ordem,extra
                            FROM estrutura_tabela WHERE tabela ='$tabela' AND campo='$row[0]'";
                            $locali_campo = mysqli_query($cone,$loca_Campo);
                            $to_pegou = mysqli_num_rows($locali_campo);
                            if($to_pegou > 0) {
                                for($l=0; $l < $to_pegou; $l++) {
                                    $lin = mysqli_fetch_row($locali_campo);
                                    $uso      = $lin[0];
                                    $etiqueta = $lin[1];
                                    $rel_tab  = $lin[2];
                                    $orde     = $lin[3];
                                    $extra    = $lin[4];
                                }
                            }else {
								$uso      ='';
								$etiqueta ='';
								$rel_tab  ='';
								$orde     ='';
								$extra    ='';
							}
                            echo "<td id=\"col_5\">";
                                echo "<input type=\"text\" name=\"uso_c[]\" size=\"5\" value =\"$uso\" maxlength=\"5\" id=\"t-uso\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_6\">";
                                echo "<input type=\"text\" name=\"etiqueta_c[]\" size=\"25\" value =\"$etiqueta\" maxlength=\"25\" id=\"t_etique\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_7\">";
                                echo "<input type=\"text\" name=\"selec_c[]\" size=\"15\" value =\"$rel_tab\" maxlength=\"15\" id=\"t_selec\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_8\">";
                                echo "<input type=\"text\" name=\"ordem_c[]\" size=\"5\" value =\"$orde\" maxlength=\"5\" id=\"t_ordem\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_9\">";
                                echo "<input type=\"text\" name=\"extra_c[]\" size=\"10\" value =\"$extra\" maxlength=\"10\" id=\"t_sele\" class=\"campo\">";
                            echo "</td>";
                            if($to_pegou > 0) {
                                echo "<td id=\"col_10\">";
                                    echo "<img src=\"img/conferido.png\">";
                                echo "</td>";
                            }
                        echo "</tr>";
                    }
                }
                if($tot_estru > $n_base){ //exclui registros da tabela estrutura_tabela
                    for($ic=0; $ic<$tot_estru; $ic++) {
                        $mostra = mysqli_fetch_row($pega_dados);
                        echo "<tr>";
                            echo "<td id=\"col_1\">";
                                echo "<input type=\"hidden\" name=\"controle\" value = \"$mostra[8]\" size=\"5\" maxlength=\"5\" id=\"n_campo\" class=\"campo\">";
                                echo "<input type=\"text\" name=\"nome_c[]\" value = \"$mostra[0]\" size=\"20\" maxlength=\"20\" id=\"n_campo\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_2\">";
                                echo "<input type=\"text\" name=\"tipo_c[]\" value =\"$mostra[1]\" size=\"15\" maxlength=\"15\" id=\"t_campo\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_3\">";  
                                echo "<input type=\"text\" name=\"tama_c[]\" value =\"$mostra[2]\" size=\"8\" maxlength=\"8\" id=\"t_tama\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_4\">";
                                echo "<input type=\"text\" name=\"indice_c[]\" value =\"$mostra[3]\" size=\"5\" maxlength=\"5\" id=\"uso_c\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_5\">";
                                echo "<input type=\"text\" name=\"uso_c[]\" value =\"$mostra[4]\" size=\"5\" maxlength=\"5\" id=\"uso_c\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_6\">";
                                echo "<input type=\"text\" name=\"etiqueta_c[]\" value =\"$mostra[5]\" size=\"10\" maxlength=\"10\" id=\"t_indice\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_7\">";
                                echo "<input type=\"text\" name=\"selec_c[]\" value =\"$mostra[6]\" size=\"15\" maxlength=\"15\" id=\"t_etique\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_8\">";
                                echo "<input type=\"text\" name=\"ordem_c[]\" value =\"$mostra[7]\" size=\"5\" maxlength=\"5\" id=\"t_sele\" class=\"campo\">";
                            echo "</td>";
                            echo "<td id=\"col_9\">";
                                echo "<input type=\"text\" name=\"extra_c[]\" value =\"$mostra[8]\" size=\"15\" maxlength=\"15\" id=\"t_sele\" class=\"campo\">";
                            echo "</td>";

                            //Procura na tabela base se o campo realmente existe, se não existir é excluido
                            $veri_campo = "SELECT COLUMN_NAME,COLUMN_TYPE,COLUMN_KEY,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS 
                            WHERE TABLE_SCHEMA = '$banco' AND TABLE_NAME = '$tabela' AND COLUMN_NAME='$mostra[0]'";
                            $busca_campo = mysqli_query($cone,$veri_campo);
                            $t_regi = mysqli_num_rows($busca_campo);
                            if($t_regi == 0) { 
                                echo "<td id=\"col_10\">";
                                echo "<a href=\"exclui_campo_tabela.php?codigo=$mostra[9]\"><img src=\"img/deleta.png\" title=\"Exclui\"></a>";
                                echo "</td>";
                            }
                            if($t_regi > 0){
                                echo "<td id=\"col_10\">";
                                    echo "<img src=\"img/conferido.png\">";
                                echo "</td>";
                            }
                        echo "</tr>";
                    } 
                }
                echo "<tr>";
                   echo "<td colspan=\"10\" id=\"col_6\">";
                   echo "<input name=\"atualiza\" type=\"submit\" value=\"Atualizar\" id=\"bt_salva\" class=\"btn btn-primary glyphicon glyphicon-pencil\">";
                   echo "<div id=\"resposta\">";
                         $resp_grava = $_SESSION['mensagem'];
                         echo $resp_grava;
                   echo "</div>";
                   echo " <a href=\"entrada.php\"><button name=\"volta\" type=\"button\" id=\"bt_volta\" class=\"btn btn-primary\">Voltar</button></a>";
                   echo "</td>";
                echo "</tr>";
            ?>
        </tbody>
     </table>
   </form>
</body>
</html>