<?php
$get = $_GET;
$post = $_POST;
$app_json = json_decode(file_get_contents('php://input'));
$response = array();
$response['GET'] = $get;
$response['POST'] = $post;
$response['JSON'] = $app_json;
echo json_encode($response);
die();
?>