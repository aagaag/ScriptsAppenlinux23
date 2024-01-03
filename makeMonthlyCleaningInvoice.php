<?php
$host = '10.10.20.11';
$db   = 'appenzell';
$user = 'aag';
$pass = 'scrap10';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    echo "Connecting to the database...\n";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connection successful.\n";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function saveTableAsHTML($pdo, $query, $title, $directory, $filename) {
    echo "Running query: $query\n";
    $html = "<html><head><title>$title</title></head><body>";
    $html .= "<h2>$title</h2>";
    $html .= "<table border='1' style='width:100%; border-collapse: collapse;'>";

    $firstRow = true;
    foreach ($pdo->query($query) as $row) {
        if ($firstRow) {
            $html .= "<thead><tr>";
            foreach ($row as $key => $value) {
                $html .= "<th>" . htmlspecialchars($key) . "</th>";
            }
            $html .= "</tr></thead><tbody>";
            $firstRow = false;
        }
        $html .= "<tr>";
        foreach ($row as $value) {
            $html .= "<td>" . htmlspecialchars($value) . "</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</tbody></table>";
    $html .= "<p>Scripts related to these emails are in: OneDrive\\Appenzell\\AppenNetwork\\ScriptsAppenlinux23</p>";
    $html .= "</body></html>";

    if (!file_exists($directory) && !is_dir($directory)) {
        echo "Creating directory: $directory\n";
        mkdir($directory, 0755, true);
    }

    echo "Saving file: " . $directory . '/' . $filename . "\n";
    file_put_contents($directory . '/' . $filename, $html);
    echo "File saved successfully.\n";
}

$directory = __DIR__ . '/abrechnungen';
$timestamp = date('Y-m-d-Hi');

$filename1 = "last_month_each_service_compact_$timestamp.html";
$filename2 = "last_month_salary_$timestamp.html";

$query1 = "SELECT * FROM qry_last_month_each_service_compact";
$query2 = "SELECT * FROM qry_lastmonthsalary";

echo "Saving tables as HTML files...\n";
saveTableAsHTML($pdo, $query1, "Last Month Each Service Compact", $directory, $filename1);
saveTableAsHTML($pdo, $query2, "Last Month Salary", $directory, $filename2);

echo "All operations completed.\n";
?>
