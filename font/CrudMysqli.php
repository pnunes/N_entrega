<?php
if ( session_status() !== PHP_SESSION_ACTIVE ) {
     session_start();
}

require_once('Conexao.php');

class CrudMysqli {
     // Atributtos
     private $campos;
     private $tabela;
     private $condicao;
     private $parametro;
     private $dados;
     private $resultado;

     public function __construct($cam,$tab,$dad,$cod,$par){
         $this->campos    = $cam;
         $this->tabela    = $tab;
         $this->dados     = $dad;
         $this->condicao  = $cod;
         $this->parametro = $par;
     }

     public function Listar(){
          $conn = new Conexao();
          $cone = $conn->Conecta();
          $pega_dados = "SELECT $this->campos FROM $this->tabela";
          mysqli_set_charset($cone,'UTF8');
          $query = mysqli_query($cone,$pega_dados);
          $total = mysqli_num_rows($query);
          for($ic=0; $ic<$total; $ic++){
               //$this->resultado[] = mysqli_fetch_row($query);// Mostra a chave na forma de numero
              $this->resultado[] = mysqli_fetch_assoc($query);// Mostra como chave o nome do campo.
          }
          
          return $this->resultado;
          mysqli_close($cone);
     }
     
     public function Inserir(){
          $conn = new Conexao();
          $cone = $conn->Conecta();
          $insere_dados = "INSERT INTO $this->tabela ($this->campos)VALUE($this->dados)";
          mysqli_set_charset($cone,'UTF8');
          $queryins = mysqli_query($cone,$insere_dados);
          if($queryins){
			 $resultado ='1I';    
          } else {
			  $resultado ='2I';
          }
          return $resultado;
     }

     public function Alterar(){
          $conn = new Conexao();
          $cone = $conn->Conecta();
		  if($this->condicao<>'') {
			  //para os casos de alteração em que exija localizar o registro para alterar
			  $altera_dados = "UPDATE $this->tabela SET $this->dados WHERE $this->condicao";
			  mysqli_set_charset($cone,'UTF8');
			  $queryalt = mysqli_query($cone,$altera_dados);
			  if($queryalt){
				 //$resultado ='Alteração bem sucedida.'; 
				 $resultado ='1A';   
			  } else {
				 //$resultado ='Problemas na Alteração! Verifique.';
				 $resultado ='2A';  
			  }
		  }else {
			  //para os casos de alteração em que não exija localizar o registro para alterar, tabela com um so registro (aparencia_site)
              $altera_dados = "UPDATE $this->tabela SET $this->dados";
			  mysqli_set_charset($cone,'UTF8');
			  $queryalt = mysqli_query($cone,$altera_dados);
			  if($queryalt) {
			     //$resultado ='Alteração bem sucedida.';
				 $resultado ='1A';   
			  } else {
				 //$resultado ='Problemas na Alteração! Verifique.'; 
				 $resultado ='2A';  
			  }
          }			  
		  
          //return "Resultado mostrado";
          return $resultado;
     }
     public function Excluir(){
          $conn = new Conexao();
          $cone = $conn->Conecta();
          $exclui_regi ="DELETE FROM $this->tabela WHERE $this->condicao";
          $queryexclu = mysqli_query($cone,$exclui_regi);
          if($queryexclu) {
			   $resultado ='1E';
	     }
          else {
			   $resultado ='2E';
          }
          return $resultado;
     }
}
?>