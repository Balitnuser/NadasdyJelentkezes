<?php
include_once("applicant.php");
include_once("db/conn.php");
class Applicantlist
{
    public $conn;
    public function __construct()
    {
        $this->conn = $GLOBALS["conn"];
    }
    public function getAll()
    {
        $sql = "SELECT * FROM jelentkezok ORDER BY Id DESC;";
        $result = $this->conn->query($sql);
        $applicants = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $applicants[] = new Applicant((int)$row["Id"], $row["Full_name"], $row["CurrentSchool"], explode(",", $row["Major_Name"]), (int)$row["Dorm"], (int)$row["isSenior"]);
            }
        }
        return $applicants;
    }
    public function getStats()
    {
        $sql = "SELECT COUNT(*) AS total, SUM(isSenior = 1) AS senior, SUM(Dorm = 1) AS dorm, SUM(Dorm = 0) AS commuter, SUM(Dorm = 2) AS unsure, SUM(Dorm = 1 AND isSenior = 1) AS dorm_senior FROM jelentkezok;";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return array(
            "total" => (int)$row["total"],
            "senior" => (int)$row["senior"],
            "dorm" => (int)$row["dorm"],
            "commuter" => (int)$row["commuter"],
            "unsure" => (int)$row["unsure"],
            "dorm_senior" => (int)$row["dorm_senior"]
        );
    }
    public function getMajorStats($majorNames)
    {
        $majors = array();
        foreach ($majorNames as $major) {
            $sql = "SELECT COUNT(*) AS total, SUM(isSenior = 1) AS senior, SUM(Dorm = 1) AS dorm, SUM(Dorm = 1 AND isSenior = 1) AS dorm_senior FROM jelentkezok WHERE FIND_IN_SET('".$this->conn->real_escape_string($major)."', Major_Name) > 0;";
            $result = $this->conn->query($sql);
            $row = $result->fetch_assoc();
            $majors[] = array(
                "major" => $major,
                "total" => (int)$row["total"],
                "senior" => (int)$row["senior"],
                "dorm" => (int)$row["dorm"],
                "dorm_senior" => (int)$row["dorm_senior"]
            );
        }
        usort($majors, function ($a, $b) {
            return $b["total"] - $a["total"];
        });
        return $majors;
    }
    public function delete($id)
    {
        $sql = "DELETE FROM jelentkezok WHERE Id = " . (int)$id . ";";
        $result = $this->conn->query($sql);
        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    public function create($name, $school, $majors, $dorm, $senior)
    {
        $name = $this->conn->real_escape_string($name);
        $school = $this->conn->real_escape_string($school);
        $major = $this->conn->real_escape_string(implode(",", $majors));
        $sql = "INSERT INTO jelentkezok(Full_name, CurrentSchool, Major_Name, Dorm, isSenior) VALUES ('".$name."','".$school."','".$major."',".(int)$dorm.",".(int)$senior.")";
        $result = $this->conn->query($sql);
        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
