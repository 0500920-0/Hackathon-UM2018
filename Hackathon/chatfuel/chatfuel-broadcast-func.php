<?php

function broadcast($fb_id, $user, $chatfuel_block_name, $data=array()){
    $bot_id = '5bd2ee2776ccbc2c7d9b1710';
    $chatfuel_token = 'mELtlMAHYqR0BvgEiMq8zVek3uYUK3OJMbtyrdNPTrQB9ndV0fM7lWTFZbM4MZvD';
    $url = "https://api.chatfuel.com/bots/$bot_id/users/$fb_id/send";
    $chatfuel_parm = array(
        'chatfuel_token' => $chatfuel_token,
        'chatfuel_block_name' => $chatfuel_block_name
    );
    $post_data = json_encode($data);
    $qs = http_build_query($chatfuel_parm);
    $url = "$url?$qs";
    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    /*
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($post_data))
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    */
    curl_setopt_array($ch, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    ));

    // execute post
    $result = curl_exec($ch);
    // close connection
    curl_close($ch);
    $errorNames = "";
    if (strpos($result, 'false') == TRUE) {
      echo "$user 無法傳送 $result\n";
      $errorNames .= $userId.",";
      return $errorNames;
    } else {
      echo "$user 成功傳送\n";
    }
}
