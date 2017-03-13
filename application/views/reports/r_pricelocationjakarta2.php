<?php 
	date_default_timezone_set('Asia/Jakarta');

	$this->load->helper('currency_helper');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- meta -->
	<!-- <meta charset="utf-8"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ERP System">
    <meta name="author" content="PT. Hanoman Sakti Pratama">

	<title>PT. Hanoman Sakti Pratama</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" media="print" type="text/css" href="<?php echo base_url(); ?>assets/css/style-print.css">
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url(); ?>assets/css/style-print2.css">

	<style type="text/css">
		table tr th {
			padding: 10px 10px;
		}
		table tr td {
			padding: 0 10px;
		}
	</style>

</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 img-logo" id="mainheader">
				<img src="<?php echo base_url(); ?>assets/images/logo.png">
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12" id="printheader">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th style="text-align: center;">No.</th>
							<th style="text-align: center;">From / To</th>
							<th style="text-align: center;">Origin / Destination</th>
							<th style="text-align: center;">Distance</th>
							<th style="text-align: center;">Distance in Litre</th>
							<th style="text-align: center;">Selling</th>
							<th style="text-align: center;">End</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$i = 1; 
							foreach ($hasil_location_jakarta as $key => $data) { ?>
								<tr>
									<td style="text-align: center;"><?php echo $i++; ?></td>
									<td><?php echo $data->FROM_NAME; ?></td>
									<td><?php echo $data->TO_NAME; ?></td>
		                            <td style="text-align: right;"><?php echo $data->DISTANCE . " Km"; ?></td>
		                            <td style="text-align: right;"><?php echo $data->DISTANCE_PER_LITRE; ?></td>
		                            <td style="text-align: right;"><?php echo currency($data->TARIFF_AMOUNT); ?></td>
		                            <td style="text-align: center;"><?php echo $data->END_DATE; ?></td>
								</tr>
							<?php }
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>