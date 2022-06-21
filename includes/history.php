<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
echo '<h4 class="block-title">История сканирований:</h4>';
$sql = 'SELECT * FROM scan_history, groups WHERE groups.url = scan_history.group_sn AND scan_history.user_id = '.$user['id'].' ORDER BY scan_history.id DESC';
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res) != 0){
	echo '<table class="user_table">';
	echo '<tr>';
		echo '<th></th>';
		echo '<th></th>';
		echo '<th style="text-align: right;">Пользователей</th>';
		echo '<th style="text-align: right;">"Собачки"</th>';
		echo '<th style="text-align: right;">Не активные</th>';
	echo '</tr>';
		while($row = mysqli_fetch_array($res)){
			echo '<tr style="cursor:pointer;height:60px" onClick="javascript:top.location.href=\'/?page=groups_details&group_id='.$row['group_id'].'\'">';
				echo '<td><img class="group_img" src="'.$row['ava'].'" /></td>';
				echo '<td>'.$row['group_name'].'</td>';
				if($row['chk_status'] == 0 and $row['cln_status'] == 0){
					echo '<td align="right">'.number_format($row['users'], 0, ',', ' ').'</td>';
					echo '<td align="right">'.number_format($row['dogs'], 0, ',', ' ').'</td>';
					echo '<td align="right">'.number_format($row['na'], 0, ',', ' ').'</td>';
				}elseif($row['chk_status'] == 1 or $row['chk_status'] == 2){
					echo '<td colspan="3" align="center">Идет сканирование...</td>';
				}elseif($row['chk_status'] == 3){
					echo '<td colspan="3" align="center">Сканирование еще не проводилось...</td>';
				}elseif($row['cln_status'] == 2){
					echo '<td colspan="3" align="center">Идет чистка группы...</td>';
				}
			echo '</tr>';
		}
	echo '</table>';
}else{
	echo '<p>История поиска пуста...</p>';
}
?>