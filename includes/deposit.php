<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}

echo '<h4 class="block-title">Пополнение баланса аккаунта</h4>';
echo '<p>В данный момент мы работаем с платежной системой <b>Yandex.Money</b>, остальные платежные системы появятся немного позже. Комиссию за перевод мы берем на себя.</p>';

if($_GET['action'] == 'confirm'){
	if($_POST['sum'] < 1 ){
		echo '<p>Депозит не может быть меньше <b>50руб</b>.</p>';
	}else{
		$sql = 'INSERT INTO deposit (user_id, sum, time, status) VALUES ('.$user['id'].', '.$_POST['sum'].', "'.date("d.m.Y H:i:s").'", 0)';
		if(mysqli_query($conn, $sql)){
			echo '<p>Вы пополняете баланс аккаунта <b>'.$user['login'].'</b> на сумму <b>'.$_POST['sum'].' руб</b>.</p>';
			echo '<p>Сейчас вы будете перенаправлены на сайт оплаты <b>Яндекс.Деньги</b></p>';
			//echo '<p>Номер транзакции: ' . mysqli_insert_id($conn);
			?>
			<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
				<input type="hidden" name="receiver" value="4100115282494511">
				<input type="hidden" name="formcomment" value="Vk-clean. Пополнение аккаунта <? echo $user['login'];?>">
				<input type="hidden" name="short-dest" value="Vk-clean. Пополнение аккаунта <? echo $user['login'];?>">
				<input type="hidden" name="label" value="<? echo mysqli_insert_id($conn);?>">
				<input type="hidden" name="quickpay-form" value="shop">
				<input type="hidden" name="successURL" value="https://vk-clean.ru">
				<input type="hidden" name="targets" value="Vk-clean. Пополнение аккаунта <? echo $user['login'];?>">
				<input type="hidden" name="sum" value="<? echo $_POST['sum'];?>" data-type="number">
				<input type="hidden" name="paymentType" value="PC">
				<button class="btn" style="display:none" type="submit">Перевести</button>
			</form>
			<script>
				setTimeout(function(){
				  $('.btn').trigger('click');
				}, 3000);
			</script>
			<?
		}else{
			echo '<p>Что-то пошло не так, приносим извинения. Проблема будет решена в ближайшее время.</p>';
			mysqli_query($conn, 'INSERT INTO log (errors, time) VALUES("Проблема с пополнением счета", "'.date("d.m.Y H:i:s").'")');
		}
	}
}else{
?>
<form class="contact-form" action="/?page=deposit&action=confirm" method="POST">
	<input type="text" placeholder="Минимальная сумма пополнения 50 руб." required name="sum">
	<button class="btn" name="submit">Пополнить</button>
</form>
<div class="res_spacer"></div>
<h4 class="block-title">История пополнений (последние 10):</h4>
<?
$history = mysqli_query($conn, 'SELECT * FROM deposit WHERE user_id = '.$user['id'].' ORDER BY id DESC LIMIT 10');

if(mysqli_num_rows($history) == 0){
	echo '<p>Депозитов не было.</p>';
}else{
	$i = 1;
	echo '<table class="user_table">';
	while($row = mysqli_fetch_array($history)){
		echo '<tr>';
			echo '<td>'.$i++.'</td>';
			echo '<td>'.$row['time'].'</td>';
			echo '<td>'.$row['sum'].' руб.</td>';
			switch($row['status']){
				case '0':
					$status = 'Ожидается';
					break;
				case '1':
					$status = 'Платеж зачислен';
					break;
				case '2':
					$status = 'Отменен';
					break;				
			}
			echo '<td align="right">'.$status.'</td>';
		echo '</tr>';
	}
	echo '</table>';
}







}
?>