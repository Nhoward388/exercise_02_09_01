<?php
     session_start();
    $errors = 0;
    $body = "";
    $hostname = "localhost";
    $username = "adminer";
    $passwd = "three-teach-20";
    $DBConnect = false;
    $DBName = "conference";
    $tableName = "assigned_seminars";
    if (!isset($_GET['seminarID'])) {
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
    if (!$errors == 0) {
        $body .= "Please go back to the <a href='SeminarSelection.php?PHPSESSID=" . session_id() . "'>Seminar Selection Page</a>";
        $errors++;
    } else {
        $SQLstring = "INSERT INTO $tableName" . " (attendeeID, seminarID)" . " VALUES('" . $_SESSION['attendeeID'] . "', '" . $_GET['seminarID'] . "')";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            $errors++;
            $body .= "<p>Query failed to execute, bad syntax</p>";
        } else {
            $body .= "<p>Successfully uploaded seminar choice</p>";
            $body .= "<p><a href='SeminarSelection.php?PHPSESSID='" . session_id() . "'>Seminar Selection Page</a></p>";
        }
    }
    if ($DBConnect) {
        mysqli_close($DBConnect);
    }
?>
<!doctype html>

<html>

<head>
    <title>Seminar Selector</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
</head>

<body>
    <?php
        echo $body;
    ?>
</body>

</html>
