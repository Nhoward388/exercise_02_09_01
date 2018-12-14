<?php
    session_start();
    $body = "";
    $errors = 0;
    $internID = 0;
//    if (isset($_GET['internID'])) {
//        $internID = $_GET['internID'];
//    }
    if (!isset($_SESSION['internID'])) {
        ++$errors;
        $body .= "<p>You have not logged in or registered. Please return to the <a href='InternLogin.php'>Registration / Login Page</a></p>\n";
    }
    if ($errors == 0) {
        if (isset($_GET['opportunityID'])) {
            $opportunityID = $_GET['opportunityID'];
        } else {
            ++$errors;
            $body .= "<p>You have not selected an opportunity. Please return to the <a href='AvailableOpportunities.php?PHPSESSID=" . session_id() . ">Opportunities Page</a></p>\n";
        }   
    };
    $DBConnect = false;
    if ($errors === 0) {
        $hostname = "localhost";
        $username = "adminer";
        $passwd = "three-teach-20";
        $DBConnect = false;
        $DBName = "internships2";
        $tableName = "interns";
        if ($errors == 0) {
            $DBConnect = mysqli_connect($hostname, $username, $passwd);
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
    }
    $displayDate = date("l, F j, Y, g:i A");
    $body .= "\$displayDate: $displayDate<br>";
    $dbDate = date("Y-m-d H:i:s");
    $body .= "\$dbDate: $dbDate<br>";
    if ($errors == 0) {
        $tableName = "assigned_opportunities";
        $SQLstring = "INSERT INTO $tableName" . " (opportunityID, internID, dateSelected)" . " VALUES($opportunityID, " . $_SESSION['internID'] . ", '$dbDate')";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            ++$errors;
            $body .= "<p>Unable to execute the query," . " error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
        } else {
            $body .= "<p>Your results for opportunity #" . " $opportunityID have been entered on" . " $displayDate.</p>\n";
        }
    }
    if ($DBConnect) {
        $body .= "<p>Closing database connection.</p>\n";
        mysqli_close($DBConnect);
    }   
    if ($_SESSION['internID'] > 0) {
        $body .= "<p>Return to the" . " <a href='AvailableOpportunities.php?PHPSESSID=" . session_id() . "'>Opportunities</a> page.</p>";
    } else {
        $body .= "<p>Return to the" . 
            " <a href='InternLogin.php'>" . 
            "Register or Login</a> to use this page.</p>";
    }
    if ($errors == 0) {
//        $body .= "setting cookie<br>";
        setcookie("LastRequestDate", urlencode($displayDate), time()+60*60*24*7);
    }
?>
    <!doctype html>

    <html>

    <head>
        <title>Request Opportunity</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <script src="modernizr.custom.65897.js"></script>
    </head>

    <body>
        <h1>College Internship</h1>
        <h2>Opportunity Requested</h2>
        <?php
        echo $body;
    ?>
    </body>

    </html>
