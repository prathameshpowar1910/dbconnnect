<?php
$username = "";
$email = "";
$message = "Choose an operation to perform.";
// Database configuration
$host = "localhost"; // Change to your database host
$dbname = "newphp"; // Change to your database name
$usernameDB = "root"; // Change to your database username
$passwordDB = "root"; // Change to your database password
$databasetype = "mysql";

if (isset($_POST["dbname"])) {
    $databasetype = $_POST["dbname"];
}
echo $databasetype;

try {
    // Create a PDO instance
    $pdo = new PDO("$databasetype:host=$host;dbname=$dbname;charset=utf8", $usernameDB, $passwordDB);
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Databse Connectivity WP Prathamesh Powar</title>
<style>       
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,700;1,300&family=Poppins:wght@300;400;600&display=swap');

* {
    margin: 0;
    padding: 0;
    /* font-family: 'Noto Sans', sans-serif; */
    font-family: 'Poppins', sans-serif;
}

        .container {
            height: 100vh;
            display: flex;
            flex-wrap: wrap;
        }

        .quadrant {
            flex: 1;
            min-width: 50%;
            min-height: 50%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            overflow:hidden;
            position: relative;

        }

        .options {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            background-color: #fff;
            text-align: center;
            font-size: 1.5rem;
            display:flex;
            justify-content: center;
            gap: 10px;
            align-items: center;
        }

        .updateTablebtn {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            /* background-color: #fff; */
            text-align: center;
        }
        .updateTablebtn button {
            padding: 10px;
            cursor: pointer;
            border-radius:10px;
            font-size: 1.2rem;
        }
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            padding: 10px;
            box-sizing: border-box;
            background-color: #fff;
            text-align: left;
            font-size: 1.2rem;
        }
        .content input {
            width: 100%;
            padding: 6px;
            box-sizing: border-box;
            margin-bottom: 6px;
        }
        .quad1 {
            display: flex;
            /* flex-direction: column; */
            justify-content: center;
            align-items: center;
            text-align: center;
            gap:10px;
        }
        .quad1 h1{
            position: absolute;
            font-size: 2.5rem;
            top:15%;
        }
        .quad2,.quad4 {
            width: 300px; /* Fixed width for the div */
            height: 200px; /* Fixed height for the div */
            overflow: auto;
        }

        table {
            /* top: 0;
            left: 0; */
            width: 100%;
            padding: 10px;
            /* width: 100%; */
            border-collapse: collapse;
            /* margin-top:5px; */
            margin-right:20px
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-size: 1.5rem;
        }

        thead {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ddd;
        }
        .quadrant:nth-child(1) {
            background-color: #f2f2f2;
        }

        .quadrant:nth-child(2) {
            background-color: #e0e0e0;
        }

        .quadrant:nth-child(3) {
            background-color: #d0d0d0;
        }

        .quadrant:nth-child(4) {
            background-color: #c0c0c0;
        }

        #dbtype {
            display:none;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="quadrant quad1">
            <h1 >Database List</h1>
            <form action="pdo.php" method="post" >
            <input type="radio" name="dbname" id="mysql" value="mysql" >
            <label for="mysql">MySQL</label>
            <input type="radio" name="dbname" id="pgsql" value="pgsql" ">
            <label for="pgsql">PostGreSQL</label>
            <input type="radio" name="dbname" id="mariadb" value="mariadb" ">
            <label for="mariadb">MariaDB</label><br>
            <input type="submit" value="Choose this DB">
            </form>
        </div>
        <div class="quadrant quad2" id="output"></div>
        <div class="quadrant quad3">
            <div class="options" >
                <input type="radio" name="operation" id="update" value="update">
                <label for="update">Update</label>
                <input type="radio" name="operation" id="insert" value="insert">
                <label for="insert">Insert</label>
                <input type="radio" name="operation" id="delete" value="delete">
                <label for="delete">Delete</label>
                <div id="dbtype"></div>
            </div>
            <div class="content">
                <?php if (!empty($message)) : ?>
                <?php echo $message; ?>
                <?php endif; ?>
            </div>
            <div class="updateTablebtn">
                <button id="showUpdatedTable" >Show Updated Table</button>
            </div>
            
        </div>
        <div class="quadrant quad4">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
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
                </tbody>
            </table>
        </div>
    </div>
</body>
<script>
    const radioButtons = document.querySelectorAll('input[name="operation"]');
    const contentDiv = document.querySelector('.content');
    const radioButtonsDbname = document.querySelectorAll('input[name="dbname"]');
    var selectedDbname = "mysql";

    radioButtonsDbname.forEach(radioButtonDbname => {
        radioButtonDbname.addEventListener('change', function () {
            if (this.checked) {
                selectedDbname = this.value;
                dbtype.innerText = selectedDbname;
            }      
        });
    });

    const insertContent = `
                            <form action="pdo.php" method="post">
                                <label for="userid">User-Id</label>
                                <input type="text" name="userid" id="userid" placeholder="Enter Userid">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" placeholder="Enter Username">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Enter Email">
                                <input type="submit" value="Insert">
                                <input type="hidden" name="action" value="insert">
                                
                            </form>
                        `;
    const deleteContent = `
                            <form action="pdo.php" method="post">
                                <label for="userid">User-Id</label>
                                <input type="text" name="userid" id="userid" placeholder="Enter Userid">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" placeholder="Enter Username">
                                <input type="submit" value="Delete">
                                <input type="hidden" name="action" value="delete">
                            </form>
                        `;
    
    const updateContent = `
                            <form action="pdo.php" method="post">
                                <label for="userid">User-Id</label>
                                <input type="text" name="userid" id="userid" placeholder="Enter Userid">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" placeholder="Enter Username">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Enter Email">
                                <input type="submit" value="Update">
                                <input type="hidden" name="action" value="update">
                            </form>
                        `;
    
    radioButtons.forEach(radioButton => {
        radioButton.addEventListener('change', function () {
            if (this.checked) {
                const selectedValue = this.value;
                if (selectedValue === 'insert') {
                    contentDiv.innerHTML = insertContent ;
                } else if (selectedValue === 'delete' ) {
                    contentDiv.innerHTML = deleteContent ;
                } else if (selectedValue === 'update') {
                    contentDiv.innerHTML = updateContent ;
                }
            }
        });
    });

    showUpdatedTable.addEventListener("click", function () {
        fetch('refresh.php')
            .then(response => response.text())
            .then(data => {
                document.querySelector("table tbody").innerHTML = data;
            })
            .catch(error => console.error(error));
    });

    document.addEventListener('DOMContentLoaded', function () {
    const outputDiv = document.getElementById('output');

    fetch('pdo.php')
        .then(response => response.text())
        .then(data => {
            // Display the fetched HTML content in the output div
            outputDiv.textContent = data;
        })
        .catch(error => {
            outputDiv.textContent = 'Error fetching content: ' + error.message;
        });
});


</script>
</html>

