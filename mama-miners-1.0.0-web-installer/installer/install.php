<?php
session_start();
$db = $_SESSION['db'];

$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
if ($conn->connect_error) die("DB Connection Failed");

$sql = file_get_contents("../sql/database.sql");
$conn->multi_query($sql);

$config = "<?php\n$"."db_host='{$db['host']}';\n$"."db_name='{$db['name']}';\n$"."db_user='{$db['user']}';\n$"."db_pass='{$db['pass']}';\n";
file_put_contents("../config.php", $config);

echo "<h2>Installation Complete</h2>";
echo "<p>Delete the installer folder.</p>";
