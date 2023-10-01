<?php
//conexão com banco de dados
require_once('Conexao.php');
$conn = new Conexao();
$cone = $conn->Conecta();
mysqli_set_charset($cone,'UTF8');

$destinos = mysqli_real_escape_string($cone,$_GET['term']);
//$destinos = $_GET['term'];

//Pesquisar no banco de dados nome do usuario referente a palavra digitada
$buscaDados = "SELECT * FROM destino WHERE nome_desti LIKE '%$destinos%' ORDER BY nome_desti LIMIT 20";
$result = mysqli_query($cone, $buscaDados);

$resJson ='[';
$first   = true;

while($res = mysqli_fetch_assoc($result)){
	if(!$first):
	   $resJson .=', ';
	else:
	   $first = false;
	endif;
	$resJson .= json_encode($res['nome_desti']);
};
$resJson .=']';
echo $resJson;