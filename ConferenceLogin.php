<?php
    session_start();
    $errors = 0;
    $body = "";
    $hostName = "localhost";
    $userName = "adminer";
    $password = "three-teach-20";
    $DBName = "conference";
    $tableName = "assigned_seminars";
    $_SESSION['pass'] = "yes";
    $DBConnect = mysqli_connect($hostName, $userName, $password);
    if (!isset($_POST['name'])) {
        $body .= "<p>Please return to the login page and retry</p>";
        $errors++;
    } else {
        $email = $_POST['email'];
    }
    if (!$DBConnect) {
        $body .= "<p>Could not connect\n</p>";
        $errors++;
    } else {
        if (mysqli_select_db($DBConnect, $DBName)) {
            $sql = "SHOW TABLES LIKE '$tableName'"; //variable to store mySQL commands
            $result = mysqli_query($DBConnect, $sql);
            if (mysqli_num_rows($result) == 0) {
                $body .= "<p>Could select database.</p>";
            } else {
                $body .= "<p>Could not select database</p>";
            }
        }
        
    }
    if ($DBConnect) {
        mysqli_close($DBConnect);
    }
?>
    <!doctype html>

    <html>

    <head>
        <!--
   project_02_09_05
   filename: ConferenceLogin.php
   author: Nathan Howard
   date: 11.30.19
-->
        <title>Conference Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <script src="modernizr.custom.65897.js"></script>
    </head>

    <body>
        <form action="RegisterProcess.php?PHPSESSID=<?php echo session_id(); ?>" method="post">
            <h1>Register</h1>
            <p>Name:
                <input type="text" name="name">
            </p>
            <p>Age:
                <input type="number" name="age">
            </p>
            <p>Email:
                <input type="email" name="email">
            </p>
            <p>Phone Number:
                <input type="tel" name="telephone">
            </p>
            <p>Company Name:
                <input type="text" name="companyname">
            </p>
            <p>
                <input type="submit" name="submit">
                <input type="reset">
            </p>
        </form>
        <form action="LoginProcess.php?PHPSESSID=<?php echo session_id(); ?>" method="post">
            <h1>Login</h1>
            <p>Email:
                <input type="text" name="loginEmail">
            </p>
            <p>Company Name:
                <input type="text" name="loginCompanyName">
            </p>
            <p>
                <input type="submit" name="login">
                <input type="reset">
            </p>
        </form>
    </body>

    </html>
