<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
echo '<h4 class="block-title">Мой профиль</h4>';



if($_GET['action'] == 'password'){
	
}elseif($_GET['action'] == 'email'){
	if(password_verify($_POST['pass'], $user['pass'])){
		if(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
			
		
			$hash = md5($_POST['mail'] . time());
			$sql = 'UPDATE users SET mail = "'.$_POST['mail'].'", mail_chk = 0, mail_hash = "'.$hash.'" WHERE id = '.$user['id'].'';
			if(mysqli_query($conn, $sql)){
				
				$subject = "Смена адреса пользователя ".$user['login'];
				$headers = "From: vk-clean@vk-clean.ru\n";
				$headers .= "Reply-to: admin@vk-clean.ru\n";
				$headers .= "X-Sender: < https://vk-clean.ru >\n";
				$headers .= "Content-Type: text/html; charset=utf-8\n";
				
				$text = 'Для для подтверждения адреса пожалуйста перейдите по ссылке ниже:<br>
						<a href="https://vk-clean.ru/?page=verify&mail='.$hash.'">Подтвердить адрес почты</a>';

				if(mail($_POST['mail'],$subject,$text,$headers)){
					echo '<p>На адрес <b>'.$_POST['mail'].'</b> выслано письмо.</p>';
				}else{
					echo '<p>Письмо не отправлено.</p>';
				}
				
				

			}else{
				echo '<p>Что-то пошло не так...</p>';
			}
		}else{
			echo '<p>Не правильный формат адреса почты</p>';
		}
		
	}else{
		echo '<p>Не правльный пароль.</p>';
	}
	
	
	
	
	
}elseif($_GET['action'] == 'token'){
	$sql = 'UPDATE users SET token = "'.$_POST['token'].'" WHERE id = '.$user['id'].'';
	if(mysqli_query($conn, $sql)){
		echo '<p>Токен установлен.</p>';
	}else{
		echo '<p>Что-то пошло не так...</p>';
	}
	
}else{

?>
<div class="res_spacer"></div>
<h4 class="block-title">Сменить пароль</h4><hr>
<form action="/?page=profile&action=password" method="post" class="contact-form">
	<input required type="password" placeholder="Старый пароль" name="oldpass">
	<input required type="password" placeholder="Новый пароль" name="newpass">
	<input required type="password" placeholder="Подтвердить пароль" name="newpass2">
	<button type="submit" class="btn">Сменить пароль</button>
</form>
<div class="res_spacer"></div>
<div class="res_spacer"></div>
<h4 class="block-title">Сменить почту</h4><hr>
<form action="/?page=profile&action=email" method="post" class="contact-form">
		<input required type="text" value="<?php echo $user['mail'];?>" name="mail">
		<input required type="password" placeholder="Пароль" name="pass">
		<p>Внимание! После смены почтового адреса требуется подтвердить новый адрес.</p>
		<button type="submit" class="btn">Сменить почту</button>
</form>

<div class="res_spacer"></div>
<div class="res_spacer"></div>
<h4 class="block-title">Токен</h4><hr>
<form action="/?page=profile&action=token" method="post" class="contact-form">
		<input required type="text" placeholder="Введите токен" value="<?php echo $user['token'];?>" name="token">
		<p><a href="/?page=manual" target="_blank"><b>Инструкция</b></a> по получению токена</p>
		<div class="res_spacer"></div>
		<button type="submit" class="btn">Сохранить токен</button>
</form>	

<?}?>