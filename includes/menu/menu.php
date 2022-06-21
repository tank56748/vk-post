<?
if(!$_SESSION['login']){
?>

<ul class="menu">
	<li><a href="/">Сканировать группу</a></li>
	<li><a href="?page=login">Вход</a></li>
	<li><a href="?page=backf">Контакты</a></li>
	<div class="res_spacer"></div>
	<div class="res_spacer"></div>

</ul>
<?}else{?>
<h4 class="block-title"><u><? echo $_SESSION['login']; if($user['status'] == 9) echo ' (Админ)';?></u></h4>
<ul class="menu">
	<li>Баланс: <b><? echo number_format($user['akk_bal'], 2, '.', ' ');?> руб.</b></li>
	<li>Реф.Баланс: <b><? echo number_format($user['ref_bal'], 2, '.', ' ');?> руб.</b></li>

	<div class="res_spacer"></div>
	<?
	if($user['status'] == 9){
		$new_m = mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM messages WHERE to_user = "admin" AND new = 1'));
		if($new_m > 0){
			$count = ' ('.$new_m.')';
		}
		echo '<li><a href="?page=admn_vk">Админка</a></li>';
		echo '<li><a href="?page=admn_vk">Сообщения'.$count.'</a></li>';
	}
	?>
	<li><a href="?page=main">Поиск</a></li>
	<li><a href="?page=history">История поиска</a></li>
	<li><a href="?page=groups">Мои группы</a></li>
	<li><a href="?page=faq">Помощь</a></li>
	<li><a href="?page=contact">Контакты</a></li>
	<li><a href="?page=deposit">Пополнить</a></li>
	<li><a href="?page=withdraw">Вывести</a></li>	
	<li><a href="?page=profile">Профиль</a></li>
	<li><a href="?page=exit">Выход</a></li>
</ul>

<?}?>
<script>
$('.menu_mobile').click(function(){
	$('.right_menu').toggle();
});

</script>





