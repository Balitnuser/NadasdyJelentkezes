<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION = array();
    session_destroy();
    http_response_code(200);
} else {
    http_response_code(405);
}
