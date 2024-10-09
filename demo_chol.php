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

// SELECT 쿼리 실행
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc';  // Get the sort order from the query parameter
$sql = "SELECT r.`라면이름`, n.`콜레스테롤`
        FROM ramyun r
        JOIN nutrition n ON r.`라면 번호` = n.`라면 번호`
        ORDER BY n.`콜레스테롤` $sortOrder";

$result = $conn->query($sql);

// 결과 출력
if (!$result) {
    die("Query failed: " . $conn->error);
}

// 데이터를 담을 배열 초기화
$ramyunData = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ramyunData[] = $row;
    }
} else {
    echo "0 results";
}

// 연결 종료
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>라면 콜레스테롤 정렬</title>
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
        /* Adjust the width of the first column (라면 이름) */
        td:first-child,
        td:last-child {
            width: 200px; /* Adjusted the width */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>

<h2>라면 콜레스테롤 정렬</h2>

<label for="sort">정렬 순서:</label>
<select id="sort" onchange="sortInstantNoodles()">
    <option value="asc" <?php echo $sortOrder === 'asc' ? 'selected' : ''; ?>>오름차순</option>
    <option value="desc" <?php echo $sortOrder === 'desc' ? 'selected' : ''; ?>>내림차순</option>
</select>

<table id="ramenTable">
    <thead>
        <tr>
            <th>라면 이름</th>
            <th>콜레스테롤(mg)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($ramyunData as $row) {
            $ramyun_name = isset($row["라면이름"]) ? $row["라면이름"] : "Undefined";
            $chol = isset($row["콜레스테롤"]) ? $row["콜레스테롤"] : "Undefined";
            echo "<tr><td>" . $ramyun_name . "</td><td>" . $chol . "</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    function sortInstantNoodles() {
        var sortSelect = document.getElementById("sort");
        var sortOrder = sortSelect.options[sortSelect.selectedIndex].value;
        window.location.href = 'demo_chol.php?sort=' + sortOrder;
    }
</script>

</body>
</html>
