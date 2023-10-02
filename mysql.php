try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createTableSQL = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL
    )";
    $pdo->exec($createTableSQL);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check which form action is being performed (insert, update, or delete)
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "insert") {
                // Handle insertion
                $username = $_POST["username"];
                $email = $_POST["email"];
                $id = $_POST["userid"];
                $insertSQL="";
                // Perform the insertion into the "users" table
                if (empty($username) || empty($email)) {
                    $message = "Enter Username or Email to insert";
                } elseif(!empty($username) && !empty($email) && empty($id)) {
                    $insertSQL = "INSERT INTO users (username, email) VALUES (:username, :email)";
                } else {
                    $insertSQL = "INSERT INTO users (id, username, email) VALUES (:id, :username, :email)";
                }
                
                if (!empty($insertSQL)) {
                    $stmt = $pdo->prepare($insertSQL);
                    if (!empty($id)) {
                        $stmt->bindParam(':id', $id);
                    }
                    if (!empty($email)) {
                        $stmt->bindParam(':email', $email);
                    }
                    if (!empty($username)) {
                        $stmt->bindParam(':username', $username);
                    }
                    $stmt->execute();
                    $message = "Data inserted successfully!";
                }
                
            } elseif ($_POST["action"] == "update") {
                // Handle update
                $id = $_POST["userid"];
                $username = $_POST["username"];
                $email = $_POST["email"];
                $updateSQL="";

                if (!empty($id) && !empty($username) && !empty($email)) {
                    $updateSQL = "UPDATE users SET email = :email,username = :username WHERE id = :id";
                } elseif(!empty($id) && !empty($username) && empty($email)) {
                    $updateSQL = "UPDATE users SET username = :username WHERE id = :id";
                } elseif(!empty($id) && empty($username) && !empty($email)) {
                    $updateSQL = "UPDATE users SET email = :email WHERE id = :id";
                } elseif(!empty($id) && empty($username) && empty($email)) {
                    $message = "Enter Username or Email to update";
                } elseif(empty($id) && !empty($username) && !empty($email)) {
                    $updateSQL = "UPDATE users SET email = :email WHERE username = :username";
                } elseif(empty($id) && empty($username) && !empty($email)) {
                    $message = "Enter Username or ID to update";
                } else {
                    $message = "Enter Email to update";
                }
                // Perform the update in the "users" table
                if ($updateSQL !== "") {
                    $stmt = $pdo->prepare($updateSQL);
                    if (!empty($id)) {
                        $stmt->bindParam(':id', $id);
                    }
                    if (!empty($email)) {
                        $stmt->bindParam(':email', $email);
                    }
                    if (!empty($username)) {
                        $stmt->bindParam(':username', $username);
                    }
                    $stmt->execute();
                    $message = "Data updated successfully!";
                }
                

            } elseif ($_POST["action"] == "delete") {
                // Handle deletion
                $username = $_POST["username"];
                $id = $_POST["userid"];
                $deleteSQL="";
                // Perform the deletion from the "users" table
                if (!empty($username) && !empty($id) || empty($username) && !empty($id)) {
                    $deleteSQL = "DELETE FROM users WHERE id = :id";
                } elseif(!empty($username) && empty($id)) {
                    $deleteSQL = "DELETE FROM users WHERE username = :username";
                } else {
                    $message = "Enter Username or ID to delete";
                }

                if ($deleteSQL !== "") {
                    $stmt = $pdo->prepare($deleteSQL);
                    if (!empty($id)) {
                        $stmt->bindParam(':id', $id);
                    }
                    if (!empty($username) && empty($id)) {
                        $stmt->bindParam(':username', $username);
                    }
                    $stmt->execute();
                    $message = "Data deleted successfully!";
                }
                
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}