<?

if($_GET['action'] == 'scan'){
	require_once('includes/functions.php');
	
	$group = $_POST['group'];
	$group = str_replace('"', '', $group);
	$group = str_replace("'", "", $group);
	$group_sn = end(explode('/', $group));
	$owner_id = 0;
	
	
	
	$add_id = group_add($group_sn, 1);
	if($add_id == 'user'){
		echo '<b>Ошибка:</b> Введен адрес пользователя.';
	}elseif($add_id == 'error'){
		echo 'Внутренняя ошибка ('.$_POST['group'].').<br> Повторите попытку через некоторое время. Если ошибка повторяется - Вы можете написать в поддержку.';
	}else{
		if(isset($_SESSION['login'])){
			$history = mysqli_query($conn, 'SELECT * FROM scan_history WHERE group_sn = "https://vk.com/'.$group_sn.'" AND user_id = '.$user['id'].' LIMIT 1');
			if(mysqli_num_rows($history) == 1){
				$row = mysqli_fetch_array($history);
				mysqli_query($conn, 'DELETE FROM scan_history WHERE id = '.$row['id'].'');
				mysqli_query($conn, 'INSERT INTO scan_history (group_sn, user_id) VALUES ("https://vk.com/'.$group_sn.'", '.$user['id'].')');
			}else{
				mysqli_query($conn, 'INSERT INTO scan_history (group_sn, user_id) VALUES ("https://vk.com/'.$group_sn.'", '.$user['id'].')');
			}
		}
		setcookie('group', $add_id, time() + 86400 * 365);
		header('Location: https://vk-post.ru/');
	}

	
	

		

}else{
?>
<div class="res_spacer"></div>
<form action="?page=scan&action=scan" method="post" class="contact-form" id="sign_form">
	<input required name="group" placeholder="Адрес группы (паблика)">
	<!--<div class="g-recaptcha" data-sitekey="<? echo $site_key;?>"></div>-->
	<div class="res_spacer"></div>
	<button class="btn" name="submit">Начать сканирование</button>
</form>
<?}?>