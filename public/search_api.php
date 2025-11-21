<?php
require_once __DIR__ . '/../config/database.php';

$q = $_GET['q'] ?? '';

if ($q === '') {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT id, title, author, cover FROM books 
                        WHERE title LIKE ? OR author LIKE ? LIMIT 8");
$search = "%" . $q . "%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$res = $stmt->get_result();

$books = [];
while ($row = $res->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
