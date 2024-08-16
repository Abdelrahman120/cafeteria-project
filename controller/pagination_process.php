<?php

$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$sql = 'SELECT * FROM Orders LIMIT :offset, :itemsPerPage';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT COUNT(*) FROM Orders';
$stmt = $pdo->query($sql);
$totalRecords = $stmt->fetchColumn();

$totalPages = ceil($totalRecords / $itemsPerPage);

foreach ($results as $row) {
    echo $row['column_name'] . '<br>';
}

for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $page) {
        echo "<strong>$i</strong> ";
    } else {
        echo "<a href=\"?page=$i\">$i</a> ";
    }
}