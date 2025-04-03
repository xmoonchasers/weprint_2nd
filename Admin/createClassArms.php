
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   //------------------------SAVE--------------------------------------------------
   $day = $conn->real_escape_string($_POST['day']);
   $start_time = $conn->real_escape_string($_POST['start']);
   $end_time = $conn->real_escape_string($_POST['end']);
   $instructor_id = $conn->real_escape_string($_POST['instructor']);
   $course = $conn->real_escape_string($_POST['course']);
   $year = $conn->real_escape_string($_POST['year']);
   $section = $conn->real_escape_string($_POST['section']);
   $year_section = $year . $section; // Combine year and section into year_section
   
   if (isset($_POST['save'])) {
       // Check for conflicts
       $check_sql = "SELECT * FROM schedule 
                     WHERE day = '$day' 
                     AND (
                         ('$start_time' >= start_time AND '$start_time' < end_time) 
                         OR ('$end_time' > start_time AND '$end_time' <= end_time) 
                         OR ('$start_time' <= start_time AND '$end_time' >= end_time)
                     )";
       $result = $conn->query($check_sql);
   
       if ($result->num_rows > 0) {
           echo "<script>alert('Schedule conflict! This time slot is already taken.');</script>";
       } else {
           // Insert query
           $sql = "INSERT INTO schedule (day, start_time, end_time, instructor_id, course, year_section) 
                   VALUES ('$day', '$start_time', '$end_time', '$instructor_id', '$course', '$year_section')";
   
           if ($conn->query($sql) === TRUE) {
               echo "<script>alert('Schedule added successfully!');</script>";
           } else {
               echo "<script>alert('Error: " . $conn->error . "');</script>";
           }
       }
   }
   

    //------------------------Update--------------------------------------------------
    
    if (isset($_POST['update'])) {
      $Id = $_POST['edit_id'];
      $day = $_POST['edit_day'];
      $start_time = $_POST['edit_start'];
      $end_time = $_POST['edit_end'];
      $instructor_id = $_POST['edit_instructor'];
      $course = $_POST['edit_course'];
      $year = $_POST['edit_year'];
      $section = $_POST['edit_section'];
      $year_section = $year . $section; 
  
      // Check for conflicts before updating
      $check_sql = "SELECT * FROM schedule 
                    WHERE day = '$day' 
                    AND id != '$Id' 
                    AND (
                        ('$start_time' >= start_time AND '$start_time' < end_time) 
                        OR ('$end_time' > start_time AND '$end_time' <= end_time) 
                        OR ('$start_time' <= start_time AND '$end_time' >= end_time)
                    )";
      
      $result = $conn->query($check_sql);
  
      if ($result->num_rows > 0) {
          echo "<script>alert('Schedule conflict! This time slot is already taken.');</script>";
      } else {
          // Update query
          $sql = "UPDATE schedule 
                  SET day='$day', start_time='$start_time', end_time='$end_time', 
                      instructor_id='$instructor_id', course='$course', year_section='$year_section' 
                  WHERE id='$Id'";
  
          if ($conn->query($sql) === TRUE) {
              echo "<script>alert('Schedule updated successfully!');</script>";
          } else {
              echo "<script>alert('Error: " . $conn->error . "');</script>";
          }
      }
  }
  
    //------------------------DELETE--------------------------------------------------
  
    if (isset($_POST['delete'])) {
      $Id = $_POST['id'];
      $sql = "DELETE FROM `schedule` WHERE id='$Id'";

      if ($conn->query($sql) === TRUE) {
          echo "<script>alert('Schedule successfully  Deleted!');</script>";
      } else {
          echo "<script>alert('Error: " . $conn->error . "');</script>";
      }
    }

  

}




// Fetch data from schedule table
$sql = "
    SELECT s.*, i.firstName AS instructor_name, i.lastName AS lname 
    FROM schedule s
    INNER JOIN tblclassteacher i ON s.instructor_id = i.id
    ORDER BY FIELD(s.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), s.start_time
    ";
$result = $conn->query($sql);

// Group data by day
$schedule_data = [];
while ($row = $result->fetch_assoc()) {
    $schedule_data[$row['day']][] = $row;
}

// Define days and time slots
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$time_slots = [
    "07:00:00",
    "08:00:00",
    "09:00:00",
    "10:00:00",
    "11:00:00",
    "12:00:00",
    "13:00:00",
    "14:00:00",
    "15:00:00",
    "16:00:00",
    "17:00:00",
];

