<?php

$dados_arr = [{"Nhawb":"14707374","recebedor":"Joao Amaro","documento":"323233232323","dt_entrega":"2022-12-07","ocorrencia":"03"},{"Nhawb":"14707347","recebedor":"Maria Apareida","documento":"5445455544","dt_entrega":"2022-12-07","ocorrencia":"03"},{"Nhawb":"14707346","recebedor":"Carlos Apache","documento":"87778878787877","dt_entrega":"2022-12-07","ocorrencia":"03"}]
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

//$dados = (object) $dados;
/*$courses = [
    [
        "name"=>"PHP7",
        "url"=>"https://www.hcode.com.br/cursos/PHP7"
    ],
    [
        "name"=>"JavaScript",
        "url"=>"https://www.hcode.com.br/cursos/JSFULL"
    ],
    [
        "name"=>"MySQL",
        "url"=>"https://www.hcode.com.br/cursos/MYSQL"
    ],
    [
        "name"=>"Vue",
        "url"=>"https://www.hcode.com.br/cursos/VUE"
    ]
];*/

var_dump($data);

?>