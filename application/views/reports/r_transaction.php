<?php 
	date_default_timezone_set('Asia/Jakarta');
	$this->load->helper('currency_helper');

	$totals = 0;
	foreach ($result_detail as $key => $value) {
		$totals += $value['TOTAL_AMOUNT'];
	}
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

	<title>DAFTAR REQUEST ADVANCE OPERASIONAL</title>

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
		body {
			font-family: 'Arial Black';
			font-size: 10px;
		}
	</style>

</head>
<body>
	<div class="container format-print font_mini">
		<div class="row">
			<div class="col-xs-12">
				<h6 class="text-left" style="font-weight:bold"><strong>DAFTAR REQUEST ADVANCE OPERASIONAL</strong></h6>
				<span style="font-size: 12px;">Transaction Number : <?php echo $trx_number; ?></span>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2" class="text-center">TGL</th>
							<th rowspan="2" class="text-center">Work Order</th>
							<th rowspan="2" class="text-center">JENIS</th>
							<th rowspan="2" class="text-center">CUSTOMER</th>
							<th rowspan="2" class="text-center">CONT.</th>
							<th rowspan="2" class="text-center">BL</th>
							<th colspan="4" class="text-center">TANGGAL</th>
							<th rowspan="2" class="text-center">ACTIVITY</th>
							<th rowspan="2" class="text-center">CS</th>
							<th rowspan="2" class="text-center">AMOUNT</th>
							<th rowspan="2" class="text-center">PIC</th>
						</tr>
						<tr>
							<th class="text-center">ETA</th>
							<th class="text-center">ETD</th>
							<th class="text-center">RENC. TRANS.</th>
							<th class="text-center">REAL TRANSFER</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="13" class="text-right">TOTAL</th>
							<th class="text-right"><?php echo currency($totals); ?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php
							foreach ($result_detail as $key => $value) {
								?>
									<tr>
										<td><?php echo $value['WO_DATE']; ?></td>
										<td><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
										<td><?php echo $value['TRADE_ID']; ?></td>
										<td><?php echo $value['COMPANY_NAME']; ?></td>
										<td><?php echo $value['CONTAINER_NUMBER']; ?></td>
										<td><?php echo $value['BL_NUMBER']; ?></td>
										<td><?php echo $value['ETA_DATE']; ?></td>
										<td><?php echo $value['ETD_DATE']; ?></td>
										<td><?php echo $value['TRANS_DATE']; ?></td>
										<td></td>
										<td>
											<?php echo $value['COST_GROUP']; ?>
										</td>
										<td><?php echo substr($value['NIK_NAME'], 0, strpos($value['NIK_NAME'], ' ')); ?></td>
										<td class="text-right"><?php echo currency($value['TOTAL_AMOUNT']); ?></td>
										<td><?php echo substr($value['PIC_NAME'], 0, strpos($value['PIC_NAME'], ' ')) . " - " . $value['CUSTOMS_LOCATION']; ?></td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<p style="font-style: italic">Printed by system on <?php echo date("F j, Y, g:i a"); ?></p>
	</div>
</body>
</html>


<!-- template lama -->
<?php
/*
<?php 
	date_default_timezone_set('Asia/Jakarta');
	$this->load->helper('currency_helper');
	$grand_total = $total_reim + $total_nonreim;
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

	<title>DAFTAR REQUEST ADVANCE OPERASIONAL</title>

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
		body {
			font-family: 'Arial Black';
			font-size: 10px;
		}
	</style>

</head>
<body>
	<div class="container format-print font_mini">
		<div class="row">
			<div class="col-xs-12">
				<h6 class="text-left" style="font-weight:bold"><strong>DAFTAR REQUEST ADVANCE OPERASIONAL</strong></h6>
				<span style="font-size: 12px;">Transaction Number : <?php echo $trx_number; ?></span>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2" class="text-center">TGL</th>
							<th rowspan="2" class="text-center">Work Order</th>
							<th rowspan="2" class="text-center">JENIS</th>
							<th rowspan="2" class="text-center">CUSTOMER</th>
							<th colspan="2" class="text-center">CONT.</th>
							<th rowspan="2" class="text-center">BL</th>
							<th colspan="4" class="text-center">TANGGAL</th>
							<th rowspan="2" class="text-center">ACTIVITY</th>
							<th rowspan="2" class="text-center">CS</th>
							<th rowspan="2" class="text-center">AMOUNT</th>
							<th rowspan="2" class="text-center">PIC</th>
						</tr>
						<tr>
							<th class="text-center">Detail</th>
							<th class="text-center">NO</th>
							<th class="text-center">ETA</th>
							<th class="text-center">ETD</th>
							<th class="text-center">RENC. TRANS.</th>
							<th class="text-center">REAL TRANSFER</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="13" class="text-right">TOTAL</th>
							<th class="text-right"><?php echo currency($total_amount); ?></th>
							<th></th>
						</tr>
					</tfoot>
					<tbody>
						<?php
							foreach ($data_transaction as $key => $value) {
								?>
									<tr>
										<td><?php echo $value->WO_DATE; ?></td>
										<td><?php echo $value->WORK_ORDER_NUMBER; ?></td>
										<td><?php echo $value->TRADE_ID; ?></td>
										<td><?php echo $value->COMPANY_NAME; ?></td>
										<td><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID; ?></td>
										<td><?php echo $value->CONTAINER_NUMBER; ?></td>
										<td><?php echo $value->BL_NUMBER; ?></td>
										<td><?php echo $value->ETA_DATE; ?></td>
										<td><?php echo $value->ETD_DATE; ?></td>
										<td><?php echo $value->TRANS_DATE; ?></td>
										<td></td>
										<td>
											<?php echo $value->COST_GROUP; ?>
											<br>
											( <?php echo $value->COST_NAME; ?> )
										</td>
										<td><?php echo substr($value->NIK_NAME, 0, strpos($value->NIK_NAME, ' ')); ?></td>
										<td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
										<td><?php echo substr($value->PIC_NAME, 0, strpos($value->PIC_NAME, ' ')) . " - " . $value->CUSTOMS_LOCATION; ?></td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<p style="font-style: italic">Printed by system on <?php echo date("F j, Y, g:i a"); ?></p>
	</div>
</body>
</html>
*/
?>