<?php
   //Controle de permissões das opções que chamam diretamente a rotina 
   
   //Rotina de elaboração de lista de entrega
   $rotina_ele = 'BI21';
   $rotina_lim = 'BI22';
   $rotina_ale = 'BI23';
   $rotina_bhe = 'BI31';
   $rotina_abe = 'BI32';
   $rotina_lid = 'BI41';
   

   // Pega o cpf do usuario da memoria 
   $cpf_usuario = $_SESSION['cpf_m'];

   //Define permissão para rotina elabora_lista_entrega.php
   $permi_ele  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$rotina_ele'");
   $ok_permi_ele = mysqli_num_rows($permi_ele);
   if($ok_permi_ele > 0){
		$permissao_ele = 'S';
   } else {
		$permissao_ele = 'N';
   }

   //Define permissão para rotina lista_entrega_manual.php
   $permi_lim  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$rotina_lim'");
   $ok_permi_lim = mysqli_num_rows($permi_lim);
   if($ok_permi_lim > 0){
		$permissao_lim = 'S';
   } else {
		$permissao_lim = 'N';
   }
   
   //Define permissão para rotina altera_lista_entrega.php
   $permi_ale  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$rotina_ale'");
   $ok_permi_ale = mysqli_num_rows($permi_ale);
   if($ok_permi_ale > 0){
		$permissao_ale = 'S';
   } else {
		$permissao_ale = 'N';
   }
   
   //Define permissão para rotina baixa_hawb_entregue.php
   $permi_bhe  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$rotina_bhe'");
   $ok_permi_bhe = mysqli_num_rows($permi_bhe);
   if($ok_permi_bhe > 0){
		$permissao_bhe = 'S';
   } else {
		$permissao_bhe = 'N';
   }
   
   //Define permissão para rotina altera_baixa_hawb_entregue.php
   $permi_abe  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$rotina_abe'");
   $ok_permi_abe = mysqli_num_rows($permi_abe);
   if($ok_permi_abe > 0){
		$permissao_abe = 'S';
   } else {
		$permissao_abe = 'N';
   }
   
   //Define permissão para rotina elabora_lista_devolucao_origem.php
   $permi_lid  = mysqli_query($cone, "SELECT rotina FROM permissoes WHERE cpf='$cpf_usuario' AND rotina='$rotina_lid'");
   $ok_permi_lid = mysqli_num_rows($permi_lid);
   if($ok_permi_lid > 0){
		$permissao_lid = 'S';
   } else {
		$permissao_lid = 'N';
   }
?>