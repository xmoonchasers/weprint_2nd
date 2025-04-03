
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

if (isset($_POST['save'])) {
    
  // Retrieve form data
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $middle_name = $_POST['middle_name'];
  $year = $_POST['year'];
  $section = $_POST['section'];
  $rfid = $_POST['rfid'];
  $email = $_POST['email'];
  $sampPass = $_POST['password'];
  $sampPass_2 = md5($sampPass);

  // Check for duplicate RFID
  $query = mysqli_query($conn, "SELECT * FROM students WHERE rfid = '$rfid'");
  $ret = mysqli_fetch_array($query);

  if ($ret > 0) { 
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This RFID Already Exists!</div>";
  } else {
      // Insert data into the database
      $query = mysqli_query($conn, "INSERT INTO students 
          (first_name, last_name, middle_name, year, section, rfid, email) 
          VALUES 
          ('$first_name', '$last_name', '$middle_name', '$year', '$section', '$rfid', '$email')");

      if ($query) {
          $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An Error Occurred!</div>";
      }
  }
}
}

//---------------------------------------iMPORT via CSV-------------------------------------------------------------
   // FOR UPLOADING STUDENT DETAILS
   if(isset($_POST['but_import'])){
		$target_dir ="uploads/";
		$target_file = $target_dir.basename($_FILES['importfile']['name']);

		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

		$uploadOK = 1;
		if ($imageFileType != 'csv') {
			$uploadOK=0;
		}

		if ($uploadOK != 0) {
			if (move_uploaded_file($_FILES['importfile']['tmp_name'], $target_file)) {
				
        $fileexists =0;
        if (file_exists($target_file)) {
            $fileexists =1;
        }
        if ($fileexists == 1) {

            $file =fopen($target_file, "r");
            $index =0;

            $importData_arr =array();

            while (($data = fgetcsv($file, 1000, ",")) !=FALSE) {
                $num = count($data);

                for ($c=0; $c <$num ; $c++) {
                    $importData_arr[$index][] = $data[$c];
                }
                $index++;
            }
            fclose($file);

            $skip = 0;
            foreach ($importData_arr as $data) {
                //skip the first index
                if ($skip !=0) {
                    $email = $data[0];
                    $fname = $data[1];
                    $lname = $data[2];
                    $mname = $data[3];
                    $year = $data[4];
                    $section = $data[5];
                  
                    //checking entry
                    $checkUser = "SELECT count(*) as allcount FROM students WHERE email='".$email."' ";

                    $retrieve_data = mysqli_query($conn, $checkUser);
                    $row = mysqli_fetch_assoc($retrieve_data);
                    $count = $row['allcount'];

                    if ($count == 0) {
                      //insert Record
                      mysqli_query($conn, "INSERT INTO students(`email`, `first_name`, `last_name`, `middle_name`, `year`, `section`) 
                                            VALUES ('".$email."', '".$fname."' , '".$lname."' , '".$mname."' , '".$year."' , '".$section."') ");
                    }
                }
                $skip++;
            }
            //delete file after import
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
			}
		}
	}





//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];

        $query=mysqli_query($conn,"select * from students where student_id ='$Id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

  if(isset($_POST['update'])){
    
      $first_name=$_POST['first_name'];
      $last_name=$_POST['last_name'];
      $middle_name=$_POST['middle_name'];

      $rfid=$_POST['rfid'];

      $query=mysqli_query($conn,"update students set first_name='$first_name', last_name='$last_name',
      middle_name='$middle_name', rfid='$rfid'
      where student_id='$Id'");
      if ($query) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Udated Successfully!</div>";

          echo "<script type = \"text/javascript\">
          window.location = (\"createStudents.php\")
          </script>"; 
        
      }
      else
      {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  }


//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];

        $query = mysqli_query($conn,"DELETE FROM students WHERE student_id='$Id'");

        if ($query == TRUE) {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Succesfully Deleted</div>"; 
            echo "<script type = \"text/javascript\">
            window.location = (\"createStudents.php\")
            </script>";
        }
        else{

            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
         }
      
  }


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



<script>
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
  }
</script>
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
            <h1 class="h3 mb-0 text-gray-800">Enroll Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Enroll Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Enroll Students</h6>
                    <?php echo $statusMsg; ?>
                </div>
                
                <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <p class="card-description">
                      Add Students
                      <code>.upload using csv file</code>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Upload the Student file</label>
                            <div class="col-sm-9">
                              <input type="file" class="form-control" name="importfile" />
                            </div>
                        </div>
                    </p>
                    <button class="btn btn-success btn-block" name="but_import">UPLOAD</button>
                  </form>
                  <hr>
                  <center><b>Or Add Manually</b></center>
                  <hr>
                  <div class="form-group row mb-3">
                      
                      </div>
                <form method="POST" action="">
                    <div class="form-group row mb-3">
                      <div class="col-xl-4">
                        <label class="form-control-label">First Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo $row['first_name'];?>" id="exampleInputfirst_name" >
                      </div>
                      <div class="col-xl-4">
                        <label class="form-control-label">Last Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo $row['last_name'];?>" id="exampleInputfirst_name" >
                      </div>
                      <div class="col-xl-4">
                        <label class="form-control-label">Middle Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="middle_name" value="<?php echo $row['middle_name'];?>" id="exampleInputfirst_name" >
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
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="email" value="<?php echo $row['email'];?>" id="exampleInputfirst_name" >
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">RFID<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="rfid" value="<?php echo $row['rfid'];?>" id="exampleInputfirst_name" >
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
                    <button type="submit" name="save" class="btn btn-primary btn-block">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th> Name</th>
                        <th>Year Section</th>
                        <th>RFID</th>
                         <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                
                    <tbody>

                  <?php
                      $query = "SELECT *
                      FROM students";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['first_name']." ".$rows['last_name']." ".$rows['last_name']."</td>
                                <td>".$rows['year']."" .$rows['section']."</td>
                                <td>".$rows['rfid']."</td>
                               
                                <td><a href='?action=edit&Id=".$rows['student_id']."'><i class='fas fa-fw fa-edit'></i></a></td>
                                <td><a href='?action=delete&Id=".$rows['student_id']."'><i class='fas fa-fw fa-trash'></i></a></td>
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

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>