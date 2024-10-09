<?php

// MySQL 서버 연결 정보
$servername = "localhost";  // MySQL 서버 주소
$username = "root";         // MySQL 사용자 이름
$password = "";             // MySQL 비밀번호
$dbname = "project";        // 사용할 데이터베이스 이름

// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST로 전송된 검색어를 받아옴
$search_keyword = isset($_POST['ramyunName']) ? $_POST['ramyunName'] : '';

// 검색 쿼리 작성 (ramyun 테이블과 nutrition 테이블 JOIN)
$query = "SELECT r.*, n.* FROM ramyun r 
          INNER JOIN nutrition n ON r.`라면 번호` = n.`라면 번호`
          WHERE r.`라면이름` LIKE '%$search_keyword%'";

// 쿼리 실행
$result = $conn->query($query);

// 결과 출력
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 여기에서 결과를 처리하는 코드를 작성
        echo "<h3><strong>" . $row['라면이름'] . "</strong></h3>";
        echo "종류: " . $row['종류'] . "<br>";
        echo "생산 회사: " . $row['생산 회사'] . "<br>";
        echo "컵라면: " . $row['컵라면 여부'] . "<br>";
        echo "건면: " . $row['건면 여부'] . "<br>";
        // nutrition 테이블의 속성 출력
        echo "&emsp;<영양성분>" . "<br>";
        echo "&emsp;칼로리: " . $row['칼로리'] . "<br>";
        echo "&emsp;탄수화물: " . $row['탄수화물'] . "<br>";
        echo "&emsp;단백질: " . $row['단백질'] . "<br>";
        echo "&emsp;지방: " . $row['지방'] . "<br>";
        echo "&emsp;콜레스테롤: " . $row['콜레스테롤'] . "<br>";
        echo "&emsp;나트륨: " . $row['나트륨'] . "<br>";

        echo "<hr>"; // 각 라면 결과 사이에 구분선 추가
    }
} else {
    echo "해당하는 라면이 없습니다.";
}

// 연결 종료
$conn->close();

?>
