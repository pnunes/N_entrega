<?php 
	if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
    }
  
    require_once('Conexao.php');
    $conn = new Conexao();
    $cone = $conn->Conecta();
    mysqli_set_charset($cone,'UTF8');
    
	if(!isset($usua)){
		$usua ='';
	}
    if(!isset($sena)){
		$sena ='';
	}
  
    //Recebendo as variáveis da página de entrada -index.php

    if(isset($_POST['usua']) and isset($_POST['sena'])){
       $usua     =$_POST['usua'];
       $sena     =$_POST['sena'];
    }
   // echo "<p>Usuario :".$usua;
   // echo "<p>Senha :".$sena;

	if(isset($usua) and isset($sena)) {
		
		$declara = "SELECT usuario,senha,ativo
		FROM pessoa
		WHERE ((usuario='$usua') and (senha='$sena') and (ativo='S'))";

		$query = mysqli_query($cone,$declara) or die ("Nao foi possivel acessar o banco");
		$total = mysqli_num_rows($query);
		
        if($total == 0){
			?>
			   <script language="javascript"> window.location.href=("index.php")
				    alert('Usuario não cadastrado! Fale com o Administrador do sistema.');
			   </script>
			<?php
	    }
		else {
			// Aqui vale qualquer coisa, desde que seja um diretório seguro :)
			//define('DIR_DOWNLOAD', 'copiaapp/');
			$caminho = 'copiaapp/';
			// Vou dividir em passos a criação da variável $arquivo pra ficar mais fácil de entender, mas você pode juntar tudo
			$arquivo = 'gerenteentregas.apk';
			// Retira caracteres especiais
			$arquivo = filter_var($arquivo, FILTER_SANITIZE_STRING);
			// Retira qualquer ocorrência de retorno de diretório que possa existir, deixando apenas o nome do arquivo
			$arquivo = basename($arquivo);
			// Aqui a gente só junta o diretório com o nome do arquivo
			//$caminho_download = DIR_DOWNLOAD . $arquivo;
			$caminho_download = $caminho.$arquivo;
			//echo "<p>Caminho :".$caminho_download;
			// Verificação da existência do arquivo
			if (!file_exists($caminho_download)) die('Arquivo não existe!');
			header('Content-type: octet/stream');
			// Indica o nome do arquivo como será "baixado". Você pode modificar e colocar qualquer nome de arquivo
			header('Content-disposition: attachment; filename="'.$arquivo.'";'); 
			// Indica ao navegador qual é o tamanho do arquivo
			header('Content-Length: '.filesize($caminho_download));
			// Busca todo o arquivo e joga o seu conteúdo para que possa ser baixado
			readfile($caminho_download);
			//retornando para a roina anterior apos concluido o dowload
			header("Location: index.php");
		}
	}else {
		?>
		   <script language="javascript"> window.location.href=("index.php")
				alert('Usuario e/ou senha não informado(s)! Informe os dados completos.');
		   </script>
		<?php
	}
?>
