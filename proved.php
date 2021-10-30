<?php
define('USER', "root");
define('PASSWORD', "");
define('SERVER', "localhost");
define('DB', "test_billet");

try {
    $db = new PDO("mysql:host=" . SERVER . ";dbname=" . DB, USER, PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
$code_barres = $_POST['code_barres'];
$messages = '';
$statement = $db->prepare("INSERT INTO proved_order (code_barres)
VALUES (
       :code_barres
       )");
$answer = $statement->execute([
    'code_barres' => $code_barres
]);

if ($answer) {
    $message = 'order successfully aproved';
} else {
    $message = 'event cancelled';
}
echo $message;
