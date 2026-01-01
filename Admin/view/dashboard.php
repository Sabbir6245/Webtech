<?php 
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION["email"] ?? "";

// DB connection
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "ticket_management";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch users
$userResult = $conn->query("SELECT id, username, email, role, status, created_at FROM users");
$users = [];
$counts = ['total'=>0, 'active'=>0, 'blocked'=>0];
if ($userResult->num_rows > 0) {
    while ($row = $userResult->fetch_assoc()) {
        $users[] = $row;
        $counts['total']++;
        if (($row['status'] ?? 'active') == 'active') {
            $counts['active']++;
        } else {
            $counts['blocked']++;
        }
    }
}

// Fetch events
$eventResult = $conn->query("
    SELECT e.id, e.title, e.event_date, e.venue, u.username AS organiser
    FROM events e
    JOIN users u ON e.organiser_id = u.id
");
$events = [];
if ($eventResult->num_rows > 0) {
    while ($row = $eventResult->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background-color: #f5f5f5; }
        .box { width: 90%; max-width: 1000px; margin: 30px auto; padding: 20px; background: white; border: 1px solid #ccc; }
        h2, h3 { text-align: center; }
        a.logout { display: inline-block; margin-bottom: 20px; text-decoration: none; color: white; background-color: #444; padding: 8px 15px; }
        a.logout:hover { background-color: #000; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #333; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="box">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo $email; ?></p>
    <a class="logout" href="../Controller/logout.php">Logout</a>

    <h3>All Users</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Registered At</th>
        </tr>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
            <td><?= $user['status'] ?? 'active' ?></td>
            <td><?= $user['created_at'] ?? '-' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>All Events</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Organizer</th>
            <th>Date</th>
            <th>Venue</th>
        </tr>
        <?php foreach($events as $event): ?>
        <tr>
            <td><?= $event['id'] ?></td>
            <td><?= $event['title'] ?></td>
            <td><?= $event['organiser'] ?></td>
            <td><?= $event['event_date'] ?></td>
            <td><?= $event['venue'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>User Summary</h3>
    <table>
        <tr>
            <th>Total Users</th>
            <th>Active Users</th>
            <th>Blocked Users</th>
        </tr>
        <tr>
            <td><?= $counts['total'] ?></td>
            <td><?= $counts['active'] ?></td>
            <td><?= $counts['blocked'] ?></td>
        </tr>
    </table>
</div>

</body>
</html>
