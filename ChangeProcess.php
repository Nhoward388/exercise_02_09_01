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
    if (!isset($_POST['save'])) {
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
        $SQLstring = "UPDATE $tableName SET name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', phone='" . $_POST['telephone'] . "', companyname='" . $_POST['companyname'] . "' WHERE attendeeID='" . $_SESSION['attendeeID'] . "'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            $errors++;
            $body .= "Query failed, error code:" . mysqli_errno($DBConnect) . ": " . mysqli_error() . ".";
        } else {
            $body .= "<p>Changes saved</p>";
        }
    }
    if ($DBConnect) {
        mysqli_close($DBConnect);
    }
?>
<!doctype html>

<html>

<head>
    <title>Page Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
</head>

<body>
    <?php
        echo $body;
    ?>
</body>

</html>
