<?php
$username = $_POST['username'];
$password = $_POST['password'];

session_start();
$_SESSION['auth_login'] = $username;
$_SESSION['auth_password'] = $password;

//Login Alfresco Share
 $url="https://meusoftware.ambientelivre.com.br/share/page/dologin";

 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS,"username=".$username."&password=".$password);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_VERBOSE, 1);
 curl_setopt($ch, CURLOPT_HEADER, 1);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

 $response = curl_exec($ch);
 list($header, $body) = explode("\r\n\r\n", $response, 2);

 preg_match_all('/Set-Cookie: (.*?);/is', $header, $Cookies);

 foreach ($Cookies[1] as $chave => $valor){
         $novoCookie = explode('=', $valor);
         setcookie($novoCookie[0], $novoCookie[1]);
 }

//Login Pentaho
$url="https://meusoftware.ambientelivre.com.br/pentaho/j_spring_security_check";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"j_username=".$username."&j_password=".$password);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response =curl_exec($ch);
list($header, $body) = explode("\r\n\r\n", $response, 2);

preg_match_all('/Set-Cookie: (.*?);/is', $header, $Cookies);
foreach ($Cookies[1] as $chave => $valor){
    $novoCookie = explode('=', $valor);
	setcookie($novoCookie[0], $novoCookie[1], 0, '/pentaho', 'meusoftware.ambientelivre.com.br', true);
}

$retorno = array(
	'status' => 'OKAY',
	'redirect' => 'https://meusoftware.ambientelivre.com.br/index.php?module=Project&action=index'
);
echo json_encode($retorno);die;
