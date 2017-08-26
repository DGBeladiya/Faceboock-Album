<?php 

session_start();
require_once __DIR__."/Google.php";
$google=new Google();
$google->client->authenticate($_GET['code']);
$_SESSION["google_access_token"] = $google->client->getAccessToken();


header("location:./");
?>