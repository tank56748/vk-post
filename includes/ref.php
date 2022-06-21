<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
echo '<h4 class="block-title">Реферальная программа:</h4>';
echo '<h3>Ваша реф ссылка: https://vk-clean.ru/?from=' . $user['id'] . '</h3>';

$ref_sql = 'SELECT * FROM users WHERE ref_id =' . $user['id'] . ' ORDER BY id DESC';
$ref_res = mysqli_query($conn, $ref_sql);
if(mysqli_num_rows($ref_res) == 0){
	echo 'У вас еще нет рефералов';
}else{
	echo '<div class="res_spacer"></div>';
	echo '<table class="user_table">';
	$i = 1;
	while($row = mysqli_fetch_array($ref_res)){
		echo '<tr>';
			echo '<td>'.$i++.'</td>';
			echo '<td>'.$row['login'].'</td>';
			echo '<td align="right">'.number_format($row['ref_money'], 2, '.', ' ').' руб.</td>';
		
		echo '</tr>';
	}
	
	
	echo '</table>';

}

?>