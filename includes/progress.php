<?
require('../cnf.php');
$group = end(explode('/', $_POST['url']));
$sql = 'SELECT * FROM groups WHERE url = "https://vk.com/'.$group.'" LIMIT 1';
$res = mysqli_fetch_array(mysqli_query($conn, $sql));
echo $res['chk_status'].','.$res['users'].','.$res['offset'].','.$res['dogs'].','.$res['na'];
mysqli_close($conn);

?>