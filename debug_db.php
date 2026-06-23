<?php
/**
 * Debug Script - Check Database Tables and Connections
 * Access: http://localhost/zeveduacademy/debug_db.php
 */

echo "<h2>🔍 Database Debug</h2>";

// 1. Test connection
try {
    $db = \Config\Database::connect();
    echo "<p style='color: green;'>✓ Database Connected: " . $db->getDatabase() . "</p>";
} catch (\Exception $e) {
    echo "<p style='color: red;'>✗ Connection Error: " . $e->getMessage() . "</p>";
    exit;
}

// 2. List all tables
echo "<h3>📋 All Tables</h3>";
$tables = $db->listTables();
echo "<ul>";
foreach ($tables as $table) {
    $count = $db->table($table)->countAllResults();
    echo "<li><strong>{$table}</strong> ({$count} rows)</li>";
}
echo "</ul>";

// 3. Check specific tables
echo "<h3>🔎 Check Required Tables</h3>";

$requiredTables = ['kelas_admin', 'template_sertifikat', 'produk_pelatihan', 'kategori_produk', 'users'];

foreach ($requiredTables as $table) {
    $exists = in_array($table, $tables);
    if ($exists) {
        $count = $db->table($table)->countAllResults();
        echo "<p style='color: green;'>✓ {$table} exists ({$count} rows)</p>";
    } else {
        echo "<p style='color: red;'>✗ {$table} NOT FOUND!</p>";
    }
}

// 4. Check produk_pelatihan structure
echo "<h3>📊 produk_pelatihan Columns</h3>";
if (in_array('produk_pelatihan', $tables)) {
    $fields = $db->getFieldData('produk_pelatihan');
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th></tr>";
    foreach ($fields as $field) {
        echo "<tr><td>{$field->name}</td><td>{$field->type}</td></tr>";
    }
    echo "</table>";
}

// 5. Test query from SertifikatController::index()
echo "<h3>🧪 Test SertifikatController Query</h3>";
try {
    $builder = $db->table('produk_pelatihan');
    $builder->select('produk_pelatihan.*,
                      kategori_produk.nama_kategori,
                      template_sertifikat.id_template,
                      template_sertifikat.background_image');
    $builder->join('kategori_produk', 'kategori_produk.id_kategori = produk_pelatihan.id_kategori', 'left');
    $builder->join('template_sertifikat', 'template_sertifikat.id_produk = produk_pelatihan.id_produk', 'left');
    $builder->orderBy('produk_pelatihan.id_produk', 'DESC');

    $result = $builder->get()->getResultArray();
    echo "<p style='color: green;'>✓ Query Success! Found {$result->num_rows} classes</p>";

    foreach ($result as $row) {
        echo "<p>- {$row['judul']} (ID: {$row['id_produk']})</p>";
    }
} catch (\Exception $e) {
    echo "<p style='color: red;'>✗ Query Error: " . $e->getMessage() . "</p>";
}

// 6. Test kelas_admin query
echo "<h3>🧪 Test kelas_admin Query</h3>";
try {
    $result = $db->table('kelas_admin')
        ->select('kelas_admin.*, users.nama_lengkap')
        ->join('users', 'users.id_user = kelas_admin.id_user')
        ->get()
        ->getResultArray();

    echo "<p style='color: green;'>✓ Query Success! Found " . count($result) . " records</p>";

    foreach ($result as $row) {
        echo "<p>- Admin: {$row['nama_lengkap']} | Role: {$row['role']} | Produk ID: {$row['id_produk']}</p>";
    }
} catch (\Exception $e) {
    echo "<p style='color: red;'>✗ Query Error: " . $e->getMessage() . "</p>";
}

echo "<hr><p><a href='" . base_url('admin/sertifikat') . "'>Back to Admin</a></p>";