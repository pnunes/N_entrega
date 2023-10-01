<?php
   require_once('Conexao.php');
   $conn = new Conexao();
   $cone = $conn->Conecta();
   mysqli_set_charset($cone,'UTF8');
   
   //definindo a entrada da informação, tanto por POST como por GET
  // $acao = ($_POST['acao'] ? $_POST['acao'] : $_GET['acao']);
   if(isset($_POST['acao'])) {
       $acao = $_POST['acao'];
   } 
   if(isset($_GET['acao'])) {
       $acao = $_GET['acao'];
   } 
   $acao = mysqli_real_escape_string($cone,$acao);
  // echo "<p>Ação :".$acao;
   switch($acao){
        case 'completar':
            $destinos = mysqli_real_escape_string($cone,$_GET['term']);
            //Pesquisar no banco de dados nome do usuario referente a palavra digitada
            $pesquisa = "SELECT * FROM destino WHERE nome_desti LIKE '%$destinos%' ORDER BY nome_desti LIMIT 20";
            $resulta = mysqli_query($cone, $pesquisa);        
            $resJson ='[';
            $first   = true;      
            while($res = mysqli_fetch_assoc($resulta)){
                if(!$first):
                   $resJson .=', ';
                else:
                   $first = false;
                endif;
                $resJson .= json_encode($res['nome_desti']);
            };
            $resJson .=']';
            echo $resJson;
        break;
        case 'pesquisar':
            $pesquisa = mysqli_real_escape_string($cone,$_GET['valor']);
            //Pesquisar no banco de dados nome do usuario referente a palavra digitada
            $localiza = "SELECT * FROM destino WHERE nome_desti LIKE '%$pesquisa%' ORDER BY nome_desti LIMIT 20";
            $resul    = mysqli_query($cone, $localiza); 
            if(mysqli_num_rows($resul) >= 1) {
                while($dado = mysqli_fetch_assoc($resul)) {
                    echo '<li>';
                         echo $dado['codigo_desti'].' - '.$dado['nome_desti'].' - '.$dado['rua_desti'];
                    echo '</li>';
                }
            }else {
                echo '<li>Destino não localizado!</li>';
            }   
           /* $resJson ='[';
            $first   = true;      
            while($res = mysqli_fetch_assoc($resul)){
                if(!$first):
                   $resJson .=', ';
                else:
                   $first = false;
                endif;
                $resJson .= json_encode($res['nome_desti']);
            };
            $resJson .=']';
            echo $resJson;*/
        break;
        default;


   }

?>