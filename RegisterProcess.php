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
    if (!isset($_POST['name'])) {
        $errors++;
    } else {
        $email = $_POST['email'];
    }
    //        only connect if theres no errors
    if ($errors == 0) {
        $DBConnect = mysqli_connect($hostname, $username, $passwd);
//            see if it is NOT $DBConnect
        if (!$DBConnect) {
            ++$errors;
            $body .= "<p>Unable to connect to the database server, error code: " . mysqli_connect_error($DBConnect) . ".</p>\n";
        } else {
            $result = mysqli_select_db($DBConnect, $DBName);
            if (!$result) {
                $errors++;
                $body .= "<p>Unable to select to database, \"$DBName\", error: " . mysqli_error($DBConnect) . ".</p>\n";
            }
        }
    }
    if ($errors == 0) {
        $SQLstring = "SELECT count(*) FROM $tableName WHERE email='$email'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if ($queryResult) {
            $row = mysqli_fetch_row($queryResult);
            if ($row[0] > 0) {
                $errors++;
                $body .= "<p>The email address entered (" . htmlentities($email) . ") is already registered.</p>\n";
            }
        }
    }
    if ($errors == 0) {
        $name = stripslashes($_POST['name']);
        $name = trim($name);
        $phone = $_POST['telephone'];
        $companyName = $_POST['companyname'];
        $SQLstring = "INSERT INTO $tableName" . " (name, email, phone, companyname)" . " VALUES('$name', '$email', '$phone', '$companyName')";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            $errors++;
            $body .= "<p>Unable to save your registration information, error code: " . mysqli_error($DBConnect) . "</p>\n";
        } else {
//            $internID = mysqli_insert_id($DBConnect);
            $_SESSION['attendeeID'] = mysqli_insert_id($DBConnect);
        }
    }
    if ($errors == 0) {
        $SQLstring = "SELECT attendeeID from $tableName WHERE email='$email'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            $body .= "Could not execute query, bad syntax.";
        } else {
            $resultString = mysqli_fetch_assoc($queryResult);
            $_SESSION['attendeeID'] = $resultString['attendeeID'];
            mysqli_free_result($queryResult);
            $body .= "<p>Welcome $name!<br>Please select a Seminar from the <a href='SeminarSelection.php?PHPSESSID=" . session_id() . "'>Seminar Selection Page</a></p>";
        }
    }
    if ($DBConnect) {
        mysqli_close($DBConnect);
    }
    if ($errors > 0) {
        $body .= "<p>Please return to the <a href='ConferenceLogin.php?PHPSESSID=" . session_id() . "'>login page</a> and retry</p>";
    }
?>
<!doctype html>

<html>

<head>
    <!--
    project_02_09_01
    author: nathan howard
    date: 12.5.18
    filename: RegisterProcess.php
    -->
    <title>Registration Processing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
    <?php
        echo $body;
    ?>
</body>

</html>
