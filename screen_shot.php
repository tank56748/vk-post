<?php
//file.php
$name = 'upload/screens/'.time().'.png';
file_put_contents($name, base64_decode($_POST['data'] ));
echo( $name );
?>