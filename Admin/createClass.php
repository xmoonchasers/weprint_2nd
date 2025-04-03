
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';



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
  <title>Attendance</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
</head>

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
            <h1 class="h3 mb-0 text-gray-800">View Class Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Class Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Class Attendance</h6>
                    <?php echo $statusMsg; ?>
                </div>
               

                <div class="card-body">
                  <div class="row">
                      <div class="col-md-4">
                          <div class="form-group row">
                              <input type="date" id="filterDate" class="form-control" placeholder="Select Date">
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group row">
                              <select id="filterSubject" class="form-control">
                              <option value="">Select Course</option>
                                <?php 
                                 $sql = "SELECT DISTINCT course from schedule ;";
                                
                                  $res = mysqli_query($conn, $sql);
                                  
                                  while ($row = mysqli_fetch_assoc($res)) { ?>
                                  <option value="<?php echo $row['course']; ?>"><?php echo $row['course']; ?></option>
                                <?php } ?>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group row">
                          <button class="btn btn-primary w-100 form-control" id="filterBtn">Filter</button>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group row">
                          <button class="btn btn-danger w-100 form-control" id="refreshBtn">Refresh</button>
                          </div>
                      </div>
                  </div>
                </div>  
                
              </div>
                

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Class Attendance</h6>
                  <span>
                  <button class="btn btn-success" id="exportBtn">
                   <i class="fas fa-download me-2"></i>Export CSV
                  </button>
                  <button class="btn btn-success" id="pdfBtn">
                   <i class="fas fa-download me-2"></i>Export PDF
                  </button>
                  <button class="btn btn-primary" id="printBtn">
                      <i class="fas fa-print me-2"></i>Print 
                  </button>
                  </span>
                  
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="attendanceTable">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Year Section</th>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th>Date</th>
                        <th>Time</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if(isset($_POST['view'])){

                      $dateTaken =  $_POST['dateTaken'];

                      $query = "SELECT attendance.*, schedule.*
                      -- students.*,tblclassteacher.*
                      FROM attendance
                        INNER JOIN schedule ON schedule.id = attendance.schedule_id
                      where attendance.date = '$dateTaken' 
                      ";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                              // if($rows['status'] == '1'){$status = "Present"; $colour="#00FF00";}else{$status = "Absent";$colour="#FF0000";}
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                 <td>".$rows['name']."</td>
                                 <td>".$rows['year_section']."</td>
                                 <td>".$rows['course']."</td>
                                 <td>".$rows['firstName']." ".$rows['lastName']."</td>
                                 <td>".$rows['date']."</td>
                                 <td>".$rows['time_in']."-".$rows['time_out']."</td>
                                
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
                    }
                  }else{
                    $query = "SELECT 
                      attendance.*, schedule.*, tblclassteacher.*
                      -- students.*,tblclassteacher.*
                      FROM attendance
                        INNER JOIN schedule ON schedule.id = attendance.schedule_id
                        INNER JOIN tblclassteacher ON schedule.instructor_id = tblclassteacher.Id
                      ";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                              // if($rows['status'] == '1'){$status = "Present"; $colour="#00FF00";}else{$status = "Absent";$colour="#FF0000";}
                             $sn = $sn + 1;
                             list($startTime, $endTime) = explode("-", $timeRange);

                             $timeRange = $rows['time_in']."-".$rows['time_out'];

                             // Extract the starting time
                             list($startTime, $endTime) = explode("-", $timeRange);
                             
                             // Reformat the time
                             $formattedTime = (new DateTime($startTime))->format('g:i A');
                             $formatted = (new DateTime($endTime))->format('g:i A');
                             
                             echo $formattedTime;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                 <td>".$rows['name']."</td>
                                 <td>".$rows['year_section']."</td>
                                 <td>".$rows['course']."</td>
                                  <td>".$rows['firstName']." ".$rows['lastName']."</td>
                                 <td>".$rows['date']."</td>
                                 <td>".$formattedTime. "-" .$formatted."</td>
                                
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
                  }
                    
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
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
  
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <!-- JSZip for CSV export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- CSV export buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <!-- Print button JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <!-- PDFMake for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
  <script>
    $(document).ready(function () {
      var table = $('#attendanceTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
          {
            extend: 'csvHtml5',
            text: 'CSV',
            className: 'btn-light'
          },
          'pdfHtml5',
          'print'
        ]
      });

      $('#filterBtn').on('click', function () {
        var filterDate = $('#filterDate').val();
        var subject = $('#filterSubject').val();

        // Apply filtering logic
        if (filterDate) {
            table.column(5).search('^' + filterDate + '$', true, false).draw(); // Exact date match filtering
        } else {
            table.column(5).search('').draw(); // Clear date filter if no date is selected
        }
        table.column(3).search(subject).draw(); // Subject filtering
      });

      // Refresh Button Logic
    $('#refreshBtn').on('click', function () {
        // Clear all filters
        $('#filterDate').val('');
      
        $('#filterSubject').val('');

        // Reset DataTable filtering
        table.search('').columns().search('').draw();
    });

      $('#exportBtn').on('click', function () {
        table.button('.buttons-csv').trigger();
      });
      $('#printBtn').on('click', function () {
        table.button('.buttons-print').trigger();
      });
      $('#pdfBtn').on('click', function () {
        table.button('.buttons-pdf').trigger();
      });
    });
  </script>
</body>

</html>