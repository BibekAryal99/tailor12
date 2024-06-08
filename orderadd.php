<?php
require_once ('function.php');
dbconnect();
session_start();

if (!is_user()) {
	redirect('index.php');
}

?>

<?php
$user = $_SESSION['username'];
$usid = $pdo->query("SELECT id FROM users WHERE username='" . $user . "'");
if ($usid === false) {
	die('Error: Unable to fetch user ID.');
}
$usid = $usid->fetch(PDO::FETCH_ASSOC);
$uid = $usid['id'];
include ('header.php');
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Add Order</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">

		<div class="col-md-10 col-md-offset-1">

			<?php

			if ($_POST) {

				// Check if 'received_by' key exists in $_POST array
				if (isset($_POST['received_by'])) {
					$received_by = $_POST["received_by"];
				} else {
					$received_by = ''; // or any default value you want
				}

				$customer = $_POST["customer"];
				$desc = $_POST["desc"];
				$date_received = $_POST["date_received"];
				$completed = $_POST["completed"];
				$date_collected = $_POST["date_collected"];
				$amount = $_POST["amount"];
				$paid = $_POST["paid"];

				$name = $pdo->query("SELECT fullname FROM customer WHERE id='" . $customer . "'");
				if ($name === false) {
					die('Error: Unable to fetch customer name.');
				}
				$nameResult = $name->fetch(PDO::FETCH_ASSOC);
				$name = $nameResult ? $nameResult['fullname'] : ''; // Get fullname or set to empty string if fetch failed
			

				if ($completed == 'No') {
					$color = '#a00000';
				} else {
					$color = '#00a014';
				}

				$res = $pdo->exec("INSERT INTO `order`(`customer`, `description`, `amount`, `paid`, `received_by`, `date_received`, `completed`, `date_collected`) VALUES ('$customer','$desc','$amount','$paid','$received_by','$date_received','$completed','$date_collected')");
				if ($res === false) {
					die('Error: Unable to add order.');
				}
				$cid = $pdo->lastInsertId();
				$res2 = $pdo->exec("INSERT INTO `calendar`(`title`, `description`, `start`, `end`, `allDay`, `color`, `url`, `category`, `user_id`) VALUES ('$name','$desc','$date_received','$date_collected','true','$color','../orderedit.php?id=$cid','Orders','$uid')");
				if ($res2 === false) {
					die('Error: Unable to add entry to calendar.');
				}
				echo "<div class='alert alert-success alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>  
                        Order Added Successfully!
                    </div>";
			}
			?>


			<script>
				$(function () {
					$("#datepicker").datepicker();
				});
			</script>




			<form action="orderadd.php" method="post">

				<div class="form-group">
					<label>Select Customer</label>
					<select name="customer" class="form-control">
						<option value="0">Please Select a Customer</option>
						<?php

						$ddaa = $pdo->query("SELECT id, fullname FROM customer ORDER BY id");
						if ($ddaa === false) {
							die('Error: Unable to fetch customer list.');
						}
						while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
							if (isset($_GET['id']) && $data['id'] == $_GET['id']) {
								echo "<option value='$data[id]' selected='selected'>$data[fullname]</option>";
							} else {
								echo "<option value='$data[id]'>$data[fullname]</option>";
							}
						}
						?>

					</select><br />

				</div>

				<div class="form-group">
					<label>Description</label><br />
					<input type="text" name="desc" style="width:200px; height: 40px;" /><br /><br />
				</div>

				<div class="form-group">
					<label>Date Received</label><br />
					<input type="date" name="date_received" style="width:200px; height: 40px;" /><br /><br />
				</div>

				<div class="form-group">
					<label>Received By</label>
					<select name="received_by" class="form-control">
						<?php

						$ddaa = $pdo->query("SELECT id, fullname FROM staff ORDER BY id");
						if ($ddaa === false) {
							die('Error: Unable to fetch staff list.');
						}
						while ($data = $ddaa->fetch(PDO::FETCH_ASSOC)) {
							echo "<option value='$data[id]'>$data[fullname]</option>";
						}
						?>

					</select><br />
				</div>

				<div class="form-group">
					<label>Amount</label><br />
					<?php echo ($currency); ?> <input type="text" name="amount"
						style="width:200px; height: 40px;" /><br /><br />
				</div>

				<div class="form-group">
					<label>Paid</label><br />
					<?php echo ($currency); ?> <input type="text" name="paid"
						style="width:200px; height: 40px;" /><br /><br />
				</div>

				<div class="form-group">
					<label>Completed?</label><br />
					<select name="completed" class="form-control">
						<option value='No'>No</option>
						<option value='Yes'>Yes</option>
					</select><br /><br />
				</div>

				<div class="form-group">
					<label>Date to Collect</label><br />
					<input type="date" name="date_collected" style="width:200px; height: 40px;" /><br /><br />
				</div>

				<input type="submit" class="btn btn-lg btn-success btn-block" value="ADD">
			</form>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script src="js/bootstrap-timepicker.min.js"></script>

<script>
	jQuery(document).ready(function () {

		jQuery("#ssn").mask("999-99-9999");

		// Time Picker
		jQuery('#timepicker').timepicker({
			defaultTIme: false
		});
		jQuery('#timepicker2').timepicker({
			showMeridian: false
		});
		jQuery('#timepicker3').timepicker({
			minuteStep: 15
		});

	});
</script>

<?php
include ('footer.php');
?>