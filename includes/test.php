<?
$sum = intval('10,95');
mysqli_query($conn, 'UPDATE users SET akk_bal = akk_bal + '.$sum.' WHERE id = 3');
?>
