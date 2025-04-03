
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $day = $conn->real_escape_string($_POST['day']);
    $start_time = $conn->real_escape_string($_POST['start']);
    $end_time = $conn->real_escape_string($_POST['end']);
    $instructor_id = $conn->real_escape_string($_POST['instructor']);
    $course = $conn->real_escape_string($_POST['course']);
    $year = $conn->real_escape_string($_POST['year']);
    $section = $conn->real_escape_string($_POST['section']);
    $year_section = $year . $section; // Combine year and section into year_section

    if (isset($_POST['save'])) {
        // Insert query
        $sql = "INSERT INTO schedule (day, start_time, end_time, instructor_id, course, year_section) 
                VALUES ('$day', '$start_time', '$end_time', '$instructor_id', '$course', '$year_section')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Schedule added successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } elseif (isset($_POST['update']) && isset($Id)) {
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



//------------------------SAVE--------------------------------------------------

// if(isset($_POST['save'])){
    
//     $classId=$_POST['classId'];
//     $classArmName=$_POST['classArmName'];
   
//     $query=mysqli_query($conn,"select * from tblclassarms where classArmName ='$classArmName' and classId = '$classId'");
//     $ret=mysqli_fetch_array($query);

//     if($ret > 0){ 

//         $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Class Arm Already Exists!</div>";
//     }
//     else{

//         $query=mysqli_query($conn,"insert into tblclassarms(classId,classArmName,isAssigned) value('$classId','$classArmName','0')");

//     if ($query) {
        
//         $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
//     }
//     else
//     {
//          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
//     }
//   }
// }


// //--------------------EDIT------------------------------------------------------------

//  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
// 	{
//         $Id= $_GET['Id'];

//         $query=mysqli_query($conn,"select * from tblclassarms where Id ='$Id'");
//         $row=mysqli_fetch_array($query);

//         //------------UPDATE-----------------------------

//         if(isset($_POST['update'])){
    
//             $classId=$_POST['classId'];
//             $classArmName=$_POST['classArmName'];

//             $query=mysqli_query($conn,"update tblclassarms set classId = '$classId', classArmName='$classArmName' where Id='$Id'");

//             if ($query) {
                
//                 echo "<script type = \"text/javascript\">
//                 window.location = (\"createClassArms.php\")
//                 </script>"; 
//             }
//             else
//             {
//                 $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
//             }
//         }
//     }


// //--------------------------------DELETE------------------------------------------------------------------

//   if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
// 	{
//         $Id= $_GET['Id'];

//         $query = mysqli_query($conn,"DELETE FROM tblclassarms WHERE Id='$Id'");

//         if ($query == TRUE) {

//                 echo "<script type = \"text/javascript\">
//                 window.location = (\"createClassArms.php\")
//                 </script>";  
//         }
//         else{

//             $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
//          }
      
//   }

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
            <h1 class="h3 mb-0 text-gray-800">Mac Laboratory Weekly Schedule</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Weekly Schedule</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <!-- <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Schedule</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                         <?php
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="classId" class="form-control mb-3">';
                          echo'<option value="">--Select Class--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['Id'].'" >'.$rows['className'].'</option>';
                              }
                                  echo '</select>';
                              }
                            ?>  
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Create Class Schedule<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="classArmName" value="<?php echo $row['classArmName'];?>" id="exampleInputFirstName" placeholder="Class Schedule">
                        </div>
                    </div>
                      <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div> -->
         


            
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

    

              <!-- Input Group -->
                 <!-- <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Class Schedule</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th></th>
                        <th>Class Name</th>
                        <th>Class Schedule</th>
                         <th>Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                  
                    <tbody>

                  <?php
                      $query = "SELECT tblclassarms.Id,tblclassarms.isAssigned,tblclass.className,tblclassarms.classArmName 
                      FROM tblclassarms
                      INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                              if($rows['isAssigned'] == '1'){$status = "Assigned";}else{$status = "UnAssigned";}
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['className']."</td>
                                <td>".$rows['classArmName']."</td>
                                <td>".$status."</td>
                                <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                      }
                      
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div> -->
          </div>
          
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
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

  </script>



</body>

</html>