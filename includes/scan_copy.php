<?
if($_GET['action'] == 'scan'){
	$mail_history = 'SELECT * FROM mails_history WHERE mail = "'.$_POST['mail'].'" LIMIT 1';
	$mail_sql = mysqli_query($conn, $mail_history);
	$mails_res = mysqli_fetch_array(mysqli_query($conn, $mail_history));
	if($mails_res['count'] == 3){
		echo '<p>Для адреса <b>'.$_POST['mail'].'</b> превышен лимит запросов. Пожалуйста зарегистрируйтесь для продолжения работы с сайтом.</p>';
	}else{
		$group = $_POST['group'];
		$group_sn = end(explode('/', $group));	
		
		$in_base = 'SELECT * FROM groups WHERE url = "https://vk.com/'.$group_sn.'" LIMIT 1';
		$in_base_row = mysqli_num_rows(mysqli_query($conn, $in_base));
		
		if($in_base_row == 1){
			$request = mysqli_fetch_array($conn, $in_base);
			if($request['chk_status'] != 0){
				echo '<p>Группа уже находится на проверке</p>';
			}else{
				mysqli_query($conn, 'UPDATE groups SET chk_status = 1, need_send = 1, send_to = "'.$_POST['mail'].'", na_val = '.$_POST['not_active'].' WHERE url = "https://vk.com/'.$group_sn.'"');
				if(mysqli_num_rows($mail_sql) == 1){
					mysqli_query($conn, 'UPDATE mails_history SET count = count + 1 WHERE mail = "'.$_POST['mail'].'"');
				}else{
					mysqli_query($conn, 'INSERT INTO mails_history (mail, count) VALUES ("'.$_POST['mail'].'", 1)');
				}
				echo '<p>Как только буден получен результат сканирования он будет незамедлительно отправлен на вашу почту (<b>'.$_POST['mail'].'</b>)</p>';
			}
		}else{
			$service = 'b9110328b9110328b911032819b961b55ebb911b9110328e7945734d2881c793c5e52c8';
			$params = array(
				'v'            => '5.103',
				'access_token' => $service,
				'screen_name'  => $group_sn, 
			);
			
			$post = file_get_contents('https://api.vk.com/method/utils.resolveScreenName?' . http_build_query($params));
			$response = json_decode($post, true);

			$group_type = $response['response']['type'];
			
			if($group_type == 'user'){
				echo 'Вы указали адрес пользователя. Будте внимательны.<br><button class="btn" onClick="javascript:location.href=\'https://vk-clean.ru\'">Новый поиск</button>';
			}else{
				$params = array(
					'v'            => '5.103',
					'access_token' => $service,
					'group_id'  => $response['response']['object_id'], 
				);
				
				$post = file_get_contents('https://api.vk.com/method/groups.getById?' . http_build_query($params));
				$response = json_decode($post, true);

				$group_id = $response['response'][0]['id'];
				$group_name = $response['response'][0]['name'];
				$group_ava = $response['response'][0]['photo_50'];
				
				$to_base = 'INSERT INTO groups (url, group_id, ava, group_name, chk_status, na_val, need_send, send_to) VALUES ("https://vk.com/'.$group_sn.'", '.$group_id.', "'.$group_ava.'", "'.$group_name.'", 1, '.$_POST['not_active'].', 1, "'.$_POST['mail'].'")';
				if(mysqli_query($conn, $to_base)){
					if(mysqli_num_rows($mail_sql) == 1){
						mysqli_query($conn, 'UPDATE mails_history SET count = count + 1 WHERE mail = "'.$_POST['mail'].'"');
					}else{
						mysqli_query($conn, 'INSERT INTO mails_history (mail, count) VALUES ("'.$_POST['mail'].'", 1)');
					}
					echo '<p>Как только будет получен результат сканирования он будет незамедлительно отправлен на вашу почту (<b>'.$_POST['mail'].'</b>)</p>';
				}
			}
		}
	}
}else{
?>
<form action="?page=scan&action=scan" method="post" class="contact-form" id="sign_form">
	<div class="res_spacer"></div>
	<input required name="group" placeholder="Адрес группы (паблика)">
	<input required name="mail" placeholder="E-mail для получения результата">
	<select name="not_active">
		<option value="1">Не заходившие в вк более 1 месяца</option>
		<option value="3">Не заходившие в вк более 3 месяцев</option>
		<option value="6" selected>Не заходившие в вк более 6 месяцев</option>
	</select>
	<div class="g-recaptcha" data-sitekey="<? echo $site_key;?>"></div><br>
	<button class="btn" name="submit">Поиск</button>
</form>
<?}?>