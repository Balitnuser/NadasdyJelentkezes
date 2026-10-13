<?php
include_once("../applicantlist.php");
include_once("../config.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $applicantlist = new Applicantlist();
    $request = json_decode(file_get_contents('php://input'), true);
    $name = trim($request["name"] ?? "");
    $school = trim($request["school"] ?? "");
    $majors = $request["majors"] ?? array();
    $dorm = $request["dorm"] ?? -1;
    if (mb_strlen($name) < 3 || mb_strlen($name) > 50 || mb_strlen($school) < 3 || mb_strlen($school) > 50 || !is_array($majors) || count($majors) == 0 || count(array_diff($majors, SZAKOK)) > 0 || !in_array($dorm, array(0, 1, 2), true)) {
        http_response_code(400);
        echo "<img src = 'https://http.cat/400' />";
        exit;
    }
    $result = $applicantlist->create($name, $school, array_unique($majors), $dorm, !empty($request["senior"]) ? 1 : 0);
    if ($result == false) {
        http_response_code(400);
        echo "<img src = 'https://http.cat/400' />";
    } else {
        http_response_code(200);
    }
} else {
    http_response_code(405);
}
