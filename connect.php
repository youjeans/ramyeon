<?php
// MySQL 서버 연결 정보
$servername = "localhost:3306";  // MySQL 서버 주소
$username = "root";     // MySQL 사용자 이름
$password = "root";       // MySQL 비밀번호
$dbname = "project";  // 사용할 데이터베이스 이름

// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

?>
