<?php
$sql = 'UPDATE users SET hash = "" WHERE user = "'.$_SESSION['login'].'"';
mysqli_query($conn, $sql);
setcookie("hash", '', time() - 1, "/");
$login = "";
$msg = 'Хорошего дня, '.$_SESSION['login'].'. До новых встреч!';
unset($_SESSION["login"]);
?>
<script>
var msg = "<? echo $msg;?>";
if(msg !== ""){
	setTimeout(
		() => {
			alert(msg);
			window.location='/';
		},700
	);
}
</script>