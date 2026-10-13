<?php
session_start();
include_once("../applicantlist.php");
include_once("../config.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (empty($_SESSION["admin"])) {
        http_response_code(401);
        echo "<img src = 'https://http.cat/401' />";
        exit;
    }
    $applicantlist = new Applicantlist();
    echo json_encode(array(
        "summary" => $applicantlist->getStats(),
        "majors" => $applicantlist->getMajorStats(SZAKOK)
    ));
} else {
    http_response_code(405);
}
