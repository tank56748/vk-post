<style>
table.details_table tr {
    height: 15px;
}
</style>
<?
if($_GET['action'] == 'delete'){
	$del_group = mysqli_query($conn, 'SELECT * FROM my_groups WHERE user_id = '.$user['id'].' AND id = '.$_POST['group_id'].' LIMIT 1');
	if(mysqli_num_rows($del_group) == 1){
		$del_row = mysqli_fetch_array($del_group);
		$del = mysqli_query($conn, 'DELETE FROM my_groups WHERE id = '.$del_row['id'].'');
		if($del){
			echo '<p>Группа удалена из Вашего списка.</p>';
		}else{
			echo '<p>Что-то опять пошло не так. Пожалуйста, повторите попытку позже.</p>';
		}
	}else{
		echo '<p>У вас нет прав для удаления этой группы.</p>';
	}
	
}else{

	$sql = 'SELECT * FROM groups WHERE group_id = '.$_GET['group_id'].' LIMIT 1';
	$res = mysqli_query($conn, $sql);
	if(mysqli_num_rows($res) == 0){
		echo '<h4 class="block-title">Ничего не найдено</h4>';
	}else{
		$row = mysqli_fetch_array($res);
		echo '<h4 class="block-title">Детали группы:</h4>';
		echo '<h4><img class="group_img" src="'.$row['ava'].'" />   '.$row['group_name'].'</h4><hr>';
		if($row['chk_status'] == 1 or $row['chk_status'] == 2){
			echo '<p>Идет сканирование... Ждите.</p>';
		}elseif($row['cln_status'] == 2){
			echo '<p>Идет чистка... Ждите.</p>';
		}elseif($row['chk_status'] == 0 and $row['cln_status'] == 0){
			$active = $row['users'] - $row['dogs'] - $row['na'];
			
			echo '<table width="100%" class="details_table">';
				echo '<tr>';
					echo '<td width="50%" align="right">Адрес группы:</td><td width="50%" align="left"><a href="'.$row['url'].'" target="_blank"><b>'.$row['url'].'</b></a></td>';
				echo '</tr>';	
				echo '<tr>';
					echo '<td align="right">ID группы:</td><td align="left"><b>'.$row['group_id'].'</b></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td></td><td></td>';
				echo '</tr>';	
				echo '<tr>';
					echo '<td align="right">Всего подписчиков:</td><td align="left"><b>'.number_format($row['users'], 0, '.', ' ').'</b></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right">Найдено собачек:</td><td align="left"><b>'.number_format($row['dogs'], 0, '.', ' ').'</b></td>';
				echo '</tr>';				
				echo '<tr>';
					echo '<td align="right">Давно не активные (более <b>'.$row['na_val'].' мес.</b>):</td><td align="left"><b>'.number_format($row['na'], 0, '.', ' ').'</b></td>';
				echo '</tr>';				
				echo '<tr>';
					echo '<td align="right">Активная аудитория:</td><td align="left"><b>'.number_format($active, 0, '.', ' ').'</b></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td></td><td></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right">Последняя проверка:</td><td align="left"><b>'.$row['last_chk'].'</b></td>';
				echo '</tr>';
			echo '</table>';
		}elseif($row['chk_status'] == 3){
			echo '<p>Сканирование еще не проводилось...</p>';
		}

		echo '<div class="res_spacer"></div>';
		if(($row['chk_status'] == 0 or $row['chk_status'] == 3) and $row['cln_status'] == 0){
			if($user['akk_bal'] >= $price_chk or $user['free_scan'] > 0){
				echo '<form action="/?page=scan&action=scan" method="post" class="contact-form" onSubmit="return confirm(\'Начать сканирование?\')">';
					echo '<input type="hidden" name="group" value="'.$row['url'].'">';
					echo '<button class="btn" name="submit">Сканировать еще раз</button>';
				echo '</form>';
			}		
		}
		
		
		$my_group = mysqli_query($conn, 'SELECT * FROM my_groups WHERE group_sn = "'.$row['url'].'" AND user_id = '.$user['id'].' LIMIT 1');
		if(mysqli_num_rows($my_group) == 1){
			$my_group_row = mysqli_fetch_array($my_group);
			echo '<div class="res_spacer"></div>';
			echo '<form action="/?page=groups_details&action=delete" method="post" class="contact-form" onSubmit="return confirm(\'Точно удаляем группу?\')">';
				echo '<input type="hidden" name="group_id" value="'.$my_group_row['id'].'">';
				echo '<button class="btn" name="submit">Удалить из своих групп</button>';
			echo '</form>';
			echo '<div class="res_spacer"></div>';
			
			echo '<h4 class="block-title">Чистка группы</h4><hr>';
			if($user['token'] == ''){
				echo '<p>Отсутствует токен. Вы можете добавить его в Вашем профиле.</p>';
				echo '<p>Пожалуйста, ознакомьтесь с <b><a href="/?page=manual">инструкцией</a></b> по получению токена.</p>';
			}else{
				if($row['last_clean'] == ''){
					echo '<p>Чистка еще не проводилась</p>';
				}else{
					if($my_group_row['err_code'] != 0){
						echo '<p><b>Чистка не удалась! Не правильный токен.</b></p>';
						echo '<p><b>Ошибка: </b>('.$my_group_row['err_code'].') - '.$my_group_row['error'].'</p>';
						echo '<p>Более подробно об ошибках Вы можете прочитать на <b><a href="https://vk.com/dev/errors" target="_blank">официальном сайте ВК</a></b></p>';
					}else{
						echo '<p>Последняя чистка: <b>'.$row['last_clean'].'</b></p>';
					}
				}
				echo '<div class="res_spacer"></div>';
				if($row['cln_status'] == 0 and $row['chk_status'] != 3){
					echo '<form action="/?page=clean&action=clean" method="post" class="contact-form" onSubmit="return confirm(\'Начать чистку?\')">';
						echo '<input type="hidden" name="group_id" value="'.$my_group_row['id'].'">';
						echo '<label for="dogs" style="margin-left:10px;cursor: pointer;">
								<input type="checkbox" name="dogs" id="dogs" style="width:30px;" value="1">
								<span>Чистка "Собачек"</span>
							</label>';
						echo '<div class="res_spacer"></div>';
						echo '<label for="na" style="margin-left:10px;cursor: pointer;">
								<input type="checkbox" name="na" id="na" style="width:30px;" value="1">
								<span>Чистка не активных</span>
							</label>';
						echo '<div class="res_spacer"></div>';
						echo '<button class="btn" name="submit">Запустить чистку</button>';
					echo '</form>';
					echo '<div class="res_spacer"></div>';
				}elseif($row['chk_status'] == 3){
					echo '<p>Требуется сканирование группы.</p>';
				}
				echo '<p><b>Внимание!!!</b> Убедитесь в правильности указанного Вами токена в профиле.</p>';
				
			}
			
			
			
			
		}
	}
}
?>
