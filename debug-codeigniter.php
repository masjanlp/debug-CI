<?php
// debug-codeigniter.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>CodeIgniter Debugging Tool</h2><hr>";

// 1. PHP Version
echo "<h3>1. PHP Version</h3>";
echo "<pre>" . phpversion() . "</pre>";

// 2. Cek Ekstensi yang dibutuhkan
echo "<h3>2. PHP Extensions</h3>";
$extensions = ['intl', 'mbstring', 'json', 'curl', 'openssl', 'pdo', 'mysqli'];
foreach ($extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "<span style='color:green;'>Enabled</span>" : "<span style='color:red;'>Missing</span>") . "<br>";
}

// 3. Baca config/app.php
echo "<h3>3. BaseURL dari app/Config/App.php</h3>";
$appConfigPath = __DIR__ . '/app/Config/App.php';
if (file_exists($appConfigPath)) {
    $contents = file_get_contents($appConfigPath);
    preg_match("/public\s+\\\$baseURL\s+=\s+'(.*?)';/", $contents, $matches);
    echo isset($matches[1]) ? "<pre>{$matches[1]}</pre>" : "<span style='color:orange;'>Tidak ditemukan</span>";
} else {
    echo "<span style='color:red;'>File App.php tidak ditemukan</span>";
}

// 4. Coba koneksi database dari app/Config/Database.php
echo "<h3>4. Database Connection (Default)</h3>";
$dbConfigPath = __DIR__ . '/app/Config/Database.php';
if (file_exists($dbConfigPath)) {
    include_once $dbConfigPath;
    $config = new \Config\Database();
    $db = $config->default;

    try {
        $pdo = new PDO("mysql:host={$db['hostname']};dbname={$db['database']}", $db['username'], $db['password']);
        echo "<span style='color:green;'>Berhasil terkoneksi ke database '{$db['database']}'</span>";
    } catch (PDOException $e) {
        echo "<span style='color:red;'>Gagal konek DB:</span> " . $e->getMessage();
    }
} else {
    echo "<span style='color:red;'>File Database.php tidak ditemukan</span>";
}

// 5. Cek permission folder writable/
echo "<h3>5. Folder writable/ Permission</h3>";
$writablePath = __DIR__ . '/writable';
if (is_writable($writablePath)) {
    echo "writable/: <span style='color:green;'>Writable</span>";
} else {
    echo "writable/: <span style='color:red;'>Not Writable</span>";
}

// 6. Tampilkan 20 baris terakhir dari writable/logs/log-*.php
echo "<h3>6. Isi Log Terbaru (jika ada)</h3>";
$logFiles = glob(__DIR__ . '/writable/logs/log-*.php');
if (!empty($logFiles)) {
    $latestLog = end($logFiles);
    echo "<strong>File:</strong> " . basename($latestLog) . "<br>";
    echo "<pre>" . tailCustom($latestLog, 20) . "</pre>";
} else {
    echo "<span style='color:gray;'>Tidak ada file log ditemukan</span>";
}

// Utilitas: ambil baris terakhir log
function tailCustom($filepath, $lines = 20) {
    $f = @fopen($filepath, "rb");
    if ($f === false) return false;
    fseek($f, -1, SEEK_END);
    $buffer = '';
    $char = '';
    $lineCount = 0;

    while (ftell($f) > 0 && $lineCount < $lines) {
        $char = fgetc($f);
        $buffer = $char . $buffer;
        fseek($f, -2, SEEK_CUR);
        if ($char === "\n") $lineCount++;
    }

    fclose($f);
    return $buffer;
}
?>
