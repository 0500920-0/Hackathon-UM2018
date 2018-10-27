<?php

require_once 'chatfuel-broadcast-func.php';


$db = new mysqli("localhost", "hackathon14", "pcms", "hackathon14");
mysqli_set_charset($db, "utf8");

if ($db->connect_errno) {
    $err_no = $mysqli->connect_errno;
    $error = $mysqli->connect_error;
    throw new Error("Failed to connect to MySQL: ($err_no) $error");
}


// broadcast(1924079884339400, 'testing@umac.mo', 'alert');

?>
