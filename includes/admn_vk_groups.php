<?
if(!$_SESSION['login'] and $user['status'] != 9){
    print "<script language=\"javascript\">top.location.href=\"/\";</script>";
}
$sql = 'SELECT * FROM groups ORDER BY id DESC';





?>