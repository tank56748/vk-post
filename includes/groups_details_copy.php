<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
if($_GET['action'] == 'delete'){
	$del_group = mysqli_query($conn, 'SELECT * FROM groups WHERE owner_id = '.$user['id'].' AND id = '.$_POST['group_id'].' LIMIT 1');
	if(mysqli_num_rows($del_group) == 1){
		$del_row = mysqli_fetch_array($del_group);
		$del = mysqli_query($conn, 'UPDATE groups SET owner_id = 0 WHERE id = '.$del_row['id'].'');
		if($del){
			echo '<p>Группа удалена из Вашего списка.</p>';
		}else{
			echo '<p>Что-то опять пошло не так. Пожалуйста, повторите попытку позже.</p>';
		}
	}else{
		echo '<p>У вас нет прав для удаления этой группы.</p>';
	}
	
}else{

	$sql = 'SELECT * FROM groups WHERE id = '.$_GET['group'].' LIMIT 1';
	$res = mysqli_query($conn, $sql);
	if(mysqli_num_rows($res) == 0){
		echo '<h4 class="block-title">Ничего не найдено</h4>';
	}else{
		$row = mysqli_fetch_array($res);
		echo '<h4 class="block-title">Детали группы:</h4>';
		echo '<h4><img class="group_img" src="'.$row['ava'].'" />   '.$row['group_name'].'</h4><hr>';
		if($row['chk_status'] == 1 or $row['chk_status'] == 2){
			echo '<p>Идет сканирование... Ждите.</p>';
		}elseif($row['chk_status'] == 0){
			echo '<p>Адрес группы: <a href="'.$row['url'].'"><b>'.$row['url'].'</b></a></p>';
			echo '<p>ID группы: <b>'.$row['group_id'].'</b></p>';
			
			echo '<div class="res_spacer"></div>';
			
			echo '<p>Всего подписчиков: <b>'.number_format($row['users'], 0, '.', ' ').'</b></p>';
			echo '<p>Найдено собачек: <b>'.number_format($row['dogs'], 0, '.', ' ').'</b></p>';
			echo '<p>Давно не активные (более <b>'.$row['na_val'].' мес.</b>): <b>'.number_format($row['na'], 0, '.', ' ').'</b></p>';
			$active = $row['users'] - $row['dogs'] - $row['na'];
			echo '<p>Активная аудитория: <b>'.number_format($active, 0, '.', ' ').'</b></p>';
			
			echo '<div class="res_spacer"></div>';
			
			echo '<p>Последнее сканирование: <b>'.$row['last_chk'].'</b></p>';
			echo '<div class="res_spacer"></div>';
			if($user['akk_bal'] >= $price_chk or $user['free_scan'] > 0){
				if($user['free_scan'] > 0){
					$comment = ' ('.$user['free_scan'].' бесплатно)';
				}
				$button = '<button class="btn" name="submit">Сканировать заново'.$comment.'</button>';
				echo '<form action="/?page=scan&action=scan" method="post" class="contact-form">';
					echo '<input type="hidden" name="group" value="'.$row['url'].'">';
					echo '<input type="hidden" name="not_active" value="'.$row['na_val'].'">';
					echo $button;
				echo '</form>';
			}

		}elseif($row['chk_status'] == 3){
			echo '<p>Сканирование еще не проводилось...</p>';
			if($user['free_scan'] > 0){
				$comment = ' ('.$user['free_scan'].' бесплатно)';
			}
			$button = '<button class="btn" name="submit">Новое сканирование'.$comment.'</button>';
			echo '<form action="/?page=scan&action=scan" method="post" class="contact-form">';
				echo '<input type="hidden" name="group" value="'.$row['url'].'">';
				echo '<input type="hidden" name="not_active" value="'.$row['na_val'].'">';
				echo $button;
			echo '</form>';
		}
		if($row['owner_id'] == $user['id']){
			echo '<div class="res_spacer"></div>';
			echo '<form action="/?page=groups_details&action=delete" method="post" class="contact-form">';
				echo '<input type="hidden" name="group_id" value="'.$row['id'].'">';
				echo '<button class="btn" name="submit">Удалить из своих групп</button>';
			echo '</form>';
			echo '<div class="res_spacer"></div>';
			
			echo '<h4 class="block-title">Чистка группы</h4><hr>';
		}
	}
}
?>