<?php
    /*///////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
													/*A  T  E  N  Ç  Ã  O */
	/*Esta rotina é responsavel por montar o formulário para os casos de alteracao e exclusão - ela recebe parametros da
    rotina - entrada.php que chega até arotina tela_entra_cadastro.php por GET e depois os parametros necessário para esta
    rotina são repassados por variaveis globais através de $_SESSSION[].
	*/

    //Pega na tabela ESTRUTURA_TABELA os campos da tabela que comporão o formulario.
    
    $pega_campos_tab ="SELECT campo,tipo,tamanho,etiqueta,indice,relaciona_tab,extra 
    FROM estrutura_tabela
    WHERE tabela = '$tabela' AND uso='S'
    ORDER BY ordem";
	
	$query_mostra = mysqli_query($cone,$pega_campos_tab);
	$total_campos = mysqli_num_rows($query_mostra);	
	
    if($total_campos > 0) {
		for($ii=0; $ii<$total_campos; $ii++){
			$pega_campos = mysqli_fetch_row($query_mostra); 
			$nome_campo      = $pega_campos[0];
			$tipo_campo      = $pega_campos[1];
			$tama_campo      = $pega_campos[2];
			$etiqueta        = $pega_campos[3];
			$campo_pesquisa  = $pega_campos[4];
			$liga_tab        = $pega_campos[5];
			$extra           = $pega_campos[6];
			
			//verifica se o campo chave e do tipo autoincrement
			if($ii < ($total_campos -1)) {
				if($extra<>'auto_increment'){
					$campos.=$nome_campo.",";
				}
			}
			else{
				if($extra<>'auto_increment'){
					$campos.=$pega_campos[0];
				}
			}
			
			//Pega o nome do campo chave primaria da tabela
			if($campo_pesquisa <>'') {
				$campo_pesquisa         = $pega_campos[0];
				$_SESSION['c_indice_m'] = $pega_campos[0]; 				
			}
	
			//Pega a chave de pesquisa repassada pela rotina cria_formulario.php, 
			//que por sua vez recebeu da rotina tela_entra_cadastro.php
			
			$chave_pesquisa = $_SESSION['chave_pesquisa_m'];
			
			//pega o campo de pesquisa que foi colocado na variavel global 
			if(isset($_SESSION['c_indice_m'])){					   
				$campo_pesquisa   = $_SESSION['c_indice_m'];
			}
			else {
				$campo_pesquisa   =''; 
			}
			/*echo "<p>String Select :".$pega_campos_tab;
			echo "<p>Nome do campo :".$nome_campo;
			echo "<p>SQL :".$liga_tab;
		    echo "<p>Tabela :".$tabela;
			echo "<p>Valor chave pesquisa :".$chave_pesquisa;
			echo "<p>Campo chave pesquisa :".$campo_pesquisa;*/
			
			//Aqui inicia processo de montagem do formulário com valores referente ao registro selecionado no form tela_entra_cadastro.php
			//este procedimento se reprete para cada campo do formulario
			if($nome_campo<>'') {
				if($campo_pesquisa<>''){
					$localiza = "SELECT $nome_campo FROM $tabela WHERE $campo_pesquisa='$chave_pesquisa'";
				}else {
					$localiza = "SELECT $nome_campo FROM $tabela";
				}
				//echo "<p>Script :".$localiza;
				$query_1 = mysqli_query($cone,$localiza) or die (mysqli_errno($cone)." - ".mysqli_error($cone));
				$total_1 = mysqli_num_rows($query_1);
				if($total_1 > 0) {
					for($i=0; $i<$total_1; $i++){
						$mostra = mysqli_fetch_row($query_1);
						$var_campo           = $mostra[0];
					}
				}else {
					$var_campo ='';
			    }
				////////////////////mostrar campo e seu conteudo para testes//////////////
				/*echo "<p>Contuedo Campo :".$var_campo;
				echo "<p>ID do Campo :".$nome_campo;
				echo "<p>Tipo do Campo :".$tipo_campo;*/
				/////////////////////////////////////////////////////////////////////////
		
				//echo "<p> Campo: ".$nome_campo." - tipo: ".$tipo_campo." - Tama: ".$tama_campo." - Eique: ".$etiqueta." - Indice: ".$campo_pesquisa." - Liga Tab: ".$liga_tab;
		
				if($tipo_campo == 'date') {
					if($var_campo<>''){
					   $data = explode("-",$var_campo);
					   $var_campo = $data[2]."/".$data[1]."/".$data[0];
					}
					?>
					<tr>
					<td><b><?php echo $etiqueta;?> :</b></td>
					<td>
					<div class="form-group">
						<div class="col-sm-10">
							<div class="input-group date">
								<input type="text" class="form-control" id="<?php echo $nome_campo;?>" value ="<?php echo $var_campo;?>" name="<?php echo $nome_campo;?>">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
							</div>
						</div>
						</div>
						<script type="text/javascript">
							$('#<?php echo $nome_campo;?>').datepicker({	
							format: "dd/mm/yyyy",	
							language: "pt-BR",
							/*startDate: '+0d',*/
							});
						</script>
					</td>
					</tr>
					<?php				 
				}
				if($tipo_campo == 'blob' or $tipo_campo == 'mediumtext' or $tipo_campo == 'longblob'){ ?>
					<tr>
					<td><b><?php echo $etiqueta;?></b></td>				  
					<td><textarea class="form-control" id="<?php echo $nome_campo;?>" name="<?php echo $nome_campo;?>" rows="3" cols="60" class="campo"><?php echo $var_campo;?></textarea></td>
					</tr>
					<?php
				}
				//if($tipo_campo == 'longblob'){ ?>
					<!--<tr>
					<td><b><?php //echo $etiqueta;?></b></td>				  
					<td><textarea class="form-control" id="<?php echo $nome_campo;?>" name="<?php echo $nome_campo;?>" rows="6" cols="60" class="campo"><?php echo $var_campo;?></textarea></td>
					</tr>-->
					<?php
				//}
				if($tipo_campo == 'char' or $tipo_campo == 'varchar') {
					if($liga_tab <>'') {
						$pega_sele ="SELECT escript_sql FROM sql_padrao WHERE codigo ='$liga_tab'";
						
						////////////////mostra estrutura sql para verificação///////////
						//echo "Script :".$pega_sele;
						///////////////////////////////////////////////////////////////
						
						$query_sele = mysqli_query($cone,$pega_sele);
						$total_sele = mysqli_num_rows($query_sele);
						if($total_sele >0){
							for($i=0; $i<$total_sele; $i++){
								$mostra_sele = mysqli_fetch_row($query_sele); 
								$script_sele = $mostra_sele[0];
							}
						}
						?>
						<tr>
							<td><b><?php echo $etiqueta;?></b></td>
							<td>
							<select name="<?php echo $nome_campo;?>" class="campo">
								<option>Selecione</option>
								<?php
								$sql2 = $script_sele;
								
								////////////////mostra estrutura sql para conferencia///////////////////////
								//echo "SQL 2 :".$sql2;
								///////////////////////////////////////////////////////////////////////////
								
								$resul = mysqli_query($cone,$sql2) or die ("Não foi possivel acessar o banco");
								while ( $linha = mysqli_fetch_array($resul)) {
									$select = $var_campo == $linha[0] ? "selected" : "";
									echo "<option value=\"". $linha[0] . "\" $select>" . $linha[1]."</option>";
								}
								?>
							</select>
							</td>
						</tr>
						<?php
					}
					else {
						?>
							<tr>
								<td><b><?php echo $etiqueta;?></b></td>
								<td><input type="<?php echo $tipo_campo;?>" name="<?php echo $nome_campo;?>" size="<?php echo $tama_campo;?>" value="<?php echo $var_campo;?>" maxlength="<?php echo $tama_campo;?>" id="<?php echo $nome_campo;?>" class="campo"></td>
							</tr>
						<?php
					}
				}
				if($tipo_campo == 'int' AND $extra <> 'auto_increment') {
					?>
						<tr>
							<td><b><?php echo $etiqueta;?></b></td>
							<td><input type="<?php echo $tipo_campo;?>" name="<?php echo $nome_campo;?>" size="<?php echo $tama_campo;?>" value="<?php echo $var_campo;?>" maxlength="<?php echo $tama_campo;?>" id="<?php echo $nome_campo;?>" class="campo"></td>
						</tr>
					<?php 
				}
				if($tipo_campo == 'decimal') {
					?>
						<tr>
							<td><b><?php echo $etiqueta;?></b></td>
							<td><input type="<?php echo $tipo_campo;?>" name="<?php echo $nome_campo;?>" size="<?php echo $tama_campo;?>" value="<?php echo $var_campo;?>" maxlength="<?php echo $tama_campo;?>" id="<?php echo $nome_campo;?>" class="campo"></td>
						</tr>
					<?php 
				}
			}//fim do if campo				   
		}
	}
	//coloca o conteudo da variavel $campos numa variavel global para ser recuparada na rotina cria_form_alteracao.php
    $_SESSION['campos_m']   = $campos;	
?>