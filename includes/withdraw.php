<?
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}

echo '<h4 class="block-title">Вывод средств</h4>';
$sql = 'SELECT ref_bal FROM users WHERE id = "'.$user['id'].'"';
$res = mysqli_fetch_array(mysqli_query($conn, $sql));

if($_GET['to'] == 'account'){
	$sql = 'UPDATE users SET akk_bal = akk_bal + ref_bal, ref_bal = 0 WHERE id = '.$user['id'].'';
	if(mysqli_query($conn, $sql)){
		echo '<p>Денежные средства в размере <b>'.$res['ref_bal'].' руб</b>. были зачислены на основной счет.</p>';
		$user['akk_bal'] += $res['ref_bal'];
		$user['ref_bal'] = 0;
	}else{
		echo '<p>Что-то пошло не так, повторите попытку позже.</p>';
	}
	
}elseif($_GET['to'] == 'wallet'){
	echo '<p>Пожалуйста, введите номер кошелька для вывода.</p>';
	echo '<form class="contact-form" action="/?page=withdraw&to=send" method="POST">';
		echo '<input type="text" placeholder="Номер кошелька в яндекс.деньгах" required name="nums">';
		echo '<button class="btn" name="submit">Вывести</button>';
	echo '<form>';

}elseif($_GET['to'] == 'send'){
	if($res['ref_bal'] != 0){
		$bal = mysqli_query($conn, 'UPDATE users SET ref_bal = 0 WHERE id = '.$user['id'].'');
		$req = mysqli_query($conn, 'INSERT INTO withdraw (user_id, wallet, sum, time, status) VALUES ('.$user['id'].', '.intval($_POST['nums']).', '.$res['ref_bal'].', "'.date("d.m.Y H:i:s").'", 0)');
		if($bal and $req){
			$user['ref_bal'] = 0;
			echo '<p>Запрос на вывод <b>'.number_format($res['ref_bal'], 2, '.', ' ').'руб</b>. создан. Ожидайте выплату.</p>';
		}
	}else{
		print "<script language=\"javascript\">top.location.href=\"/?page=withdraw\";</script>";
	}
	
}else{
	echo '<p>Внимание! Выводу подлежат только те средства, которые были заработаны на реферальной программе.</p>';
	echo '<p>Вывод средств проводится в ручном режиме и может занять до 24 часов.</p>';
	echo '<p>Просим Вас быть терпиливыми. Если по истечению 24 часов средства так и не были зачислены на Ваш счет, пожалуйста, обратитесь в службу поддержки через форму обратной связи, либо напрямую через электронную почту. Мы в кратчайшие сроки постараемся решить сложившуюся ситуацию.</p>';


	echo '<p>Доступно к выводу: <b>' . number_format($res['ref_bal'], 2, '.', ' ') . ' руб.</b></p>';
	if($res['ref_bal'] > 0){
		echo '<p><button class="btn" onClick="javascript:top.location.href=\'/?page=withdraw&to=wallet\'">Вывести на кошелек</button></p>';
		echo '<p><button class="btn" onClick="javascript:top.location.href=\'/?page=withdraw&to=account\'">Перевести на счет аккаунта</button></p>';
	}
	echo '<div class="res_spacer"></div>';
	echo '<h4 class="block-title">История выплат (последние 10):</h4>';
	$withdraw = mysqli_query($conn, 'SELECT * FROM withdraw WHERE user_id = '.$user['id'].' ORDER BY id DESC LIMIT 10');
	if(mysqli_num_rows($withdraw) == 0){
		echo '<p>Выплат не было.</p>';
	}else{
		$i = 1;
		echo '<table class="user_table">';
		while($row = mysqli_fetch_array($withdraw)){
			echo '<td>'.$i++.'</td>';
			echo '<td>'.$row['time'].'</td>';
			echo '<td>'.number_format($row['sum'], 2, '.', ' ').' руб.</td>';
			echo '<td align="center">'.$row['wallet'].'</td>';
			switch($row['status']){
				case '0':
					$status = 'Ожидается';
					break;
				case '1':
					$status = 'Выплачено';
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