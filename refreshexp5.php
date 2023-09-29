<?php
$host = "localhost"; // Change to your database host
$dbname = "exp5DB"; // Change to your database name
$usernameDB = "root"; // Change to your database username
$passwordDB = "root"; // Change to your database password

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usernameDB, $passwordDB);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $selectSQL = "SELECT * FROM users";
    $stmt = $pdo->prepare($selectSQL);
    $stmt->execute();
    $users = $stmt->fetchAll();
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user["id"] . "</td>";
        echo "<td>" . $user["username"] . "</td>";
        echo "<td>" . $user["email"] . "</td>";
        echo "</tr>";
    }
?>