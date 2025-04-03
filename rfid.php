<?php
date_default_timezone_set('Asia/Manila');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendancemsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$rfidUID = "";
$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['uid'])) {
    $rfidUID = $_GET['uid'];
    $currentDay = date("l"); // Get the current day (e.g., "Monday")

    // Step 1: Check if RFID exists in tblstudents and retrieve the year_section
    $sql = "SELECT id, year,section, first_name, last_name FROM students WHERE rfid='$rfidUID'";
    $result = $conn->query($sql);
    
    if ($result === false) {
        echo "SQL error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // RFID found, fetch the year_section and student ID
            $row = $result->fetch_assoc();
            $studentId = $row['id'];
            $year = $row['year'];
            $section = $row['section'];
            $yearSection = $year."". $section;
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $name = $first_name . " " . $last_name;
            echo "UID_FOUND. Year Section: " . $yearSection . "<br>";

            // Step 2: Check if there's a schedule for this year_section on the current date and time
            $scheduleSql = "SELECT * FROM schedule 
                            WHERE year_section='$yearSection' 
                            AND day='$currentDay' 
                            AND start_time <= '$currentTime' 
                            AND end_time >= '$currentTime'";

            $scheduleResult = $conn->query($scheduleSql);

            if ($scheduleResult === false) {
                echo "SQL error: " . $conn->error;
            } else {
                if ($scheduleResult->num_rows > 0) {
                    // Schedule found, fetch instructor_id and course
                    $scheduleRow = $scheduleResult->fetch_assoc();
                    $courseName = $scheduleRow['course']; // Assuming the course name is in the 'course' column
                    $schedule = $scheduleRow['id']; // Assuming the course name is in the 'course' column
                    echo "Schedule found for the year_section on the current date and time.<br>";
                    echo "Course: " . $courseName . "<br>";

                    // Step 3: Check if the student already has a record in the attendance table for today
                    $attendanceSql = "SELECT * FROM attendance 
                                      WHERE user_id='$studentId' 
                                      AND date='$currentDate'";

                    $attendanceResult = $conn->query($attendanceSql);

                    if ($attendanceResult === false) {
                        echo "SQL error: " . $conn->error;
                    } else {
                        if ($attendanceResult->num_rows > 0) {
                            // Record exists, update the time_out
                            $updateSql = "UPDATE attendance 
                                          SET time_out='$currentTime' 
                                          WHERE user_id='$studentId' 
                                          AND date='$currentDate'";

                            if ($conn->query($updateSql) === TRUE) {
                                echo "Time-out updated successfully for the student.<br>";
                            } else {
                                echo "Error updating time-out: " . $conn->error;
                            }
                        } else {
                            // Step 4: Insert a new attendance record for the student
                            $insertSql = "INSERT INTO attendance (user_id, schedule_id, name, date, time_in, time_out) 
                                          VALUES ('$studentId', '$schedule', '$name', '$currentDate', '$currentTime', NULL)";

                            if ($conn->query($insertSql) === TRUE) {
                                echo "Attendance recorded successfully for the student.<br>";
                            } else {
                                echo "Error inserting attendance: " . $conn->error;
                            }
                        }
                    }
                } else {
                    echo "No schedule found for the year_section on the current date and time.<br>";
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
