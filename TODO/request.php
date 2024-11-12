<?php
    function sendRequest($request)
    {
        exit($request);
    }


$url = "https://full-example.local/api.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $query_get = http_build_query($_GET);
    $request_get = file_get_contents("$url?$query_get");
    sendRequest($request_get);
} elseif ($method == 'POST') {
    $query_post = http_build_query($_POST);
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => $query_post,
        ],
    ];
    $context  = stream_context_create($options);
    $request_post = file_get_contents($url, false, $context);

    sendRequest($request_post);
}
