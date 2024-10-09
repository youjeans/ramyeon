<?php

// MySQL 서버 연결 정보
$servername = "localhost";  // MySQL 서버 주소
$username = "root";     // MySQL 사용자 이름
$password = "";       // MySQL 비밀번호
$dbname = "project";  // 사용할 데이터베이스 이름

// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자가 체크한 알레르기
$checkedAllergies = [];
if (isset($_POST['allergies'])) {
    $checkedAllergies = $_POST['allergies'];
}

// 라면번호
$ramenNumber = '라면번호';

// 초기화
$query = "SELECT r.*, n.*, a.* FROM ramyun r 
          INNER JOIN nutrition n ON r.`라면 번호` = n.`라면 번호`
          LEFT JOIN allergy a ON r.`라면 번호` = a.`라면번호`
          WHERE ";

// 종류별 체크 여부에 따른 쿼리 조건
$selected_types = [];
if (isset($_POST['red'])) {
    $selected_types[] = "국물 (빨간)";
}
if (isset($_POST['other'])) {
    $selected_types[] = "국물 (그 외)";
}
if (isset($_POST['bibim'])) {
    $selected_types[] = "볶음면 (비빔)";
}
if (isset($_POST['jjajang'])) {
    $selected_types[] = "볶음면 (짜장)";
}
// 종류가 선택된 경우에만 추가
if (!empty($selected_types)) {
    $query .= " (";
    foreach ($selected_types as $type) {
        $query .= "r.`종류` = '$type' OR ";
    }
    $query = rtrim($query, ' OR '); // 마지막의 OR를 제거
    $query .= ") AND ";
}

// 건면, 컵라면 체크 여부 확인
$is_dry_checked = isset($_POST['dry']) && $_POST['dry'] == 1;
$is_cup_checked = isset($_POST['cup']) && $_POST['cup'] == 1;
// 건면, 컵라면 체크 여부에 따른 쿼리
$query .= ($is_dry_checked ? "r.`건면 여부` = 1" : "1") . " AND ";
$query .= ($is_cup_checked ? "r.`컵라면 여부` = 1" : "1") . " AND ";

// 칼로리 범위 확인
if (isset($_POST['calories'])) {
    $calories_range = $_POST['calories'];
    $query .= "n.`칼로리` <= $calories_range";
}

// 마지막에 남는 AND를 제거
$query = rtrim($query, ' AND ');

// 쿼리 실행
$result = $conn->query($query);

// 결과 출력
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 해당 라면의 알레르기 값들을 가져오기
        $ramenAllergies = [];
        foreach ($checkedAllergies as $allergy) {
            $ramenAllergies[$allergy] = $row[$allergy];
        }

        // 알레르기 값이 1인 경우 해당 라면을 출력에서 제외
        if (in_array(1, $ramenAllergies)) {
            continue;
        }

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
