<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
  
  if (isset($_GET['delete'])) {
    $drug_id = intval($_GET['delete']); // Ensure it's an integer
    $stmt = $mysqli->prepare("DELETE FROM drugphp WHERE drug_id = ?");
    $stmt->bind_param('i', $drug_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Drug deleted successfully!'); window.location.href='his_admin_pharm_inventory.php';</script>";
    } else {
        echo "<script>alert('Error deleting drug!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    
<?php include('assets/inc/head.php');?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
                <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
                <?php include("assets/inc/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                                            <li class="breadcrumb-item active">Pharmaceuticals Inventory</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <h4 class="header-title">Pharmaceuticals Inventory</h4>
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-12 text-sm-center form-inline">
                                                <div class="form-group">
                                                    <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                            <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Drug Name</th>
                                                <th>Generic Name</th>
                                                <th>NAFDAC Reg No</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>ATC_code</th>
                                                <th>Expiry Date</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <?php
                                                $ret = "SELECT * FROM drugphp ORDER BY RAND ()"; 
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                $cnt = 1;

                                                while ($row = $res->fetch_object()) {
                                            ?>
                                                <tbody>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->name; ?></td>
                                                    <td><?php echo $row->generic_name; ?></td>
                                                    <td><?php echo $row->nafdac_reg_no; ?></td>
                                                    <td><?php echo $row->category; ?></td>
                                                    <td><?php echo $row->type; ?></td>
                                                    <td><?php echo $row->ATC_code; ?></td>
                                                    <td><?php echo $row->expiry_date; ?></td>
                                                    <td>
													<a href="his_admin_pharm_inventory.php?delete=<?php echo $row->drug_id;?>" class="badge badge-danger"><i class=" mdi mdi-trash-can-outline "></i> Delete</a>
													<a href="his_admin_view_single_pharm.php?expiry_date=<?php echo $row->expiry_date;?>" class="badge badge-success"><i class="far fa-eye"></i> View</a>
													 <a href="his_admin_update_single_employee.php?doc_number=<?php echo $row->doc_number;?>" class="badge badge-primary"><i class="mdi mdi-check-box-outline "></i> Update</a>
													</td>
                                                </tr>
                                                </tbody>
                                            <?php 
                                                $cnt++; 
                                                } 
                                            ?>
                                            <tfoot>
                                            <tr class="active">
                                                <td colspan="9">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end .table-responsive-->
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div>

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                 <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div>

        </div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Footable js -->
        <script src="assets/libs/footable/footable.all.min.js"></script>

        <!-- Init js -->
        <script src="assets/js/pages/foo-tables.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html> 
