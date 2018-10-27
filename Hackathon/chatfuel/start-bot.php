<?php

require_once 'render-json.php';

try {
    $db = new mysqli("localhost", "hackathon14", "pcms", "hackathon14");
    mysqli_set_charset($db, "utf8");

    $email = $_GET['user_email'];
    $fb_id = $_GET['messenger_user_id'];

    if ($db->connect_errno) {
        $err_no = $mysqli->connect_errno;
        $error = $mysqli->connect_error;
        throw new Error("Failed to connect to MySQL: ($err_no) $error");
    }

    $sql = "SELECT * FROM user WHERE email='$email'";
    $res = $db->query($sql);
    if ($res->num_rows === 0) throw new Error('Data not found');

    $row = $res->fetch_assoc();
    $id = $row['id'];
    $zone = $row['zone'];

    $sql = "UPDATE user SET fb_id='$fb_id' WHERE id='$id'";
    if (!$db->query($sql)) throw new Error('Failed to update');
    renderMsg($email);

} catch (Exception $e) {
    renderJson($e->getMessage());
}
