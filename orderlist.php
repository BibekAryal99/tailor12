<?php
require_once ('function.php');
dbconnect();
session_start();

if (!is_user()) {
  redirect('index.php');
}

$user = $_SESSION['username'];
$usid_query = $pdo->query("SELECT id FROM users WHERE username='$user'");
if ($usid_query === false) {
  die('Error: Unable to fetch user ID.');
}
$usid = $usid_query->fetch(PDO::FETCH_ASSOC);
$uid = $usid['id'];
include ('header.php');

if (isset($_GET["id"])) {
  $id = $_GET["id"];
  // Check if 'id' exists and is not empty
  if (isset($id) && !empty($id)) {
    $res = $pdo->exec("DELETE FROM `order` WHERE id='$id'");
    if ($res === false) {
      echo "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>  
                    Error: Unable to delete order!
                  </div>";
    } else {
      echo "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>  
                    Order Deleted Successfully!
                  </div>";
    }
  }
}

?>

<!-- Rest of your HTML code -->


<link href="css/style.default.css" rel="stylesheet">
<link href="css/jquery.datatables.css" rel="stylesheet">
<!-- DataTables CSS -->
<link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
<!-- DataTables Responsive CSS -->
<link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">View Orders</h1>
    </div>
    <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->

  <div class="panel panel-default">
    <div class="panel-body">
      <div class="clearfix mb30"></div>
      <div class="table-responsive">
        <table class="table table-striped" id="table2">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Description</th>
              <th>Date Received</th>
              <th>Amount</th>
              <th>Balance</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $ddaa = $pdo->query("SELECT id, customer, description, date_received, amount, paid FROM `order` ORDER BY id desc");
            if ($ddaa === false) {
              die('Error: Unable to fetch order data.');
            }
            while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
              $rname_query = $pdo->query("SELECT fullname FROM customer WHERE id='" . $data['customer'] . "'");
              if ($rname_query === false) {
                die('Error: Unable to fetch customer name.');
              }
              $rname = $rname_query->fetch(PDO::FETCH_ASSOC);
              $balance = $data['amount'] - $data['paid'];
              echo "<tr>
                                            <td>$data[id]</td>
                                            <td>$rname(fullname)</td>
                                            <td>$data[description]</td>
                                            <td>$data[date_received]</td>
                                            <td>$currency $data[amount]</td>
                                            <td>$currency $balance</td>
                                            <td>
                                                <a href='addpayment.php?id=$data[id]' class='btn btn-success btn-xs'>Add Payment</a>
                                                <a href='printinvoice.php?id=$data[id]' class='btn btn-warning btn-xs'>Receipt</a>
                                                <a href='orderedit.php?id=$data[id]' class='btn btn-info btn-xs'>Update</a>
                                                <a href='orderlist.php?id=$data[id]' class='btn btn-danger btn-xs'>DELETE</a>
                                            </td>
                                        </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <!-- table-responsive -->
    </div>
    <!-- panel-body -->
  </div>
  <!-- panel -->

</div>
<!-- /#page-wrapper -->

<script src="js/jquery.datatables.min.js"></script>
<script src="js/select2.min.js"></script>

<script>
  jQuery(document).ready(function () {

    "use strict";

    jQuery('#table1').dataTable();

    jQuery('#table2').dataTable({
      "sPaginationType": "full_numbers"
    });

    // Select2
    jQuery('select').select2({
      minimumResultsForSearch: -1
    });

    jQuery('select').removeClass('form-control');

    // Delete row in a table
    jQuery('.delete-row').click(function () {
      var c = confirm("Continue delete?");
      if (c)
        jQuery(this).closest('tr').fadeOut(function () {
          jQuery(this).remove();
        });

      return false;
    });

    // Show action upon row hover
    jQuery('.table-hidaction tbody tr').hover(function () {
      jQuery(this).find('.table-action-hide a').animate({
        opacity: 1
      });
    }, function () {
      jQuery(this).find('.table-action-hide a').animate({
        opacity: 0
      });
    });

  });
</script>
<?php
include ('footer.php');
?>