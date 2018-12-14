<?php
    session_start();
    $errors = 0;
    $body = "";
    $hostname = "localhost";
    $username = "adminer";
    $passwd = "three-teach-20";
    $DBConnect = false;
    $DBName = "conference";
    $tableName = "attendee";
    if (!isset($_SESSION['attendeeID'])) {
        $body .= "<p>Please return to the login page and retry</p>";
        $errors++;
    }
    if ($errors == 0) {
        $DBConnect = mysqli_connect($hostname, $username, $passwd);
//            see if it is NOT $DBConnect
        if (!$DBConnect) {
            ++$errors;
            $body .= "<p>Unable to connect to the database server, error code: " . mysqli_connect_error() . ".</p>\n";
        } else {
            $result = mysqli_select_db($DBConnect, $DBName);
            if (!$result) {
                $errors++;
                $body .= "<p>Unable to select the database, \"$DBName\", error: " . mysqli_error($DBConnect) . ".</p>\n";
            }
        }
    }
    if ($errors == 0) {
        $SQLstring = "SELECT name, email, phone, companyname FROM $tableName" . " WHERE attendeeID='" . $_SESSION['attendeeID'] . "'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            $errors++;
            $body .= "Could not execute query error: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect);
        } else {
            if (mysqli_num_rows($queryResult) > 0) {
                $row = mysqli_fetch_assoc($queryResult);
                $body .= "<form action='ChangeProcess.php?PHPSESSID=" . session_id() .  "' method='post'><h1>Register</h1><p>Name:<input type='text' name='name' value='" . $row['name'] . "'></p><p>Email:<input type='email' name='email' value='" . $row['email'] . "'></p><p>Phone Number:<input type='tel' name='telephone' value='" . $row['phone'] . "'></p><p>Company Name:<input type='text' name='companyname' value='" . $row['companyname'] . "'></p><p><input type='submit' name='save'><input type='reset'></p></form>";
            } else {
                $body .= "Please return to the login page and retry";
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
    <title>Profile Changer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
</head>

<body>
    <?php
        echo $body;
    ?>
</body>

</html>
