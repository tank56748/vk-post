<?
/*
if(!$_SESSION['login']){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}*/
?>
<h4 class="block-title">Инструкция по созданию токен-ключа</h4>
<p>Мы, конечно, всемогущи и можем творить абсолютно все что нам захочется, но, мы не настолько беспридельщики и меры приличия мы блюдим свято. Что бы Мы могли помогать Вам
в Вашем не легком деле в продвижении и поддержании, поддерживать Ваши группы в ВК в надлежащем состоянии нам требуется доступ к Вашим группам. Для этого нам достаточно токен-ключа, который Вы можете нам предоставить.</p>

<p>Токен, полученный данным способом, действует на <b>все</b> группы, которые принадлежат Вам.</p>

<p>Заходим на сайт <b><a href="https://vk.com/dev" target="_blank">https://vk.com/dev</a></b>, сверху жмем кнопку <b>"Мои приложения"</b> => <b>"Создать приложение"</b>.</p>

<p>Обзываем приложение как Вам угодно, но так, что бы Вы сами понимали за что данное приложение отвечает. Ставмм галку "Standalone-приложение" и жмем "Подключить приложение".</p>

<p><b>Внимание!!!</b> Каждый раз старайтесь создавть новое приложение и давать новые имена приложениям на каждое действие, иначе Вы можете потерять функционал или просто запутаться в Приложениях.</p>

<p>Приложение готово, осталось дело за малым...</p>

<p>Для удобства можете скопировать ваши ключи ниже и нужные ссылки сформируются сами:</p>

<div class="res_spacer"></div>
<form class="contact-form">
	<input type="text" id="app_id" placeholder="ID Вашего приложения"/>
</form>

<p>Какие-либо настройки приложения не требуются. Все что нам нужно дальше - это <b>ID приложения</b> и <b>Защищенный ключ.</b> Для этого заходим в настройки и видим там всю необходимую информацию.</p>

<p>Эту строку надо скопировать и вставить в адресную строку Вашего браузера:</p>

<hr>
<span class="link1"><b>https://oauth.vk.com/authorize?client_id=ID_ПРИЛОЖЕНИЯ?display=page&redirect_uri=https://api.vk.com/blank.html&scope=offline,groups&<br>response_type=code</b></span>
<hr>

<p>Где <b>'ID_ПРИЛОЖЕНИЯ'</b> - ID вашего приложения. Обратите внимание на параметр <b>scope</b>. Если не указать параметр <b>'offline'</b> - тогда токен будет ограничен по времении и будет одноразовым. Про остальные разрешения Вы всегда можете прочитать на официальной странице ВК - <b><a href="https://vk.com/dev/permissions" target="_blank">https://vk.com/dev/permissions</a></b></p>

<p>В ответ мы получаем адресную строку вида: <hr><b>https://api.vk.com/blank.html#code=КАКОЕ-ТО_ЗНАЧЕНИЕ</b></p><hr>

<p>Где <b>КАКОЕ-ТО_ЗНАЧЕНИЕ</b> - это наш секретный код.</p>

<p>Далее нам понадобится данный код и Ваш <b>"защищенный ключ"</b></p>

<div class="res_spacer"></div>
<form class="contact-form">
	<input type="text" id="code" placeholder="Полученный код"/>
	<input type="text" id="secret" placeholder="Защищенный ключ" />
</form>

<hr>
<span class="link2"><b>https://oauth.vk.com/access_token?client_id=ID_ПРИЛОЖЕНИЯ&client_secret=ЗАЩИЩЕННЫЙ_КЛЮЧ&<br>redirect_uri=https://api.vk.com/blank.html&code=СЕКРЕТНЫЙ_КОД</b></span>
<hr>

<p>Где <b>'ID_ПРИЛОЖЕНИЯ'</b> - ID вашего приложения, <b>'ЗАЩИЩЕННЫЙ_КЛЮЧ'</b> - защищенный ключ Вашего приложения и <b>'СЕКРЕТНЫЙ_КОД'</b> - код, полученный в предидущем шаге.</p>

<p>В ответ мы получаем адресную строку вида:</p>

<p><hr><b>https://api.vk.com/blank.html#access_token=МНОГО_СИМВОЛОВ&expires_in=0&user_id=123456</b><hr></p>

<p>Вот мы и получили нужный нам токен - '<b>access_token</b>'. Обратите внимание на параметр '<b>expires_in</b>' - он должен быть равен "0" иначе ключ будет ограничен по времени и будет одноразовый. Т.е. как только мы проверим его на валидность он перестанет работать и мы ничего не сможем сделать.</p>

<p>Копируем "МНОГО_СИМВОЛОВ" и сохраняем этот код в Вашем профиле. Все.</p>


<script>
$('form :input').bind('change keyup input', function(){
	var id = $('#app_id').val();
	var secret = $('#secret').val();
	var code = $('#code').val();
	$('.link1').html('<b><a href="https://oauth.vk.com/authorize?client_id='+id+'&display=page&redirect_uri=https://api.vk.com/blank.html&scope=offline,groups&response_type=code" target="_blank">https://oauth.vk.com/authorize?client_id='+id+'&display=page&redirect_uri=https://api.vk.com/blank.html&<br>scope=offline,groups&response_type=code</a></b>');
	$('.link2').html('<b><a href="https://oauth.vk.com/access_token?client_id='+id+'&client_secret='+secret+'&redirect_uri=https://api.vk.com/blank.html&code='+code+'" target="_blank">https://oauth.vk.com/access_token?client_id='+id+'&client_secret='+secret+'&redirect_uri=https://api.vk.com/blank.html&code='+code+'</a></b>');
	
});

</script>