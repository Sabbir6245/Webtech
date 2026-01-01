<?php
session_start();
include "../model/DatabaseConnection.php";
include "../model/AdminModel.php";

$db = new DatabaseConnection();
$conn = $db->openConnection();

$model = new AdminModel();
$users = $model->getAllUsers($conn);
