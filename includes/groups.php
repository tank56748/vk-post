<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
echo '<h4 class="block-title">Мои группы:</h4>';
if($_GET['action'] == 'add'){
	$group = $_POST['group'];
	$group_sn = end(explode('/', $group));
	
	$in_base = mysqli_query($conn, 'SELECT * FROM groups WHERE url = "https://vk.com/'.$group_sn.'" LIMIT 1');
	$in_base_res = mysqli_fetch_array($in_base);
	if(mysqli_num_rows($in_base) == 1){
		$chk_status = $in_base_res['chk_status'];
	}else{
		$chk_status = 3;
	}
	require_once('includes/functions.php');
	$add = group_add($group_sn, $user['id'], $chk_status);
	if($add == 'ok'){
		echo '<p>Группа добавлена в Ваш список.</p>';
		?>
			<script>
				setTimeout(function(){
				  window.location.href = '/?page=groups';
				}, 3000);
			</script>		
		
		<?
	}elseif($add == 'user'){
		echo '<p>Вы указали адрес пользователя. Будте внимательней.</p>';
		?>
			<script>
				setTimeout(function(){
				  window.location.href = '/?page=groups';
				}, 3000);
			</script>		
		
		<?
	}elseif($add == 'error'){
		echo '<p>Что-то пошло не так. Попорбуйте повторить попытку позже</p>';
		?>
			<script>
				setTimeout(function(){
				  window.location.href = '/?page=groups';
				}, 3000);
			</script>		
		
		<?
	}
}else{
?>
<div class="res_spacer"></div>
<form action="/?page=groups&action=add" method="POST" class="contact-form">
	<input type="text" placeholder="Адрес группы" required name="group">
	<button class="btn" name="submit">Добавить группу</button>
</form>
<div class="res_spacer"></div>
<hr>
<?
$sql = 'SELECT * FROM my_groups, groups WHERE groups.url = my_groups.group_sn AND my_groups.user_id = '.$user['id'].'';
$res = mysqli_query($conn, $sql);


if(mysqli_num_rows($res) == 0){
	echo '<p>Вы не добавили ни одной своей группы</p>';
}else{
	echo '<table class="user_table">';
	echo '<tr>';
		echo '<th></th>';
		echo '<th></th>';
		echo '<th style="text-align: right;">Пользователей</th>';
		echo '<th style="text-align: right;">"Собачки"</th>';
		echo '<th style="text-align: right;">Не активные</th>';
	echo '</tr>';
	while($row = mysqli_fetch_array($res)){
		echo '<tr style="cursor:pointer;height:60px;" onClick="javascript:top.location.href=\'/?page=groups_details&group_id='.$row['group_id'].'\'">';
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
}
}
?>