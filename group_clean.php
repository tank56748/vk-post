<?
$service = 'b9110328b9110328b911032819b961b55ebb911b9110328e7945734d2881c793c5e52c8';
require_once('cnf.php');
$res = mysqli_query($conn, 'SELECT * FROM my_groups WHERE id = '.$_POST['id'].'');
$res_row = mysqli_fetch_array($res);
$user_row = mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM users WHERE id = '.$res_row['user_id'].' LIMIT 1'));
$group_row = mysqli_fetch_array(mysqli_query($conn, 'SELECT * FROM groups WHERE url = "'.$res_row['group_sn'].'" LIMIT 1'));

mysqli_query($conn, 'UPDATE my_groups SET err_code = 0, error = "" WHERE id = '.$_POST['id'].'');
mysqli_query($conn, 'UPDATE groups SET cln_status = 2 WHERE url = "'.$res_row['group_sn'].'"');

	$params = array(
		'v'            => '5.103',
		'access_token' => $service,
		'group_id'     => $group_row['group_id'], 
		'count'		   => '1',
		'offset'	   => '0',
		'sort'         => 'id_asc',
	);

	$post = file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($params));
	$response = json_decode($post, true);	

	$members_count = $response['response']['count'];

	mysqli_query($conn, 'UPDATE groups SET users = '.$members_count.' WHERE group_id = '.$group_row['group_id'].'');

	$dogs = array();
	$num = 0;
	$num_na = 0;
	
	for($i=0;$i<=$members_count;$i=$i+1000){
		$time_script = time();
		set_time_limit(30);
		mysqli_query($conn, 'UPDATE groups SET cln_offset = '.$i.', time_script = '.$time_script.' WHERE group_id = '.$group_row['group_id'].'');
		$params = array(
			'v'            => '5.103',
			'access_token' => $service,
			'group_id'     => $group_row['group_id'], 
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
			'v'            => '5.103',
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

		$dead_line = time() - 2592000 * $group_row['na_val'];

		foreach($users_res['response'] as $user){
			if(array_key_exists('deactivated', $user)){
				if($group_row['cln_dogs'] == 1){
					$dogs_del = file_get_contents('https://api.vk.com/method/groups.removeUser?v=5.110&group_id='.$group_row['group_id'].'&user_id='.$user['id'].'&access_token='.$user_row['token'].'');					
					$dogs_del_resp = json_decode($dogs_del, true);
					if($dogs_del_resp['response'] == 1){
						$num++;
					}elseif($dogs_del_resp['error']){
						mysqli_query($conn, 'UPDATE my_groups SET err_code = '.$dogs_del_resp['error']['error_code'].', error = "'.$dogs_del_resp['error']['error_msg'].'" WHERE id = '.$res_row['id'].'');
						mysqli_query($conn, 'INSERT INTO error_log (group_id, user, del_id, err_code, error) VALUES ('.$group_row['group_id'].', "'.$user_row['login'].'", '.$user['id'].', '.$dogs_del_resp['error']['error_code'].', "'.$dogs_del_resp['error']['error_msg'].'")');
						$token_error = 1;
						goto end_script;
					}
					usleep(220000);
				}
			}elseif($user['last_seen']['time'] < $dead_line){
				if($group_row['cln_na'] == 1){
					$na_del = file_get_contents('https://api.vk.com/method/groups.removeUser?v=5.110&group_id='.$group_row['group_id'].'&user_id='.$user['id'].'&access_token='.$user_row['token'].'');						
					$na_del_resp = json_decode($na_del, true);
					if($na_del_resp['response'] == 1){
						$num_na++;
					}elseif($na_del_resp['error']){
						mysqli_query($conn, 'UPDATE my_groups SET err_code = '.$na_del_resp['error']['error_code'].', error = "'.$na_del_resp['error']['error_msg'].'" WHERE id = '.$res_row['id'].'');
						mysqli_query($conn, 'INSERT INTO error_log (group_id, user, del_id, err_code, error) VALUES ('.$group_row['group_id'].', "'.$user_row['login'].'", '.$user['id'].', '.$na_del_resp['error']['error_code'].', "'.$na_del_resp['error']['error_msg'].'")');
						$token_error = 1;
						goto end_script;						
					}
					usleep(220000);
				}
			}
		}
		$ids = '';
	}

	end_script:

	$params = array(
		'v'            => '5.103',
		'access_token' => $service,
		'group_id'     => $group_row['group_id'], 
		'count'		   => '1',
		'offset'	   => '0',
		'sort'         => 'id_asc',
	);

	$post = file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($params));
	$response = json_decode($post, true);	

	$members_count = $response['response']['count'];
	
	$cln_time = date("d.m.Y H:i:s");
	if($token_error == 1){
		$cln_status = 0;
	}else{
		$cln_status = 0;
	}
	mysqli_query($conn, 'UPDATE groups SET users = '.$members_count.', dogs = dogs - '.$num.', na = na - '.$num_na.', cln_status = '.$cln_status.', cln_dogs = 0, cln_na = 0, last_clean = "'.$cln_time.'", dogs_del = '.$num.', na_del = '.$num_na.' WHERE group_id = '.$group_row['group_id'].'');

mysqli_close($conn);










?>