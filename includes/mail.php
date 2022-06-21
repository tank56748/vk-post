<?php
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
if($_GET['action'] == 'send'){
			$hash = md5($_POST['mail'] . time());
			
            $sql = 'UPDATE users SET mail_chk = 0, mail_hash = "'.$hash.'" WHERE id = '.$user['id'].'';
            if(mysqli_query($conn, $sql)){
				
				$subject = "Подтверждение адреса пользователя ".$user_login;
				$headers = "From: vk-clean@vk-clean.ru\n";
				$headers .= "Reply-to: admin@vk-clean.ru\n";
				$headers .= "X-Sender: < https://vk-clean.ru >\n";
				$headers .= "Content-Type: text/html; charset=utf-8\n";
				
				$text = 'Вы зарегистрировались на сайте <b>Vk-Clean</b><br><br>
						Для завершения регистрации пожалуйста перейдите по ссылке ниже:<br>
						<a href="https://vk-clean.ru/?page=verify&mail='.$hash.'">Подтвердить адрес почты</a>';

				if(mail($_POST['mail'],$subject,$text,$headers)){
					echo '<p>Ссылка отправлена на почту '.$_POST['mail'].'</p>';
				}else{
					echo '<p>Не отправлено</p>';
				}

			}else{
				echo '<p>Проблемы с доступом к БД. Попробуйте повторить попытку через несколько минут</p>';
			}
	
	
}else{
?>
<h4 class="block-title">Подтверждение адреса</h4><hr>
<p>Адрес почты не подтвержден. Пожалуйста, пройдите по ссылке в письме, высланном на адрес <? echo $user['mail'];?>. Если письма нет - пожалуйста, проверьте папку со спамом - бывает и такое.</p>
<form action="/?page=mail&action=send" method="post" class="contact-form">
	<input type="hidden" name="mail" value="<? echo $user['mail'];?>">
	<button type="submit" class="btn">Выслать подтверждение заново</button>
</form>
<?}?>