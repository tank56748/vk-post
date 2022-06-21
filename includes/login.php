<?
if($_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
if($_GET['action'] == 'check'){
	$user_login = $_POST["user"];
	$password = $_POST['pass'];

	$get_pass = 'SELECT id, login, pass, status FROM users WHERE login = "'.$user_login.'" LIMIT 1';
	$row = mysqli_fetch_array(mysqli_query($conn, $get_pass));

	$id	= $row['id'];
	$login = $row['login'];
	$user_password = $row['pass'];
	$status	= $row['status'];

	if(!$login || !password_verify($password, $user_password)){
		$msg = 'Не правильный логин или пароль';
	}elseif($status == 3){
		$msg = 'Аккаунт заблокирован';
	}else{

		$hash = md5($user_login . time() . $user_password);
		$sql = 'UPDATE users SET hash = "'.$hash.'", last_in = "'.date("d.m.Y H:i:s").'" WHERE login = "'.$user_login.'"';
		mysqli_query($conn, $sql);
		setcookie('hash', $hash, time() + (60 * 60 * 24 * 30 * 12), '/');

		
		session_start();
		$_SESSION['login'] = $login;
		sleep(1);
		print "<script language=\"javascript\">top.location.href=\"/\";</script>";
	}
}
?>
<h4 class="block-title">Вход в личный кабинет</h4>
<form action="/?page=login&action=check" method="POST" class="contact-form">
	<input type="text" placeholder="Имя пользователя" required name="user" value="<? echo $user_login; ?>">
	<input type="password" placeholder="Пароль" required name="pass"><br>
	<button class="btn" name="submit">Вход</button>
</form>
<script>
$(window).ready(function () {
    var msg = "<? echo $msg;?>";
    if (msg !== "") {
        setTimeout(
            () => {
                alert(msg);
            },
            700
        );
    }
});
</script>