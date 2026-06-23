<?php

/**
 * Database Connection Test
 * Access: http://localhost/zeveduacademy/dbtest
 */

// Load CodeIgniter
require_once __DIR__ . '/../vendor/autoload.php';

$dbConfig = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'db_zevedu',
    'DBDriver' => 'MySQLi'
];

echo "<h2>🧪 Database Connection Test</h2>";

// Test connection
$mysqli = @new mysqli(
    $dbConfig['hostname'],
    $dbConfig['username'],
    $dbConfig['password'],
    $dbConfig['database']
);

if ($mysqli->connect_error) {
    echo "<div style='color: red; padding: 20px; border: 1px solid red; margin: 20px 0;'>";
    echo "<h3>❌ Connection Failed!</h3>";
    echo "<p>Error: " . $mysqli->connect_error . "</p>";
    echo "<p>Port: " . $mysqli->connect_errno . "</p>";
    echo "<h4>Solutions:</h4>";
    echo "<ol>";
    echo "<li>Pastikan XAMPP MySQL sudah START (berwarna hijau)</li>";
    echo "<li>Cek apakah database <code>db_zevedu</code> sudah ada</li>";
    echo "<li>Import file <code>db_zevedu.sql</code> ke phpMyAdmin</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div style='color: green; padding: 20px; border: 1px solid green; margin: 20px 0;'>";
    echo "<h3>✅ Connection Successful!</h3>";
    echo "<p>Database: " . $mysqli->host_info . "</p>";
    echo "</div>";

    // List tables
    $result = $mysqli->query("SHOW TABLES");
    echo "<h3>📋 Database Tables (Total: " . $result->num_rows . ")</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>#</th><th>Table Name</th><th>Records</th></tr>";
    $no = 1;
    while ($row = $result->fetch_row()) {
        $countResult = $mysqli->query("SELECT COUNT(*) FROM `" . $row[0] . "`");
        $count = $countResult->fetch_row()[0];
        echo "<tr><td>{$no}</td><td>{$row[0]}</td><td>{$count}</td></tr>";
        $no++;
    }
    echo "</table>";
}

$mysqli->close();