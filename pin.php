<?php
// Set timezone to Manila
date_default_timezone_set('Asia/Manila');

// Database connection details
$servername = "localhost";  // Replace with your database server IP/hostname
$username = "root";   // Replace with your database username
$password = "";    // Replace with your database password
$dbname = "attendancemsystem"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$rfidUID = "";
$currentDate = date("Y-m-d"); // Current date for attendance
$currentTime = date("H:i:s"); // Current time for time_in
$currentDay = date("l"); // Get the current day (e.g., "Monday")

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['uid'])) {
    $rfidUID = $_GET['uid'];
    
    // Step 1: Find the user ID from the tblclassteacher table using the PIN
    $sql = "SELECT id, firstName, lastName FROM tblclassteacher WHERE pin='$rfidUID'";
    $result = $conn->query($sql);
    
    if ($result === false) {
        echo "SQL error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // Step 2: Get the user ID
            $row = $result->fetch_assoc();
            $userID = $row['id'];
            $first_name = $row['firstName'];
            $last_name = $row['lastName'];
            $name = $first_name . " " . $last_name;
            // echo "User ID: " . $userID . "<br>";

            // Step 3: Check if the user has a schedule at the current day and time
            $scheduleSql = "SELECT * FROM schedule 
                            WHERE instructor_id='$userID' 
                            AND day='$currentDay' 
                            AND start_time <= '$currentTime' 
                            AND end_time >= '$currentTime'";
            $scheduleResult = $conn->query($scheduleSql);
            
            if ($scheduleResult === false) {
                echo "SQL error: " . $conn->error;
            } else {
                if ($scheduleResult->num_rows > 0) {
                    // Schedule found for the current day and time
                    // echo "User has a schedule at the current date and time.<br>";

                    // Fetch the schedule data
                    $scheduleData = $scheduleResult->fetch_assoc();
                    $course = $scheduleData['course'];  // Course from the schedule table
                    $schedule = $scheduleData['schedule_is'];  // Course from the schedule table

                    // Step 4: Check if the attendance already exists for this user on the current date
                    $attendanceCheckSql = "SELECT id, time_in, time_out FROM attendance 
                                           WHERE user_id='$userID' 
                                           AND date='$currentDate'";
                    $attendanceCheckResult = $conn->query($attendanceCheckSql);

                    if ($attendanceCheckResult->num_rows > 0) {
                        // Attendance already exists for this user on the current date
                        $attendanceRow = $attendanceCheckResult->fetch_assoc();
                        
                        // Check if time_out is empty or if time_in is within the last 3 hours
                        if (empty($attendanceRow['time_out'])) {
                            // Update the time_out field with the current time
                            $updateSql = "UPDATE attendance 
                                          SET time_out='$currentTime' 
                                          WHERE id=" . $attendanceRow['id'];
                            if ($conn->query($updateSql) === TRUE) {
                                echo "exists";
                            } else {
                                echo "Error updating time out: " . $conn->error;
                            }
                        } else {
                            // If time_out is not empty, check if the time_in is within the last 3 hours
                            $timeIn = $attendanceRow['time_in'];
                            $timeInTime = strtotime($timeIn); // Convert time_in to timestamp
                            $currentTimeStamp = strtotime($currentTime); // Convert current time to timestamp
                            $timeDifference = ($currentTimeStamp - $timeInTime) / 3600; // Time difference in hours

                            if ($timeDifference < 3) {
                                // Update the time_out field with the current time if within 3 hours
                                $updateSql = "UPDATE attendance 
                                              SET time_out='$currentTime' 
                                              WHERE id=" . $attendanceRow['id'];
                                if ($conn->query($updateSql) === TRUE) {
                                    echo "exists";
                                } else {
                                    echo "Error updating time out: " . $conn->error;
                                }
                            } else {
                                echo "exists";
                            }
                        }
                    } else {
                        // Step 5: Add attendance data into the attendance table
                        $attendanceSql = "INSERT INTO attendance (user_id, schedule_id, name, date, time_in, time_out) 
                        VALUES ('$userID', '$schedule', '$name', '$currentDate', '$currentTime', NULL)";

                        if ($conn->query($attendanceSql) === TRUE) {
                            echo "exists";
                        } else {
                            echo "Error: " . $attendanceSql . "<br>" . $conn->error;
                        }
                    }
                } else {
                    // No schedule found for the current day and time
                    echo "No schedule found for the current day and time.";
                }
            }
        } else {
            echo "UID does not exist in the database";
        }
    }
}

// Close the connection
$conn->close();
?>
