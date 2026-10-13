<?php
session_start();
include_once("../applicantlist.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if (empty($_SESSION["admin"])) {
        http_response_code(401);
        echo "<img src = 'https://http.cat/401' />";
        exit;
    }
    $applicantlist = new Applicantlist();
    $result = $applicantlist->delete($_GET["id"]);
    if ($result == false) {
        http_response_code(400);
        echo "<img src = 'https://http.cat/400' />";
    } else {
        http_response_code(200);
    }
} else {
    http_response_code(405);
}
