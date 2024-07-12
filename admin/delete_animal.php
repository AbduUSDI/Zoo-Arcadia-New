<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: ../login.php');
    exit;
}

require '../functions.php';

$db = new Database();
$conn = $db->connect();

$animalManager = new Animal($conn);

if (isset($_GET['id'])) {
    $animalId = $_GET['id'];
    $animalManager->delete($animalId);
}

header('Location: manage_animals.php');
exit;