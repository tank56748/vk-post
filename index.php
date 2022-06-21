<?
require_once "cnf.php";
session_start();
if($_GET['page']){
	$page = $_GET['page'];
}
$css_rand = mt_rand(1, 100);

if($_GET['from']){
	setcookie('from', $_GET['from'], time() + 2592000);
}

?>


<!doctype html>
<html class="no-js" lang="ru-RU">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
	<meta name="yandex-verification" content="eab16f39ec3eff02" />
    <title>Чистый Вконтакте</title>
	<meta name="description" content="Чистка собачек в группе. Чистый вк. " />
	<meta name="keywords" content="Собачки, чистка, удаление." />
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
 	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
 	<link rel="stylesheet" href="assets/css/style.css?5">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="apple-mobile-web-app-title" content="vk-post.ru">
	<meta name="application-name" content="vk-post.ru">
	<meta name="msapplication-TileColor" content="#2d89ef">
	<meta name="theme-color" content="#ffffff">
	<!-- Yandex.RTB -->
	<script>window.yaContextCb=window.yaContextCb||[]</script>
	<script src="https://yandex.ru/ads/system/context.js" async></script>

</head>
<body>
<section id="main">
    <div class="container">

        <div class="row">
            <div class="col-sm-12 header-text">
                <h1>Чистый ВКонтакте</h1><hr>
            </div>
        </div>
				<div class="res_spacer"></div>
				<div class="res_spacer"></div>
		<!-- Yandex.RTB R-A-1678774-1 -->
		<div id="yandex_rtb_R-A-1678774-1"></div>
		<script>window.yaContextCb.push(()=>{
				Ya.Context.AdvManager.render({
					renderTo: 'yandex_rtb_R-A-1678774-1',
					blockId: 'R-A-1678774-1'
				})
			})</script>
        <div class="row">
            <div class="col-sm-8">
				<?
				if(isset($_SESSION['login']) and ($user['mail_chk'] == 0 or $user['mail_chk'] == '')){
					if($page == 'exit'){
						include ("includes/exit.php");
					}elseif($page == 'verify'){
						include ("includes/verify.php");
					}elseif($page == 'profile'){
						include ("includes/profile.php");
					}else{
						include ("includes/mail.php");
					}
				}else{
					if($page){
						$inc = $page.'.php';
						if(file_exists("includes/".$inc."")){
							include("includes/".$inc."");
						}else{
							include("includes/errors/404.php");
						}
					}else{
						include ("includes/main.php");
					}
				}

				?>


            </div>
            <div class="col-sm-4">
				<div class="menu_mobile"><h4 class="block-title">Меню</h4></div>
                <div class="right-content-block right_menu">
					<? include('includes/menu/menu.php');?>
                </div>
                <br>
                <div class="right-content-block">

                </div>
            </div>
        </div>
    </div>
</section>


<footer>
    <div class="container">
        <div class="row">
          <div class="col-md-1 edward-img">
              <img src="img/edward.png" alt="">
          </div>
          <div class="col-md-7 edward-details">
              <h4>Разработчики</h4>
                <p>
                    Добро пожаловать на наш ресурс. У нас вы найдете инструменты для очистки группы в ВК от собачек и неактивных пользователей, полезную информацию и о ваших сообществах, реферальную программу для заработка. Мы всегда открыты для диалога и если у вас есть предложение по улучшению, то напишите нам через форму <a href="?page=contact">обратной связи</a> и мы вместе подумаем над решением.
                </br></br>
                </p>
                <!-- <ul class="list-inline social">
                    <li><a href=""><i class="fa fa-facebook"></i></a></li>
                    <li><a href=""><i class="fa fa-twitter"></i></a></li>
                    <li><a href=""><i class="fa fa-github"></i></a></li>
                    <li><a href=""><i class="fa fa-dribbble"></i></a></li>
                    <li><a href=""><i class="fa fa-behance"></i></a></li>
                    <li><a href=""><i class="fa fa-link"></i></a></li>
                </ul>-->
          </div> <!-- END col-md-8 -->
          <div class="col-md-4 copy-right">
              <p>Все права защищены © 2022 vk-post.ru</p>
          </div>
        </div> <!-- END row-->
    </div> <!-- END container-->
</footer>
<style type="text/css">
    @media (max-width: 480px) {
        .rc-anchor-normal {
            width: 274px !important;
        }
        .rc-anchor-logo-portrait {
            margin: 10px 0 0 !important;
        }
        .rc-anchor-normal .rc-anchor-pt {
            right: 25px !important;
            width: 276px;
        }
}
</style>

        <script src="assets/js/scripts.js"></script>
        <script type="text/javascript">
function downloadJSAtOnload() {
var element = document.createElement("script");
element.src = "https://www.google.com/recaptcha/api.js";
document.body.appendChild(element);
}
if (window.addEventListener)
window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;
</script>
    </body>
</html>
