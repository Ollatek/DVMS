<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid = $_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    
<?php include ('assets/inc/head.php');?>

    <body>

        <div id="wrapper">

            <?php include('assets/inc/nav.php');?>
            <?php include("assets/inc/sidebar.php");?>

            <?php
                if(isset($_GET['expiry_date'])) {
                    $batch_number = $_GET['expiry_date'];
                    $ret = "SELECT * FROM drugphp WHERE expiry_date = ?";
                    $stmt = $mysqli->prepare($ret);
                    
                    if (!$stmt) { die("Query failed: " . $mysqli->error); }

                    $stmt->bind_param('s', $batch_number);
                    if (!$stmt->execute()) { die("Execution failed: " . $stmt->error); }

                    $res = $stmt->get_result();
                    if (!$res) { die("Fetch failed: " . $stmt->error); }

                    if ($res->num_rows == 0) {
                        die("No drug found with the given expiry_date.");
                    }
                    
                } else {
                    echo "<script>alert('No Batch Number Provided'); window.location.href='his_admin_pham_inventory.php';</script>";
                    exit;
                }
                
                while($row = $res->fetch_object()) {
            ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">#<?php echo $row->expiry_date;?> - <?php echo $row->name;?></h4>
                                </div>
                            </div>
                        </div>     

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-xl-5">
                                            <img src="assets/images/pharm.webp" alt="Drug Image" class="img-fluid rounded">
                                        </div>
                                        <div class="col-xl-7">
                                            <h2>Drug Name: <?php echo $row->name;?></h2>
                                            <h4 class="text-danger">Generic Name: <?php echo $row->generic_name;?></h4>
                                            <h4 class="text-danger">NAFDAC Reg No: <?php echo $row->nafdac_reg_no;?></h4>
                                            <h4 class="text-danger">Category: <?php echo $row->category;?></h4>
                                            <h4 class="text-danger">Type: <?php echo $row->type;?></h4>
                                            <h4 class="text-danger">ATC Code: <?php echo $row->ATC_code;?></h4>
                                            <h4 class="text-danger">Expiry Date: <?php echo $row->expiry_date;?></h4>
                                            <h4 class="text-danger">Description: <?php echo $row->description;?></h4>
                                           

                                            <!-- New Section: Drug Action -->
                                            <h4 class="text-danger">Action</h4>
                                            <p class="text-muted"> 
                                                <?php 
                                                    echo (!empty($row->action)) ? $row->action : "No action information available."; 
                                                ?> 
                                            </p>
                                            <!-- End of Drug Action Section -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include('assets/inc/footer.php');?>
            </div>
            <?php } ?>
        </div>
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
