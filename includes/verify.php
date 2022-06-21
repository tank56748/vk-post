<?
if($_GET['mail']){
	$mail_hash = $_GET['mail'];
	$sql = 'SELECT login, mail_chk, mail_hash FROM users WHERE mail_hash = "'.$mail_hash.'" LIMIT 1';
	
	if(mysqli_num_rows(mysqli_query($conn, $sql)) == 1){
		$sql = 'UPDATE users SET mail_chk = 1, mail_hash = "" WHERE mail_hash = "'.$mail_hash.'"';
		mysqli_query($conn, $sql);
		echo 'Адрес почты подтвержден, все функции доступны.';
	}else{
		echo 'Ничего не найдено. Возможно ссылка устарела. Создайте новый запрос в личном кабинете.';
	}
}else{	
	echo 'Ошибка! Не правильные параметры.';
}





?>