// Create an array to track occupied cells
$occupied = [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>
<style>
  body {
      font-family: Arial, sans-serif;
  }
  table {
      border-collapse: collapse;
      width: 100%;
  }
  th, td {
      border: 1px solid black;
      text-align: center;
      vertical-align: middle;
      padding: 10px;
  }
  th {
      background-color: #f2f2f2;
  }
  .time-slot {
      width: 100px;
  }
  .class-cell {
      background-color: #90ee90; /* Light green */
  }
</style>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Class Schedule</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class Schedule</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">         
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Schedule</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Day<span class="text-danger ml-2">*</span></label>
                          <select required name="day" class="form-control mb-3">
                            <option value="Monday" >Monday</option>
                            <option value="Tuesday" >Tuesday</option>
                            <option value="Wednesday" >Wednesday</option>
                            <option value="Thursday" >Thursday</option>
                            <option value="Friday" >Friday</option>
                            <option value="Saturday" >Saturday</option>
                            <option value="Sunday" >Sunday</option>
                          </select>
                      </div>
                      <div class="col-xl-6">
                      <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Start Time<span class="text-danger ml-2">*</span></label>
                        <input type="time" class="form-control" name="start" value="<?php echo $row['classArmName'];?>" id="exampleInputFirstName" placeholder="Class Schedule">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">End Time<span class="text-danger ml-2">*</span></label>
                        <input type="time" class="form-control" name="end" value="<?php echo $row['classArmName'];?>" id="exampleInputFirstName" placeholder="Class Schedule">
                      </div>
                    </div>
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Instructor<span class="text-danger ml-2">*</span></label>
                         <?php
                        $qry= "SELECT * FROM tblclassteacher ORDER BY firstName ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="instructor" class="form-control mb-3">';
                          echo'<option value="">--Select Instructor--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['Id'].'" >'.$rows['firstName'] ." ".$rows['lastName'].'</option>';
                              }
                                  echo '</select>';
                              }
                            ?>  
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Course<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="course" value="<?php echo $row['classArmName'];?>" id="exampleInputFirstName" placeholder="Course Name">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Year<span class="text-danger ml-2">*</span></label>
                        <select required name="year" class="form-control mb-3">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Section<span class="text-danger ml-2">*</span></label>
                        <select required name="section" class="form-control mb-3">
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="D">D</option>
                          <option value="E">E</option>
                          <option value="F">F</option>
                          <option value="G">G</option>
                          <option value="H">H</option>
                          <option value="I">I</option>
                          <option value="J">J</option>
                        </select>
                      </div>
                    </div>
                   
                    <button type="submit" name="save" class="btn btn-primary form-control">Save</button>
                   
                  </form>
                </div>
              </div>




              <div class="col-lg-12">         
              <div class="card mb-4">
               
                <div class="card-body">
                <table cellspacing="0" cellpadding="5">
                  <tr>
                      <th>Time</th>
                      <?php foreach ($days as $day): ?>
                          <th><?php echo $day; ?></th>
                      <?php endforeach; ?>
                  </tr>
                  <?php foreach ($time_slots as $start_time): ?>
                      <tr>
                          <td><span style='font-size: 12px;'><?php echo date('g:i A', strtotime($start_time)) . ' - ' . date('g:i A', strtotime($start_time) + 3600); ?></span></td>
                          <?php foreach ($days as $day): ?>
                              <?php
                              if (isset($occupied[$day][$start_time])) {
                                  echo "<td></td>";
                                  continue;
                              }

                              $current_schedule = null;
                              if (isset($schedule_data[$day])) {
                                  foreach ($schedule_data[$day] as $schedule) {
                                      $schedule_start = new DateTime($schedule['start_time']);
                                      $schedule_end = new DateTime($schedule['end_time']);
                                      $slot_time = new DateTime($start_time);

                                      if ($slot_time >= $schedule_start && $slot_time < $schedule_end) {
                                          $current_schedule = $schedule;
                                          break;
                                      }
                                  }
                              }

                              if ($current_schedule) {
                                  $start = new DateTime($current_schedule['start_time']);
                                  $end = new DateTime($current_schedule['end_time']);
                                  $duration = $start->diff($end)->h;
                                  $sTime = date("g:i A", strtotime($current_schedule['start_time']));
                                  $eTime = date("g:i A", strtotime($current_schedule['end_time']));

                                  echo "<td rowspan='$duration' style='background-color: #A1E3F9; border: 1px solid #dc3545; padding: 5px;'>";
                                  echo "<strong>" . $current_schedule['course'] . "</strong><br>";
                                  echo "<span style='font-size: 12px;'>" . $current_schedule['year_section'] . "</span><br>";
                                  echo "<span style='font-size: 10px;'>" . $current_schedule['instructor_name'] . " " . $current_schedule['lname'] . "</span><br>";
                                  echo "<span style='font-size: 10px; color: #555;'>" . $sTime . " - " . $eTime . "</span><br>"; ?>
                                  
                                  <form method="POST">
                                      <a href="#" class="edit-btn"
                                        data-id="<?php echo $current_schedule['id']; ?>"
                                        data-day="<?php echo $current_schedule['day']; ?>"
                                        data-start="<?php echo $current_schedule['start_time']; ?>"
                                        data-end="<?php echo $current_schedule['end_time']; ?>"
                                        data-instructor="<?php echo $current_schedule['instructor_id']; ?>"
                                        data-course="<?php echo $current_schedule['course']; ?>"
                                        data-year="<?php echo $current_schedule['year']; ?>"
                                        data-section="<?php echo $current_schedule['section']; ?>"
                                        data-toggle="modal" data-target="#editScheduleModal"
                                        style="font-size: 10px; color: blue;">
                                          <i class='fas fa-fw fa-edit'></i>Edit
                                      </a>
                                      <input type="hidden" name="id" value="<?php echo $current_schedule['id']; ?>">
                                      <button style="border:none; background:none; color:red; font-size: 10px;" name='delete'>
                                          <i class='fas fa-fw fa-trash'></i>Delete
                                      </button>
                                  </form>
                                  
                                  <?php echo "</td>";

                                  $occupied_time = clone $start;
                                  for ($i = 0; $i < $duration; $i++) {
                                      $occupied[$day][$occupied_time->format('H:i:s')] = true;
                                      $occupied_time->modify('+1 hour');
                                  }
                              } else {
                                  echo "<td>&nbsp;</td>";
                              }
                              ?>
                          <?php endforeach; ?>
                      </tr>
                  <?php endforeach; ?>
              </table>

                </div>
              </div>
 


            


    

         
        
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>


                <!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editScheduleForm">
          <input type="text" name="edit_id" id="edit_id" >

          <div class="form-group">
            <label>Select Day</label>
            <select required name="edit_day"id="edit_day" id="edit_day" class="form-control">
              <option value="Monday">Monday</option>
              <option value="Tuesday">Tuesday</option>
              <option value="Wednesday">Wednesday</option>
              <option value="Thursday">Thursday</option>
              <option value="Friday">Friday</option>
              <option value="Saturday">Saturday</option>
              <option value="Sunday">Sunday</option>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group ">
                  <label>Start Time</label>
                  <input type="time" class="form-control" name="edit_start" id="edit_start">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ">
                  <label>End Time</label>
                  <input type="time" class="form-control" name="edit_end" id="edit_end">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Instructor</label>
            <select required name="edit_instructor" id="edit_instructor" class="form-control">
              <option value="">--Select Instructor--</option>
              <?php
              $qry = "SELECT * FROM tblclassteacher ORDER BY firstName ASC";
              $result = $conn->query($qry);
              while ($rows = $result->fetch_assoc()) {
                echo '<option value="'.$rows['Id'].'">'.$rows['firstName']." ".$rows['lastName'].'</option>';
              }
              ?>
            </select>
          </div>
          

          <div class="form-group">
            <label>Course</label>
            <input type="text" class="form-control" name="edit_course" id="edit_course">
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
              <label>Year</label>
              <select required name="edit_year" id="edit_year" class="form-control">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
              </select>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Section</label>
            <select required name="edit_section" id="edit_section" class="form-control">
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
              <option value="E">E</option>
              <option value="F">F</option>
              <option value="G">G</option>
              <option value="H">H</option>
              <option value="I">I</option>
              <option value="J">J</option>
            </select>
          </div>
            </div>
          </div>
          <button type="submit" name="update" class="btn btn-primary form-control">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });



    // Wait for the DOM to load before adding event listeners
