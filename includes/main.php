<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<h4 class="block-title">Сканирование групп на "Собачек" и давно забытые аккаунты</h4>
<p>Пользователей во вконтакте блокируют или они сами удаляются - появляются так называемые <b>"Собачки"</b>. 
Так же у пользователей может пропасть доступ к своей странице и эти аккаунты становятся <b>"Мертвыми"</b>. 
В этих и многих других случаях аудитория группы, сообщества или паблика начинает становиться неактивной - мертвой. 
Этот плохо влияет на релевантность, охват и стоимость рекламы.</p>

<p>Регистрация на проекте по приглашениям. Если Вы хотите зарегистрироваться - запросите ссылку через меню "Контакты".</p>
<?


if(isset($_COOKIE['group'])){
	$last_chk = 'SELECT * FROM groups WHERE url = "https://vk.com/'.$_COOKIE['group'].'" LIMIT 1';
	$last_row = mysqli_fetch_array(mysqli_query($conn, $last_chk));

	if($last_row['chk_status'] == 1 or $last_row['chk_status'] == 2){
		$style = 'style="background-color:#5181b8;height:20px;width:0px;"';
		echo '<div class="result">';
		echo '<h3><img class="group_img" src = '.$last_row['ava'].'> '.$last_row['group_name'].'</h3><hr>';
		echo '<p>Подготовка к сканированию...</p>';
		echo '<div class="res_spacer"></div>';
		echo '<div class="progress"><div '.$style.' class="procent"></div></div>';
		echo '<div class="proc_now" style="float:right;">'.round($proc, 2, PHP_ROUND_HALF_DOWN).'%</div>';
		echo '</div>';

		?>
		<script>
		function procent(){
			$.ajax({
				type:'POST',
				url:'includes/progress.php',
				data:'url=<? echo $last_row['url']; ?>',
				success:function(data){
					var res = data.split(',');
					if(res[0] == 2){
						var full = res[1];
						var full_nums = new Intl.NumberFormat('ru-RU').format(res[1]);
						var progress = res[2];
						var progress_nums = new Intl.NumberFormat('ru-RU').format(res[2]);
						var proc = (progress*100/full).toFixed(2);
						$('.procent').css('width', proc+'%');
						$('div.result p').html('Идет сканирование. Пожалуйста подождите.<br>Всего подписчиков: <b>'+full_nums+'</b><br>Проверено: <b>'+progress_nums+'</b></br>Сканирование может занять некоторое время.');
						$('.proc_now').html(proc+'%');
					}else if(res[0] == 0){
						window.location.href = '/?page=groups_details&group_id=<? echo $last_row['group_id']; ?>';
					}
				}
			});
		}
		procent();
		setInterval(procent, 5000);
		</script>
		<?
		
	}else{
		include('includes/scan.php');
	}
}else{
	include('includes/scan.php');
}

?>