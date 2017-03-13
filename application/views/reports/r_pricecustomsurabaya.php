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
		<div class="row">
			<div class="col-xs-12">
				<p>TRUCKING TARIF PT. HANOMAN SAKTI PRATAMA</p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" id="printheader">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th rowspan="2" style="text-align: center;">No.</th>
							<th rowspan="2" style="text-align: center;">Location</th>
							<th rowspan="2" style="text-align: center;">Custom Kind</th>
							<th rowspan="2" style="text-align: center;">Custom Line</th>
							<th colspan="4" style="text-align: center;">Selling</th>
							<th rowspan="2" style="text-align: center;">Agreed Date</th>
						</tr>
						<tr>
							<td style="text-align: center;">20'</td>
							<td style="text-align: center;">40'</td>
							<td style="text-align: center;">4H'</td>
							<td style="text-align: center;">45'</td>
						</tr>
					</thead>
					<tbody>
						<?php 
							$i = 1; 
							foreach ($hasil_custom_surabaya as $key => $data) { ?>
								<tr>
									<td style="text-align: center;"><?php echo $i++; ?></td>
									<td><?php echo $data['CUSTOM_LOCATION']; ?></td>
		                            <td><?php echo $data['CUSTOM_KIND']; ?></td>
		                            <td style="text-align: center;"><?php echo $data['CUSTOM_LINE']; ?></td>
		                            <td style="text-align: right;"><?php echo $data['TARIF_20']; ?></td>
		                            <td style="text-align: right;"><?php echo $data['TARIF_40']; ?></td>
		                            <td style="text-align: right;"><?php echo $data['TARIF_4H']; ?></td>
		                            <td style="text-align: right;"><?php echo $data['TARIF_45']; ?></td>
									<td style="text-align: right;"><?php echo $data['START_DATE']; ?></td>
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