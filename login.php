<?php

include('db_con.php');

session_start();

$message = '';


if (isset($_SESSION['user_id'])) {
    header("location:index.php");
}




if (isset($_POST['login'])) {
    $query = "
    SELECT * FROM login
    WHERE username = :username
    ";

    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':username' => $_POST["username"]
        )
    );

    $count = $statement->rowCount();
    if ($count > 0) {
        $result = $statement->fetchAll();

        foreach ($result as $row) {
            if (password_verify($_POST["password"], $row["password"])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $sub_query = "
                INSERT INTO login_details(user_id)
                VALUES ('" .$row['user_id']. "')
                ";
                $statement = $connect->prepare($sub_query);
                $statement->execute();
                $_SESSION['login_details_id'] = $connect->lastInsertId();
                header('location:index.php');
            } else {
                $message = '<label>Wrong Password</label>';
            }
        }
    } else {
        $message = '<label>Wrong Username</label>';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Responsive side menu</title>
    <link rel="stylesheet" href="./login.css">
</head>

<body>
    <div class="head">

        <div id="main">
            <div class="message">
                <?php echo $message; ?>
                <?php echo password_hash('123456',PASSWORD_DEFAULT); ?>
            </div>
            <div class="con">
                <div class="signup">
                    <h2 id="sp">Login</h2>
                    <hr />
                    <br />
                    <form method="post">
                        <label>Userame : <br /><input type="text" class="bdr" name="username" placeholder="username" required /></label><br />
                        <label>Password : <br /><input type="password" class="bdr" name="password" placeholder="password" required /></label><br />
                        <input type="submit" name="login" value="Login" id="btn">
                    </form>
                </div>
            </div>
        </div>
        <!-- 
        <div id="main">
            <div class="con">
                <div class="signup">
                    <h2 id="sp">SignUp</h2>
                    <hr />
                    <br />
                    <form action="registration.php" method="post">
                        <label>Userame : <br /><input type="text" class="bdr" name="username" placeholder="username" required /></label><br />
                        <label>Email : <br /><input type="email" class="bdr" name="email" placeholder="email" required /></label><br />
                        <label>Password : <br /><input type="password" class="bdr" name="password" placeholder="password" required /></label><br />
                        <button type="submit" id="btn">SignUp</button>
                    </form>
                </div>
            </div>
        </div>-->
    </div>
</body>

</html>