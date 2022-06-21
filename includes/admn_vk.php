<?
if(!$_SESSION['login'] and $user['status'] != 9){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
echo '<h4 class="block-title">Мало-мальская админка...)))</h4>';


if($_GET['action'] == 'add_scan'){
	
	$sql = 'UPDATE users SET free_scan = free_scan + '.$_POST['scans'].', akk_bal = akk_bal + '.$_POST['balance'].' WHERE login = "'.$_POST['login'].'"';
	if(mysqli_query($conn, $sql)){
		echo '<p>Пользователю <b>'.$_POST['login'].'</b> добавлено <b>'.$_POST['scans'].'</b> бесплатных сканирований и '.$_POST['balance'].' на баланс.</p>';
		if($user['login'] == $_POST['login']){
			$user['free_scan'] += $_POST['scans'];
			$user['akk_bal'] += $_POST['balance'];
		}
	}else{
		echo '<p>Что-то пошло не так...</p>';
	}
}else{
echo '<p>Пользователи: <b>'.mysqli_num_rows(mysqli_query($conn, 'SELECT id FROM users')).'</b></p>';
echo '<p>Групп в базе: <b>'.mysqli_num_rows(mysqli_query($conn, 'SELECT id FROM groups')).'</b></p>';
$money = mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM money'));
echo '<p>Всего зачислено: <b>'.$money['balance'].' руб.</b></p>';
echo '<p>Из них реферальные: <b>'.$money['ref_balance'].' руб.</b></p>';
?>
<div class="res_spacer"></div>
<h4 class="block-title">Добавить сканирования/баланс</h4>
<form action="/?page=admn_vk&action=add_scan" method="post" class="contact-form">
	<input required type="text" placeholder="Логин пользователя" name="login">
	<input required type="number" placeholder="Количество сканирований" name="scans">
	<input required type="text" placeholder="Добавить денег" name="balance">
	<button type="submit" class="btn">Добавить</button>
</form>





<?}?>