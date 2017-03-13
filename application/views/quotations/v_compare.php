<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');

$this->load->helper('currency_helper');

?>

<!-- content -->
<div class="container-fluid font_mini">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
            <h3>Market Data</h3>
            <hr>
        </div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<table class="table borderless">
				<tr>
					<td>Floor Price</td>
					<td>:</td>
					<td><?php echo $currency . " " . currency($floor_price); ?></td>
				</tr>
				<br>
				<tr>
					<td>Market Price</td>
					<td>:</td>
					<td><?php echo $currency . " " . currency($market_price); ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<!-- end of content -->

<!-- js -->
<?php
    $this->load->view('layouts/js.php');  
?>

<?php
    $this->load->view('layouts/footer.php');
?>
