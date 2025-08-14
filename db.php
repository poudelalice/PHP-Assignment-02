<?php
$host = '172.31.22.43';
$user = 'Alice200603293';
$password = '6sFTn5wY8v';
$db = 'Alice200603293';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>