<?
$service = 'b9110328b9110328b911032819b961b55ebb911b9110328e7945734d2881c793c5e52c8';
require_once('cnf.php');

$scan = 'SELECT * FROM groups WHERE url = "https://vk.com/'.$_POST['sn'].'" LIMIT 1';
$scan_sql = mysqli_query($conn, $scan);

if(mysqli_num_rows($scan_sql) == 1){
	$res = mysqli_fetch_array($scan_sql);
	$time_start = time();
	mysqli_query($conn, 'UPDATE groups SET chk_status = 2, time_start = '.$time_start.' WHERE group_id = '.$res['group_id'].'');
	
	$params = array(
		'v'            => '5.110',
		'access_token' => $service,
		'group_id'     => $res['group_id'], 
		'count'		   => '1',
		'offset'	   => '0',
		'sort'         => 'id_asc',
	);

	$post = file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($params));
	$response = json_decode($post, true);	

	$members_count = $response['response']['count'];

	mysqli_query($conn, 'UPDATE groups SET users = '.$members_count.' WHERE group_id = '.$res['group_id'].'');

	$dogs = array();
	$num = 0;
	$num_na = 0;

	for($i=0;$i<=$members_count;$i=$i+1000){
		$time_script = time();
		set_time_limit(30);
		mysqli_query($conn, 'UPDATE groups SET offset = '.$i.', time_script = '.$time_script.' WHERE group_id = '.$res['group_id'].'');
		$params = array(
			'v'            => '5.110',
			'access_token' => $service,
			'group_id'     => $res['group_id'], 
			'count'		   => '1000',
			'offset'	   => $i,
			'sort'         => 'id_asc',
		);

		$post = file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($params));
		$response = json_decode($post, true);
		
		
		foreach($response['response']['items'] as $id){
			$ids .= $id . ',';
		}
		
		$ids = mb_substr($ids, 0, -1);
			
		$params = array(
			'v'            => '5.110',
			'user_ids'     => $ids,
			'fields'       => 'last_seen',
			'access_token' => $service,
		);
		
		$users_dogs = 'https://api.vk.com/method/users.get';
		$response_dogs = file_get_contents($users_dogs, false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
			)
		)));
		$users_res = json_decode($response_dogs, true);

		$dead_line = time() - 2592000 * $res['na_val'];

		foreach($users_res['response'] as $user){
			if(array_key_exists('deactivated', $user)){
				$num++;
			}elseif($user['last_seen']['time'] < $dead_line){
				$num_na++;
			}
		}
		$ids = '';
	}

	$chk_time = date("d.m.Y H:i:s");
	$db_res = 'UPDATE groups SET users = '.$members_count.', dogs = '.$num.', na = '.$num_na.', last_chk = "'.$chk_time.'", chk_status = 0 WHERE group_id = '.$res['group_id'].'';
	mysqli_query($conn, $db_res);
}

mysqli_close($conn);














?>