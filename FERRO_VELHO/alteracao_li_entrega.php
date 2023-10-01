<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<script type="text/javascript">   
	    //Função submete o form após digitar o campo numero hawb
	    function salva(campo){
           cadastro.submit();
        }
   </script>
	
	
  </head>
  <body>
   <form name="cadastro" id="cadastro" align="center" action="alteracao_li_entrega.php" method="POST">
       
		<?php
		  ///////////////// Verifica se foi passado hawb na leitora e capturado o codigo de barra ///////////////////////////////////////
	   	   var_dump($_POST['codi_barra']);
		   if(isset($_POST['codi_barra'])) {
			  $codi_barra  = $_POST['codi_barra'];
		   }
		   else {
			  $codi_barra  ='';
		   }
		?>
		
	<div class="input-group" id="cod_barras">
	 <span class="input-group-addon">Cod. Barras</span>
	 <input type="text" name="codi_barra" class="form-control" placeholder=""  onChange="salva(this)">
	 <script language="JavaScript">
		 document.getElementById('codi_barra').focus()
	 </script>
   </div>
   </form>
   <div style="width:1155px;height:50px;background-color:#191970;vertical-align:middle !important;z-index:1;position:relative;margin-left:100px;margin-top:0px;">
   
	   <div style="width:85px;height:40px;float:left;position:relative;margin-left:7px;margin-top:6px;">
		   <a href="entrada.php"<button class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Voltar</button></a>
	   </div>
   </div> 
   
</body>
</html>