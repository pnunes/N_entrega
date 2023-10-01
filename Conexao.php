<?php
if ( session_status() !== PHP_SESSION_ACTIVE ) {
    session_start();
}

class Conexao{
	// Atributos da classe
	private $servidor;
	private $banco;
	private $usuario;
	private $senha;
	private $con;
	
	//Coexaão no servidor internet
	//public function __construct($servidor="localhost",$banco="u803786208_sis_entregas",$usuario="u803786208_nunesp",$senha="Pnu251857") {
	//Conexão no banco no meu micro
	public function __construct($servidor="localhost",$banco="entregas",$usuario="root",$senha="") {
		$this->servidor = $servidor;
		$this->banco    = $banco;
		$this->usuario  = $usuario;
		$this->senha    = $senha;
		$_SESSION['bancoD'] = $banco; 
	}
	
	public function Conecta(){
		$this->con = new mysqli($this->servidor, $this->usuario, $this->senha, $this->banco);
		if (mysqli_connect_errno()) {
            die('FATAL ERROR: Não foi possivel fazer a conexão com o banco! Verifique.');
            exit();
        }else {
			$mensagem ="Conexão bem sucedida!";
		}
		// retorna o resultado da conexão
		return $this->con;
	}
}
?>