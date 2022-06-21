<?

echo '<h4 class="block-title">Обратная связь</h4>';
if($_GET['action'] == 'send'){
	$response = $_POST["g-recaptcha-response"];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = [
		'secret' => $secret_key,
		'response' => $_POST["g-recaptcha-response"]
	];
	$options = [
		'http' => [
		  'method' => 'POST',
		  'content' => http_build_query($data)
		]
	];
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	if ($captcha_success->success==false){
		echo '<p>Ваше сообщение отправлено администрации. Ждите - Вам скоро ответят.</p>';
	}elseif($captcha_success->success==true){
			// сохраняем данные, отправляем письма, делаем другую работу. Пользователь не робот
	
		if($_POST['tryfghvbn'] == ''){
			echo 'Не указан адрес почты.';
		}elseif(!filter_var($_POST['tryfghvbn'], FILTER_VALIDATE_EMAIL)){
			echo 'Не правильный формат почты';
		}elseif($_POST['message'] == ''){
			echo 'Пустое сообщение.';
		}else{
			$message = mysqli_query($conn, 'INSERT INTO messages (to_user, mail, text) VALUES ("admin", "'.$_POST['tryfghvbn'].'", "'.$_POST['message'].'")');
			if($message){
				echo '<p>Ваше сообщение отправлено администрации. Ждите, Вам скоро ответят.</p>';
			}else{
				echo '<p>Что-то пошло не так...</p>';
			}
		}
	}
	
}else{
?>
<p>Если у Вас появились вопросы, либо Вы нашли что-то что не работает, сообщите нам об этом</p>
<div class="res_spacer"></div>
<form action="/?page=backf&action=send" method="POST" class="contact-form">
	<input required name="tryfghvbn" placeholder="Ваш адрес почты">
	<input style="display:none" name="mail" placeholder="Ваш адрес почты">
	<textarea type="text" placeholder="Сообщение" name="message" required style="height:100px"></textarea>
	<div class="validation">
		<div class="g-recaptcha" data-sitekey="<? echo $site_key; ?>"></div>
	</div>

	<button class="btn" name="submit">Отправить сообщение</button>

</form>
<?}?>