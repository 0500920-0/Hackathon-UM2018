<?php

function renderJson ($array) {
    header('Content-type: application/json');
    echo json_encode($array);
    exit;
}

function renderMsg ($msg) {
    renderJson(array(
        'messages' => array(
            array(
                'text' => $msg
            )
        )
    ));
}

function dump ($it) {
    echo '<pre>';
    var_dump($it);
    echo '</pre>';
    exit;
}
