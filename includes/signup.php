<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>




<?
if($_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
if(isset($_GET['hash'])){
	$ref = mysqli_query($conn, 'SELECT * FROM invite WHERE hash = "'.$_GET['hash'].'" LIMIT 1');
	if(mysqli_num_rows($ref) == 0){
		print "<script language=\"javascript\">top.location.href=\"/\";</script>";
	}else{
$ref_row = mysqli_fetch_array($ref);

if($_GET['action'] == "register"){
	$user_login	= htmlspecialchars($_POST['user'], ENT_QUOTES);
	$pass1	= $_POST['password1'];
	$pass2	= $_POST['password2'];
	$email	= htmlspecialchars($_POST['email'], ENT_QUOTES);
        if (strlen($user_login) > 20 || strlen($user_login) < 5) {
            $msg = "Имя пользователя должно содержать от 5 до 20 символов";
        } elseif (!intval($_POST['yes'])) {
            $msg = "Прочитайте и примите соглашение";
        } elseif ($pass1 != $pass2) {
            $msg = "Пароли не совпадают";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "Не правильный формат почты";
        } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT login FROM users WHERE login = '" . $user_login . "'"))) {
            $msg = "Имя пользователя занято";
        } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT mail FROM users WHERE mail = '" . $email . "'"))) {
            $msg = "Адрес почты занят";
        } else {
            $pass = password_hash($pass1, PASSWORD_DEFAULT);

			$hash = md5($email . time());
			
            $sql = "INSERT INTO users (login, pass, mail, ref_id, free_scan, mail_hash) VALUES ('".$user_login."', '".$pass."', '".$email."', ".$ref_id.", ".$free_scans.", '".$hash."')";
            if(mysqli_query($conn, $sql)){
				
				$subject = "Подтверждение адреса пользователя ".$user_login;
				
				$headers = "Content-type: text/html; charset=utf8 \r\n";
				$headers .= "Mime-Version: 1.0\r\n";
				$headers .= "From: support@vk-clean.ru \r\n";

				
				$text = 'Вы зарегистрировались на сайте <b>Vk-Clean</b><br><br>
						Для завершения регистрации пожалуйста перейдите по ссылке ниже:<br>
						<a href="https://vk-clean.ru/?page=verify&mail='.$hash.'">Подтвердить адрес почты</a>';

				mail($email,$subject,$text,$headers);
				
				$msg_reg = 'Вы успешно зарегистрированы в системе. Пожалуйста подтвердите адрес почты. Если письмо долго не приходит - проверьте пожалуйста папку со спамом.';

			}else{
				$msg = 'Что-то пошло не так... Пожалуйста повторите попытку позже.';
			}
        }
}
?>
<h4 class="block-title">Регистрация</h4>
<form action="/?page=signup&action=register" method="POST" class="contact-form">
	<input hidden type="text" name="hash" value="<?echo $_GET['hash']; ?>">
	<input type="text" placeholder="Логин" required name="user" value="<?php print $user_login; ?>">
	<input type="text" placeholder="Адес почты" required name="email" value="<?php print $email; ?>">
	<input type="password" placeholder="Пароль" required name="password1">
	<input type="password" placeholder="Подтвердить пароль" required name="password2"><br><br>
	
	<label for="sw" style="margin-left:10px;cursor: pointer;">
		<input type="checkbox" name="yes" id="sw" style="width:30px;" value="1"/>
		<span>Я принимаю условия соглашения (<a href="/?page=terms" target="_blank">Соглашение</a>)</span>
	</label>
	
	<div class="g-recaptcha" data-sitekey="<? echo $site_key;?>"></div><br>
	<button class="btn" name="submit">Зарегистрироваться</button>
</form>
<script>
$(window).ready(function () {
    var msg = "<? echo $msg;?>";
    var msg_reg = "<? echo $msg_reg;?>";
    if (msg !== "") {
        setTimeout(
            () => {
                alert(msg);
            },
            700
        );
    }else if(msg_reg !== ""){
        setTimeout(
            () => {
                alert(msg_reg);
                window.location='/?page=login';
            },
            700
        );

    }
})
</script>
	<?}?>