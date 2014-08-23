<?php

	echo 'xxxxxxxxxxx';

	$urls = array(
		'http://61.164.173.216:9920/gamelogin/text.php',
		'http://61.164.173.216:9920/gamelogin/text2.php'
	);

	$data = array(
		'name'=>'harry',
		'age'=>24
	);

	$options = array(
	 	CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER         => false,
        CURLOPT_VERBOSE        => true,
        CURLOPT_AUTOREFERER    => true,         
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
    );

	$options[CURLOPT_POST] = true;
    $options[CURLOPT_POSTFIELDS] = $data;

    $ch_array = array();

	foreach ($urls as $url) {
		$ch = curl_init($url); 
    	curl_setopt_array($ch, $options);
		array_push( $ch_array,$ch );
	}


	$mh = curl_multi_init(); //初始化curl批处理句柄
	foreach($ch_array as $ch)
	{
		curl_multi_add_handle($mh, $ch);
	}

	$running = null;
	do
	{
		curl_multi_exec($mh, $running); //批量执行
	}while($running > 0);

    //$output = curl_exec($ch); 



	$result_array = array(); //返回数组
	foreach($ch_array as $key => $ch)
	{
		$result_array[$key]['curl_data'] = curl_multi_getcontent($ch);
		
		$curl_info = curl_getinfo($ch);
		// $result_array[$key]['http_code'] = $curl_info['http_code'];
		$result_array[$key]['http_code'] = $curl_info;
		curl_multi_remove_handle($mh, $ch);
	}

	curl_multi_close($mh);
	
	print_r( $result_array );

    // echo $output;

    // curl_close($ch);

    echo 'yyyyyyyyyyyyyyyy';
?>