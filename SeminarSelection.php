<?php
    session_start();
    $errors = 0;
    $body = "";
    $hostname = "localhost";
    $username = "adminer";
    $passwd = "three-teach-20";
    $DBConnect = false;
    $DBName = "conference";
    $tableName = "seminars";
    if (!isset($_SESSION['attendeeID'])) {
        $body .= "<p>Please return to the login page and retry</p>";
        $errors++;
    } else {
        $body .= "<p><a href='ProfileChange.php?PHPSESSID=" . session_id() . "'>Change Your Profile</a></p>";
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
                $body .= "<p>Unable to select to database, \"$DBName\", error: " . mysqli_error($DBConnect) . ".</p>\n";
            }
        }
    }
    if ($errors == 0) {
        $SQLstring = "SELECT * FROM $tableName";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if ($queryResult) {
            $body .= "<table>";
            $body .= "<tr>";
            $body .= "<th>Seminar Date</th>";
            $body .= "<th>Seminar Title</th>";
            $body .= "<th>Availability</th>";
            $body .= "</tr>";
//            process results into something PHP can read
            if (mysqli_num_rows($queryResult) > 0) {
                while (($row = mysqli_fetch_assoc($queryResult)) != false) {
//                    make table with seminar/seminar selections 
                    $tableName2 = "assigned_seminars";
                    $SQLstring2 = "SELECT seminarID FROM $tableName2" . " WHERE attendeeID='" . $_SESSION['attendeeID'] . "'";
                    $queryResult2 = mysqli_query($DBConnect, $SQLstring2);
                    if (!$queryResult2) {
                        $errors++;
                        $body .= "<p>Query failed, error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>";
                    } else {
                        if (mysqli_num_rows($queryResult2) > 0) {
                            $assignedSeminars = [];
                            while (($row2 = mysqli_fetch_assoc($queryResult2)) != false) {
            //                    make table with seminar/seminar selections 
                                if ($row2['seminarID'] === $row['seminarID']) {
                                    $assignedSeminars[] = $row['seminarID'];
                                }
                            }
                                $body .= "<tr>";
                                $body .= "<td>" . $row['seminarDate'] . "</td>";
                                $body .= "<td>" . $row['description'] . "</td>";
                                if (in_array($row['seminarID'], $assignedSeminars)) {
                                    $body .= "<td>Selected</td>";
                                } else {
                                    $body .= "<td>" . "<a href='SeminarSelect.php?PHPSESSID=" . session_id() . "&seminarID=" . $row['seminarID'] . "'>Available</a></td>";
                                }
                                $body .= "</tr>";
//                                $row2 = mysqli_fetch_assoc($queryResult2);
                        } else {
                            $body .= "<tr>";
                            $body .= "<td>" . $row['seminarDate'] . "</td>";
                            $body .= "<td>" . $row['description'] . "</td>";
                            $body .= "<td>" . "<a href='SeminarSelect.php?PHPSESSID=" . session_id() . "&seminarID=" . $row['seminarID'] . "'>Available</a></td>";
                            $body .= "</tr>";            
                        }
                    }
                }
            }
            $body .= "</table";
        } else {
            $body .= "<p>Query failed: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . "</p>";
        }
    }
    if ($DBConnect) {
        mysqli_close($DBConnect);
    }
?>
<!doctype html>

<html>
	<head>
		<title>Seminar Selection</title>
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