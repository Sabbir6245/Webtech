<?php
session_start();
include "../model/DatabaseConnection.php";
include "../model/AdminModel.php";

$db = new DatabaseConnection();
$conn = $db->openConnection();

$model = new AdminModel();
$counts = $model->getDashboardCounts($conn);

$db->closeConnection($conn);

include "../view/dashboard.php";
