<?php
class AdminModel {

    

    function getAllUsers($connection) {
        $sql = "SELECT id, email, role, status FROM users";
        return $connection->query($sql);
    }

     function getDashboardCounts($conn) {

        $data = [];

        $sqlTotal = "SELECT COUNT(*) AS total FROM users";
        $sqlActive = "SELECT COUNT(*) AS active FROM users WHERE status='active'";
        $sqlBlocked = "SELECT COUNT(*) AS blocked FROM users WHERE status='blocked'";

        $data['total']   = $conn->query($sqlTotal)->fetch_assoc()['total'];
        $data['active']  = $conn->query($sqlActive)->fetch_assoc()['active'];
        $data['blocked'] = $conn->query($sqlBlocked)->fetch_assoc()['blocked'];

        return $data;
    }
}
