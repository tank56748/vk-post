<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
if($_GET['action'] == 'clean'){
	$sql = mysqli_query($conn, 'SELECT * FROM my_groups WHERE id = '.$_POST['group_id'].' AND user_id = '.$user['id'].' LIMIT 1');
	if(mysqli_num_rows($sql) == 1){
		$row = mysqli_fetch_array($sql);
		if($row['chk_status'] != 0){
			echo '<p>В данный момент кто-то сканирует группу. Пожалуйста, повторите попытку позже.</p>';
		}else{
		
			if(!$_POST['dogs'] and !$_POST['na']){
				echo '<p>Нужно выбрать что чистить...</p>';
			}else{
				if($_POST['dogs'] and !$_POST['na']){
					mysqli_query($conn, 'UPDATE groups SET cln_dogs = 1, cln_na = 0 WHERE url = "'.$row['group_sn'].'"');
				}elseif(!$_POST['dogs'] and $_POST['na']){
					mysqli_query($conn, 'UPDATE groups SET cln_dogs = 0, cln_na = 1 WHERE url = "'.$row['group_sn'].'"');
				}elseif($_POST['dogs'] and $_POST['na']){
					mysqli_query($conn, 'UPDATE groups SET cln_dogs = 1, cln_na = 1 WHERE url = "'.$row['group_sn'].'"');
				}
				mysqli_query($conn, 'UPDATE users SET akk_bal = akk_bal - '.$price_cln.' WHERE id = '.$user['id'].'');
				$user['akk_bal'] -= $price_cln;
				exec('/usr/bin/curl -o /dev/null -silent --data "id='.$row['id'].'" https://bitcointrash.ru/group_clean.php > /dev/null &');
				echo '<p>Чистка проводится в фоновом режиме. Зайдите чуть позже для получения результата.</p>';
			}
		}
	}else{
		echo '<p>Вы не являетесь владельцем этой группы.</p>';
	}
}




?>
