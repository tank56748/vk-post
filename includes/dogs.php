<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<h4 class="block-title">Модуль поиска "собачек"</h4>
	<p>Пользователей во вконтакте блокируют, они сами удаляются или у них пропадает доступ к странице. В этих и многих других случаях аудитория группы, сообщества или паблика начинает становиться неактивной - мертвой. Этот камень будет тащить вашу группу вниз при ранжировании в поисковой выдаче, плохо влиять на релевантность, охват и стоимость рекламы в РСВК. Это можно легко исправить воспользовавшись нашим сервисом по поиску и удалению неактивных пользователей - "собачек".</p>



<?
$last = $_COOKIE['last_check'];
if($_GET['action'] == 'check'){
	//setcookie('last_check', $_POST['group'], time() + 2592000);
	$time1 = microtime(true);
	$service = 'b9110328b9110328b911032819b961b55ebb911b9110328e7945734d2881c793c5e52c8';
	//$group_id = '188946873';
	$group = $_POST['group'];
	
	$group_sn = end(explode('/', $group));


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
		$chk_time = date("d.m.Y H:i:s");
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
		$is_group = $response['response'][0]['type'];


			$params = array(
				'v'            => '5.103',
				'access_token' => $service,
				'group_id'     => $group_id, 
				'count'		   => '1',
				'offset'	   => '0',
				'sort'         => 'id_asc',
			);

			$post = file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($params));
			$response = json_decode($post, true);
		
			$members_count = $response['response']['count'];
			
			echo '<div class="result">';
			echo '<h3><img src = '.$group_ava.'> '.$group_name.'</h3><hr>';
			echo '<p>Адрес группы: ' . $group  . '</p>';
			echo '<p>ID Группы: ' . $group_id . '</p>';
			echo '<p>Тип группы: ' . $group_type . '</p>';
			echo '<p>Всего подписчиков: ' . $members_count . '</p></p>';

			$dogs = array();
			$count = 0;
			$num = 0;
			$num_na = 0;
			
			for($i=0;$i<=$members_count;$i=$i+1000){
				
				$params = array(
					'v'            => '5.103',
					'access_token' => $service,
					'group_id'     => $group_id, 
					'count'		   => '1000',
					'offset'	   => $i,
					'sort'         => 'id_asc',
				);

				$post = file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($params));
				$response = json_decode($post, true);
				
				
				foreach($response['response']['items'] as $id){
					$ids .= $id . ',';
					$count++;
				}
				$ids = mb_substr($ids, 0, -1);
					
				$params = array(
					'v'            => '5.103',
					'user_ids'     => $ids,
					'fields'       => 'last_seen',
					'access_token' => $service,
				);
				
				$users_dogs = 'https://api.vk.com/method/users.get';
				$response_dogs = file_get_contents($users_dogs, false, stream_context_create(array(
					'http' => array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => http_build_query($params)
					)
				)));
				$res = json_decode($response_dogs, true);
			//	var_dump($res['response']);
			
				$dead_line = time() - 2592000 * $_POST['not_active'];
			
				foreach($res['response'] as $user){
					if(array_key_exists('deactivated', $user)){
						//echo ++$num . '. ' . $user['first_name'] . '<br>';
						$num++;
					}elseif($user['last_seen']['time'] < $dead_line){
						$num_na++;
					}
				}
				$ids = '';
			}	
			echo '<div class="res_spacer"></div>';
			if($num > 0){
				echo '<p>Найдено "Собачек": ' . $num . '</p>';
			}else{
				echo '<p>"Собачек" не найдено.</p>';
			}
			if($num_na > 0){
				echo '<p>Давно не активные: ' . $num_na . '</p>';
			}else{
				echo '<p>Не активных не найдено.</p>';
			}			

			$all_users = $count - $num - $num_na;
			echo '<p>Живая аудитория паблика: ' . $all_users . '</p>';
			
			$time2 = microtime(true) - $time1;
			echo '<p>Время обработки запроса: ' . $time2 . ' сек.</p><br>';
			echo '<button class="btn" onClick="javascript:location.href=\'https://vk-clean.ru\'">Новый поиск</button>';
			echo '</div>';

			$sql = 'SELECT * FROM groups WHERE group_id = ' . $group_id;
			$rows = mysqli_num_rows(mysqli_query($conn, $sql));

			if($rows > 0){
				$sql = 'UPDATE groups SET url = "https://vk.com/'.$group_sn.'", group_name = "'.$group_name.'", ava = "'.$group_ava.'", users = '.$members_count.', dogs = '.$num.', na = '.$num_na.', last_chk = "'.$chk_time.'" WHERE group_id = '.$group_id;
				mysqli_query($conn, $sql);
			}else{
				$sql = 'INSERT INTO groups (url, group_id, group_name, ava, users, dogs, na, last_chk) VALUES("https://vk.com/'.$group_sn.'", '.$group_id.', "'.$group_name.'", "'.$group_ava.'", '.$members_count.', '.$num.', '.$num_na.', "'.$chk_time.'")';
				mysqli_query($conn, $sql);
			}
			//goups.removeUser;
	}	
	
}else{
?>
<form action="?action=check" method="post" class="contact-form" id="sign_form">
	<br><input required name="group" id="group" placeholder="Адрес группы (паблика)">
	<select name="not_active">
		
		<option value="1">Не заходившие в вк более 1 месяца</option>
		<option value="3">Не заходившие в вк более 3 месяцев</option>
		<option value="6" selected>Не заходившие в вк более 6 месяцев</option>
	</select>
	<div class="g-recaptcha" data-sitekey="<? echo $site_key;?>"></div><br>
	<button class="btn" name="submit">Поиск</button>
</form>
<div class="check_hidden_warn">
	<h4>Внимание!!!</h4>
	Программа проверяет каждого подписчика в группе!<br>
	Чем больше подписчиков - тем больше времени занимает поиск.<br>
	Будте терпеливы и дождитесь окончания поиска.<br>
	Поиск может занять продолжительное количество времени.<br>
	В среднем на 1 миллион подписчиков уходит 10 минут.<br>
	Не закрывайте это окно до получения результата.<br><br>
</div>
<script>
$(window).ready(function(){
	$('button.btn').click(function(){
		var url = $('input#group').val();
		document.cookie = 'last_check = ' + url + ';max-age=2592000';
		$('div.g-recaptcha').hide();
	});
});
</script>
<?
if($last){
	$group_sn = end(explode('/', $last));
	$last_req = 'SELECT * FROM groups WHERE url = "'.$last.'" OR url = "https://vk.com/'.$group_sn.'" LIMIT 1';
	if(mysqli_num_rows(mysqli_query($conn, $last_req)) != 0){
		$last_search = mysqli_fetch_array(mysqli_query($conn, $last_req));
		echo '<h4 class="block-title">Ваш последний поиск:</h4>';
		echo '<h3><img src = ' . $last_search['ava'] . '> ' . $last_search['group_name'] . '</h3><hr>';
		echo '<p>Всего подписчиков: ' . $last_search['users'] . '</p>';
		echo '<p>"Собачек": ' . $last_search['dogs'] . '</p>';
		echo '<p>Не активные: ' . $last_search['na'] . '</p><hr>';
	}
}

?>

<? } ?>
