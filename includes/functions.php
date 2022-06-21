<?
function group_add($group_sn, $scan){
	
	global $conn;

	$in_base = 'SELECT * FROM groups WHERE url = "https://vk.com/'.$group_sn.'" LIMIT 1';
	$in_base_row = mysqli_num_rows(mysqli_query($conn, $in_base));
	
	if($in_base_row == 1){
		$request = mysqli_fetch_array($conn, $in_base);

		if($request['chk_status'] == 0 or $request['chk_status'] == 3){
			mysqli_query($conn, 'UPDATE groups SET chk_status = '.$scan.', na_val = 6 WHERE url = "https://vk.com/'.$group_sn.'"');
			if($scan == 1){
				exec('/usr/bin/curl -o /dev/null -silent --data "sn='.$group_sn.'" https://vk-post.ru/group_scan.php > /dev/null &');
			}
			$msg = $group_sn;
		}else{
			$msg = $group_sn;
		}
	}else{
		$service = 'b9110328b9110328b911032819b961b55ebb911b9110328e7945734d2881c793c5e52c8';
		$params = array(
			'v'            => '5.103',
			'access_token' => $service,
			'screen_name'  => $group_sn, 
		);
		
		$post = file_get_contents('https://api.vk.com/method/utils.resolveScreenName?' . http_build_query($params));
		$response = json_decode($post, true);

		$group_type = $response['response']['type'];
		
		if($group_type == 'user'){
			$msg = 'user';
		}else{
		
			$params = array(
				'v'            => '5.103',
				'access_token' => $service,
				'group_id'  => $response['response']['object_id'], 
			);
			
			$post = file_get_contents('https://api.vk.com/method/groups.getById?' . http_build_query($params));
			$response = json_decode($post, true);

			$group_id = $response['response'][0]['id'];
			$group_name = $response['response'][0]['name'];
			$group_ava = $response['response'][0]['photo_50'];
			
			$to_base = 'INSERT INTO groups (url, group_id, ava, group_name, chk_status, na_val) VALUES ("https://vk.com/'.$group_sn.'", '.$group_id.', "'.$group_ava.'", "'.$group_name.'", '.$scan.', 6)';
			if(mysqli_query($conn, $to_base)){
				if($scan == 1){
					exec('/usr/bin/curl -o /dev/null -silent --data "sn='.$group_sn.'" https://vk-post.ru/group_scan.php > /dev/null &');
				}
				$msg = $group_sn;
			}else{
				$msg = 'error';
			}
		}
	}
	return $msg;
}
function group_clean(){
	
}


?>