document.addEventListener('DOMContentLoaded', function() {
    const cells = document.querySelectorAll('.clickable');

    cells.forEach(function(cell) {
        cell.addEventListener('click', function() {
            // Retrieve data from the clicked schedule cell
            const day = cell.getAttribute('data-day');
            const startTime = cell.getAttribute('data-start-time');
            const endTime = cell.getAttribute('data-end-time');
            const instructor = cell.getAttribute('data-instructor');
            const course = cell.getAttribute('data-course');
            const year = cell.getAttribute('data-year');
            const section = cell.getAttribute('data-section');

            // Populate the form with the schedule data
            document.querySelector('select[name="day"]').value = day;
            document.querySelector('input[name="start"]').value = startTime;
            document.querySelector('input[name="end"]').value = endTime;
            document.querySelector('select[name="instructor"]').value = instructor;
            document.querySelector('input[name="course"]').value = course;
            document.querySelector('select[name="year"]').value = year;
            document.querySelector('select[name="section"]').value = section;
        });
    });
});




$(document).ready(function () {
  $(".edit-btn").click(function () {
    var id = $(this).data("id");
    var day = $(this).data("day");
    var start = $(this).data("start");
    var end = $(this).data("end");
    var instructor = $(this).data("instructor");
    var course = $(this).data("course");
    var year = $(this).data("year");
    var section = $(this).data("section");

    $("#edit_id").val(id);
    $("#edit_day").val(day);
    $("#edit_start").val(start);
    $("#edit_end").val(end);
    $("#edit_instructor").val(instructor);
    $("#edit_course").val(course);
    $("#edit_year").val(year);
    $("#edit_section").val(section);
  });
});


  </script>



</body>

</html>