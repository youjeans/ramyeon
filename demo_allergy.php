<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>라면 리스트</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        select {
            margin-bottom: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

// MySQL에 연결
$conn = new mysqli($servername, $username, $password, $database);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 'allergy' 드롭다운 리스트를 위한 쿼리
$allergyQuery = "SHOW COLUMNS FROM allergy";
$allergyResult = $conn->query($allergyQuery);

// 'allergy' 리스트를 담을 배열 초기화
$allergies = array();

if ($allergyResult->num_rows > 0) {
    while ($row = $allergyResult->fetch_assoc()) {
        if ($row['Field'] !== '라면번호') {
            $allergies[] = $row['Field'];
        }
    }
}

// 선택된 allergy에 따른 라면 정보를 가져오기 위한 기본 쿼리
$defaultQuery = "SELECT r.`라면이름`
                FROM ramyun r
                JOIN allergy a ON r.`라면 번호` = a.`라면번호`";

// 'allergy'에 따른 쿼리를 만들기 위한 변수 초기화
$whereClause = "";

// 선택된 allergy가 있다면 해당 allergy에 대한 조건 추가
if (isset($_GET['allergy'])) {
    $selectedAllergy = $_GET['allergy'];
    $whereClause = " WHERE a.`$selectedAllergy` = 1";
}

// 실제 쿼리 생성
$sql = $defaultQuery . $whereClause;

$result = $conn->query($sql);

// 결과 출력
if (!$result) {
    die("Query failed: " . $conn->error);
}

// 데이터를 담을 배열 초기화
$ramyunData = array();

if ($result->num_rows > 0) {
    $ramyunNumber = 1; // 라면 리스트 번호 초기화
    while ($row = $result->fetch_assoc()) {
        $ramyunData[] = array("라면번호" => $ramyunNumber, "라면이름" => $row["라면이름"]);
        $ramyunNumber++;
    }
} else {
    echo "0 results";
}

// 연결 종료
$conn->close();
?>

<h2>해당 알러지 유발 식품이 포함된 라면 리스트</h2>

<label for="allergy">알러지 유발 식품 선택:</label>
<select id="allergy" onchange="filterByAllergy()">
    <option value="">전체</option>
    <?php
    foreach ($allergies as $allergy) {
        echo "<option value=\"$allergy\">" . $allergy . "</option>";
    }
    ?>
</select>

<table id="ramenTable">
    <thead>
        <tr>
            <th>No.</th>
            <th>라면 이름</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($ramyunData as $row) {
            echo "<tr><td>" . $row["라면번호"] . "</td><td>" . $row["라면이름"] . "</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    function filterByAllergy() {
        var allergySelect = document.getElementById("allergy");
        var allergy = allergySelect.options[allergySelect.selectedIndex].value;
        window.location.href = 'demo_allergy.php?allergy=' + allergy;
    }
</script>

</body>
</html>
