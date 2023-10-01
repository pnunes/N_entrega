<?php
if ( session_status() !== PHP_SESSION_ACTIVE ) {
       session_start();
}
?>
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="background-color:#ffffff;">
	<div id="cabeca">
			<?php if(isset($_SESSION['modulo_m'])){ $modulo = $_SESSION['modulo_m'];} ?>
			<div id="nome_sistema"><?php echo $modulo; ?></div>
	</div>
	<?php $nome_usu  = $_SESSION['nome_m'];?>
	<nav class="navbar navbar-dark bg-dark">	   
		 <div id="nome_usu">
			  <p><b>Usuario : </b><?php echo $nome_usu?>
	     </div>
	</nav>