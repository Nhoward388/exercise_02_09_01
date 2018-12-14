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
    if (!isset($_SESSION['pass'])) {
        $body .= "<p>Please return to the login page and retry</p>";
        $errors++;
    } else {
        $email = $_POST['loginEmail'];
    }
    //        only connect if theres no errors
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
                $body .= "<p>Unable to select to database, \"$DBName\", error: " . mysqli_error($DBConnect) . ".</p>\n";
            }
        }
    }
    if ($errors == 0) {
        $SQLstring = "SELECT name, email, companyname FROM $tableName" . " WHERE attendeeID='" . $_SESSION['attendeeID'] . "' AND companyname='" . $_POST['loginCompanyName'] . "'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            $errors++;
            echo "<p>Query not executed, bad SQL syntax.</p>\n";
        }
        if ($errors == 0) {
            if (mysqli_num_rows($queryResult) == 0) {
                $errors++;
                echo "<p>The email address/company name combination entered is not valid</p>";
            } else {
                $row = mysqli_fetch_assoc($queryResult);
//                    $internID = $row['internID'];
                $_SESSION['attendeeID'] = $row['attendeeID'];
                $attendeeName = $row['name'];
                mysqli_free_result($queryResult);
                $body .= "<p>Welcome back $attendeeName!</p>\n";
                $body .= "<p>Click <a href='SeminarSelection.php?PHPSESSID=" . session_id() ."'>here</a> to select a seminar</p>";
            }
        }
    }
    if ($DBConnect) {
        mysqli_close($DBConnect);
    }
    $TableName = "attendee";
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
		<title>Login Processing</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0">
	</head>

	<body>
    <?php 
       echo $body;
    ?>
	</body>
</html>