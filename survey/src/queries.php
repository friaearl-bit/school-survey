<?php

require_once __DIR__ . '/database.php';

$db = new database();
$pdo = $db->connect();

// Load queries
$queriesPath = __DIR__ . '/queries.json';
if (!file_exists($queriesPath)) {
    die('queries.json not found.');
}

$queries = json_decode(file_get_contents($queriesPath), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Invalid queries.json: ' . json_last_error_msg());
}

// Do not work on wasmer
// "instructor_leaderboard": "SELECT RANK() OVER (ORDER BY `rating` DESC) AS rank, t.instructor, t.subject, ROUND(AVG(a.value), 2) AS rating, COUNT(DISTINCT r.response_id) AS responses FROM target t LEFT JOIN response r ON r.target_id = t.target_id LEFT JOIN answer a ON a.response_id = r.response_id GROUP BY t.instructor, t.subject;",

// Execute and store results
$results = [];
foreach ($queries as $key => $query) {
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle single-row results
    if (count($data) === 1 && isset($data[0][$key])) {
        $results[$key] = $data[0][$key];
    }
    // Handle multi-row results
    else {
        $results[$key] = $data;
    }
}


// return [$queries, $results];
// return $results;

?>