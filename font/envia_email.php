<?php

// Email de destino da mensagem
$destinatario = "nunesp25@hotmail.com";

//Recebe as informações enviadas pela pagina index.php
$nome 	  = $_POST['nome'];
$email 	  = $_POST['email'];
$telefone = $_POST['telefone'];
$mensagem = $_POST['mensagem'];
$assunto  = 'Contato vindo do meu Portfolio';
//Monta a estrutura do email

$headers = "MIME-Version: 1.1\r\n";
$headers .= "Content-type: text/plain; charset=UTF-8\r\n";
$headers .= "From:". $email."\r\n"; // remetente
$headers .= "Return-Path: nunesp25@hotmail.com\r\n"; // return-path
$envio = mail($destinatario, $assunto, $mensagem, $headers);
 
if($envio){
    $resposta = "Mensagem enviada com sucesso";
}else {
    $resposta = "A mensagem não pode ser enviada";
}

// redireciona para a página index.php novamente
header("location:index.php");
?>