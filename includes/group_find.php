<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?
$user_search = $_COOKIE['user_search'];

if($_GET['action'] == 'check'){
	setcookie('user_search', $_POST['user'], time() + 2592000);
	$service = 'b9110328b9110328b911032819b961b55ebb911b9110328e7945734d2881c793c5e52c8';
	$user_token ='87c1b24bcb26f44a66328ec317841d1f13bca16de7b419f8571f00dac1e2a5852db13dc7bb0fa152fd47b';
	
	$user = $_POST['user'];
	$user_sn = end(explode('/', $user));
	
	$params = array(
		'v'            => '5.103',
		'access_token' => $service,
		'screen_name'  => $user_sn, 
	);
	
	$post = file_get_contents('https://api.vk.com/method/utils.resolveScreenName?' . http_build_query($params));
	$response = json_decode($post, true);

	$user_id = $response['response']['object_id'];
	echo $user_id . '<br>';
	
	$params = array(
		'v'            => '5.103',
		'access_token' => $user_token,
		'user_id' => $user_id,
		'extended' => 1,
	);
	
	$post = file_get_contents('https://api.vk.com/method/groups.get?' . http_build_query($params));
	$response = json_decode($post, true);
	
//	var_dump($response);
//	echo '<br><br><br>';

	
	if($response['error']['error_code'] == 30){
		echo 'Ваш профиль закрыт для просмотра.<br>Пожалуйста откройте профиль на время сканирования, либо добавте нужные группы в ручную.<br>Спасибо за понимание.';
	}else{
		$i = 0;
		foreach($response['response']['items'] as $item){
			if($item['admin_level'] == 3){
				echo $item['photo_50'] . ' ' . $item['name'] . ' (id: '.$item['id'].')<br>';
				$i++;
			}
			
			
		}
		echo 'Найдено групп: ' . $i . '<br><br>';
/*
		foreach($response['response']['items'] as $item){
			//echo $item . '<br>';
			$params = array(
				'v'            => '5.103',
				'access_token' => $service,
				'group_id'  => $item, 
			);
			
			$post = file_get_contents('https://api.vk.com/method/groups.getById?' . http_build_query($params));
			$response = json_decode($post, true);
			
			//var_dump($response);
			echo '<img src="'.$response['response'][0]['photo_50'].'" /> '.$response['response'][0]['name'].' (id: '.$response['response'][0]['id'].')<br>';

		}
*/
	}



}else{
?>
<form action="group_find.php?action=check" method="post">
	<input required name="user" id="user" placeholder="Адрес страницы пользователя" value="<? echo $user_search; ?>" style="width: calc(100% - 134px)" />
	<button type="submit" class="submit" style="float: right;width: 128px;">Поиск</button>
</form>
<div class="check_hidden_warn" style="display:none;">
	<h4>Ищем группы. Внимание!!!</h4>
	Программа ищет группы пользователя!<br>
	Чем больше групп - тем больше времени занимает поиск.<br>
	Будте терпеливы и дождитесь окончания поиска.<br>
	Поиск может занять продолжительное количество времени.<br>
	Не закрывайте это окно до получения результата.
</div>

<script>
$(window).ready(function(){
	$('.submit').click(function(){
		var g_id = $('input#group').val();
		if(g_id != ''){;
			$('.check_hidden_warn').fadeIn();
		}
	});


});

</script>
<?}?>