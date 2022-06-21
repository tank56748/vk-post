<?
require('cnf.php');
$res = mysqli_query($conn, 'SELECT * FROM mails_history WHERE need_send = 1 LIMIT 1');
if(mysqli_num_rows($res) == 1){
	$row = mysqli_fetch_array($res);
	$group_row = mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM groups WHERE url = "https://vk.com/'.$row['group_sn'].'" LIMIT 1'));
	if($group_row['chk_status'] == 0){
		$to = $row['mail'];
		$alive = $group_row['users'] - $group_row['dogs'] - $group_row['na'];
		
		$subject = "Результат сканирования группы  ".$group_row['group_name'];
		$headers = "From: vk-clean@vk-clean.ru\n";
		$headers .= "Reply-to: vk-clean@vk-clean.ru\n";
		$headers .= "X-Sender: < https://vk-clean.ru >\n";
		$headers .= "Content-Type: text/html; charset=utf-8\n";
		
		$text = '<p>Вы сканировали группу '.$group_row['group_name'].':</p>
				<p>Всего подписчиков: '.number_format($group_row['users'], 0, '.', ' ').'</p>
				<p>Найдено "Собачек": '.number_format($group_row['dogs'], 0, '.', ' ').'</p>
				<p>Не активные пользователи: '.number_format($group_row['na'], 0, '.', ' ').'</p>
				<p>Активная аудитория паблика: '.number_format($alive, 0, '.', ' ').'</p>';
		

		
		$mail = mail($to,$subject,$text,$headers);		

		if($mail){
			mysqli_query($conn, 'UPDATE mails_history SET need_send = 0, group_sn = "" WHERE id = '.$row['id'].'');
		}		
	}
}





mysqli_close($conn);
?>
