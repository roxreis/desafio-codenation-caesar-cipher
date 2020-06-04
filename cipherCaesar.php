<?php

// array com as letras do alfabeto como chave e 8 casas depois como valor
$key = [
        "a" => "s", "b" => "t","c" => "u", "d" => "v","e" => "w","f" => "x",
        "g" => "y", "h" => "z", "i" => "a", "j" => "b","k" => "c",
        "l" => "d","m" => "e", "n" => "f", "o" => "g", 
        "p" => "h","q" => "i", "r" => "j","s" => "k","t" => "l", 
        "u" => "m", "v" => "n", "w" => "o", "x" => "p",
        "y" => "q", "z" => "r", " " => " ", "." => "."
      ];

$decifrado = '';

// pegando os dados para o desafio
$url = 'https://api.codenation.dev/v1/challenge/dev-ps/generate-data?token=3597bdd739fe16af54bacc1560ce8630f9b88005';
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$resultado = curl_exec($ch);
curl_close($ch);
$json = json_decode($resultado, true);
$cifrado =  $json['cifrado'];


// decifrando a frase que veio pela url
for ($i = 0; $i < strlen($cifrado) ; $i++) {
    
    if (is_array($key) && in_array(strtolower($cifrado[$i]), array_flip($key))) {
        
        $decifrado .= $key[strtolower($cifrado[$i])];
    
    }
}

// hash com sha1
$resumo = sha1($decifrado);

// gravando os dados no arquivo answer.json
$frase = ["numero_casas"=> $json['numero_casas'],"token"=>$json['token'],"cifrado"=>$cifrado,"decifrado"=>$decifrado,"resumo_criptografico"=>$resumo];
        file_put_contents('answer.json', json_encode($frase));

// enviando o arquivo .json para a url abaixo:
$ch = curl_init('https://api.codenation.dev/v1/challenge/dev-ps/submit-solution?token='.$json['token']); 
$headers = array("Content-Type:multipart/form-data"); 
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt_array($ch, 
                    [ 
                        CURLOPT_RETURNTRANSFER => true, 
                        CURLOPT_POST => true, 
                        CURLOPT_HTTPHEADER => $headers,
                        CURLOPT_POSTFIELDS => [ 'answer' => curl_file_create('answer.json') ] 
                    ]); 

// confirmando o sucesso do envio envio
$resposta = curl_exec($ch); 
$erro = curl_error($ch);
echo $erro;
echo $resposta;
curl_close($ch);        