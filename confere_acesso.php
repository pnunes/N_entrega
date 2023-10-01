<?php
  if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
  }
  
  require_once('Conexao.php');
  $conn = new Conexao();
  $cone = $conn->Conecta();
  mysqli_set_charset($cone,'UTF8');
  
  
?>
<HTML>
<HEAD>
 <TITLE>confere_acesso.php</TITLE>
</HEAD>
<BODY>
	<?php
	if(isset($_POST['usuario']) and isset($_POST['senha'])) {
		$usuario     =$_POST['usuario'];
        $senha       =$_POST['senha'];
		
		// Verifica se o usuário é válido

		$declara = "SELECT cnpj_cpf,nome,email,usuario,senha,ativo
		FROM pessoa
		WHERE ((usuario='$usuario') and (senha='$senha') and (ativo='S'))";

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
			for($ic=0; $ic<$total; $ic++){
			    $row = mysqli_fetch_row($query);
			    $cpf_m          = $row[0];
			    $nome_m         = $row[1];
			    $email_m        = $row[2];
			    $usuario_m      = $row[3];
			    $senha_m        = $row[4];
			    $ativo_m        = $row[5];
		    }
			if ($ativo_m <>'S') {
				?>
					<script language="javascript"> window.location.href=("index.php")
					   alert('Usuario com acesso negado! Fale com o Administrador do sistema.');
					</script>
				<?php
			}else {
				if (($usuario_m==$usuario) and ($senha_m==$senha)) {

					// Registrando variáveis na sessão

					$_SESSION['cpf_m']         = $cpf_m;
					$_SESSION['nome_m']        = $nome_m;
					$_SESSION['email_m']       = $email_m;
					$_SESSION['usuario_m']     = $usuario_m;
					$_SESSION['senha_m']       = $senha_m;
					$_SESSION['ativo_m']       = $ativo_m;
		
					?>
					  <script language="javascript"> window.location.href=("entrada.php")</script>
					<?php
				}else {
					?>
					<script language="javascript"> window.location.href=("index.php")
					   alert('Login e/ou Senha em desacordo com o registrado nosistema! Verifique.');
					</script>
					<?php
				}
		    }
		}
	}
	?>
</BODY>
</HTML>
