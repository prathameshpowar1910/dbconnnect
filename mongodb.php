<?php
$username = "";
$email = "";
$message = "Choose an operation to perform.";
// Database configuration
$host = "localhost"; // Change to your database host
// $dbname = "newphp"; // Change to your database name
$usernameDB = "root"; // Change to your database username
$passwordDB = "root"; // Change to your database password

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
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
</style>
<body>
    <div class="container">
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

    const insertContent = `
                            <form action="exp5.php" method="post">
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
                            <form action="exp5.php" method="post">
                                <label for="userid">User-Id</label>
                                <input type="text" name="userid" id="userid" placeholder="Enter Userid">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" placeholder="Enter Username">
                                <input type="submit" value="Delete">
                                <input type="hidden" name="action" value="delete">
                            </form>
                        `;
    
    const updateContent = `
                            <form action="exp5.php" method="post">
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
        fetch('refreshexp5.php')
            .then(response => response.text())
            .then(data => {
                document.querySelector("table tbody").innerHTML = data;
            })
            .catch(error => console.error(error));
    });

</script>
</html>
