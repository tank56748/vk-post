<?php
session_start();
Error_Reporting(0);

define('MYSQL_HOST', 'localhost');
define('MYSQL_LOGIN', 'vk_post_ru_usr');
define('MYSQL_PASS', 'EeQBYO1ZqfdE45h9');
define('MYSQL_DB', 'vk_post_ru_db');

$site_key = '6LdXAfcUAAAAALsPA49P600AXITYOq4-Suj2sSwx';
$secret_key = '6LdXAfcUAAAAANoH42sFuzdgKAWZ-oJOtHazoLNJ';

//Прайсы и прочие настройки
$price_chk = 10;
$price_cln = 50;
$free_scans = 5;








define('VK_KEY', 'jkhsad7e3bcsdjhgfdsu37lcds');

if (!($conn = mysqli_connect(MYSQL_HOST, MYSQL_LOGIN, MYSQL_PASS, MYSQL_DB))) {
	die("Error DB connect: " . mysqli_connect_error());
	exit();
}
mysqli_set_charset($conn, "utf8");
if(isset($_COOKIE['hash'])){
	$sql = mysqli_query($conn, 'SELECT * FROM users WHERE hash = "'.$_COOKIE['hash'].'" LIMIT 1');
	if(mysqli_num_rows($sql) == 1){
		$user = mysqli_fetch_array($sql);
		session_start();
		$_SESSION['login'] = $user['login'];
	}else{
		unset($_SESSION['login']);
		setcookie("hash", '', time() - 1, "/");
		setcookie("PHPSESSID", '', time() - 1, "/");
	}
}else{
	unset($_SESSION['login']);
	setcookie("PHPSESSID", '', time() - 1, "/");
}




?>