<?php
session_start();
include_once("../config.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request = json_decode(file_get_contents('php://input'), true);
    if (($request["password"] ?? "") === ADMIN_JELSZO) {
        session_regenerate_id(true);
        $_SESSION["admin"] = true;
        http_response_code(200);
    } else {
        http_response_code(401);
        echo "<img src = 'https://http.cat/401' />";
    }
} else {
    http_response_code(405);
}
