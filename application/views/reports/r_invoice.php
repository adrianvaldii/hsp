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

	<title>Invoice <?php echo $invoice_number; ?></title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" media="print" type="text/css" href="<?php echo base_url(); ?>assets/css/style-print.css">
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url(); ?>assets/css/style-print2.css">

	<style>
		body {
			font-family: 'Arial Black';
		}
		.table-detail {
			text-align: left;
		}
		.table-data thead th {
			text-align: center;
			border: 1px solid #333;
			padding: 2px 0px;
		}
		.table-data tfoot td {
			text-align: left;
			border: 1px solid #333;
			padding: 2px 0px 2px 5px;
			font-size: 11px;
			font-weight: bold;
			font-style: italic;
		}

		@page {
			margin-top: 6cm;
			margin-bottom: 5cm;
			margin-left: -0.4cm;
			margin-right: -0.4cm;
		}

		.minggir {
			margin-left: 17px;
		}

	</style>

</head>
<body>
	<div class="container format-print">
		<div class="minggir">
			<p style="font-weight: bold">Messrs : </p>
			<div style="float: left; width: 350px;">
				<?php echo $customer_name; ?>
				<br>
				<?php echo $customer_address; ?>
			</div>
			<div style="float: right; width: 300px;">
				<p style="padding-left: 150px;">Date <span style="margin: 0px 10px;">:</span> <span><?php echo $invoice_date; ?></span></p>
				<p style="padding-left: 158px;">Ref <span style="margin: 0px 10px;">:</span> <span><?php echo $ref_no; ?></span></p>
			</div>
		</div>
		<div style="clear: both; margin: 0pt; padding: 0pt;"></div>
		<p style="text-align: center; font-weight: bold; margin-bottom: -5px;">INVOICE</p>
		<div style="border: 1px solid black; margin-top: 1px;"></div>
		<div style="margin-bottom: 10px;"></div>
		<div class="col-xs-5">
			<table class="table-detail">
				<tr>
					<td>VES/VOY</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $vessel_name . "/" . $voyage_number; ?></td>
				</tr>
				<tr>
					<td>Party/Vol</td>
					<td style="padding: 0px 10px;">:</td>
					<td>
						<?php
							echo $result_vol;
						?>
					</td>
				</tr>
				<tr>
					<td>M BL / H BL</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $data_bl; ?></td>
				</tr>
				<tr>
					<td>SHIPPER</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $shipper; ?></td>
				</tr>
				<tr>
					<td>CONSIGNEE</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $customer_name; ?></td>
				</tr>
			</table>
		</div>
		<div class="col-xs-5">
			<table class="table-detail">
				<tr>
					<td>AJU No</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $pib_no; ?></td>
				</tr>
				<tr>
					<td>INV No.</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $invoice_number; ?></td>
				</tr>
				<tr>
					<td>WO No</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $wo_data; ?></td>
				</tr>
				<tr>
					<td>ETA</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo $eta; ?></td>
				</tr>
				<tr>
					<td>SHIPMENT</td>
					<td style="padding: 0px 10px;">:</td>
					<td><?php echo "-"; ?></td>
				</tr>
			</table>
		</div>
		<div style="clear: both; margin: 0pt; padding: 0pt;"></div>
		<div style="margin-bottom: 5px"></div>
		<div>
			<table class="table table-data">
				<thead>
					<tr>
						<th class="text-center">DESCRIPTION</th>
						<th class="text-center">AMOUNT</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td style="padding-left: 17px;">Rp &emsp; <?php echo ucwords($spell_total); ?></td>
						<td>Rp &emsp;&emsp;&emsp;&emsp;<span style="text-align: right"><?php echo currency($total_inv); ?></span></td>
					</tr>
					<tr>
						<td colspan="2" style="font-transform: italic;padding-left: 17px;">Note : The Cost Incurred during the withdrawal of container will we claim back</td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td style="padding-left: 17px;">
							<p style="font-weight: bold;">REIMBURSEMENT :</p>
							<table class="table" id="no-border">
								<?php
									foreach ($data_rem as $key => $value) {
										?>
											<tr>
												<td>-</td>
												<td><?php echo ucwords(strtolower($value->COST_NAME)); ?>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
												<td><?php echo (($value->CHARGES_CURRENCY == "IDR")? "Rp": "$"); ?></td>
												<td style="text-align: right"><?php echo currency($value->AMOUNT); ?></td>
												<td>&nbsp;</td>
											</tr>
										<?php
									}
								?>
								<br>
								<tr>
									<td style="font-weight: bold;" colspan="2">TOTAL</td>
									<td style="font-weight: bold;">Rp</td>
									<td style="text-align: right;font-weight: bold;"><?php echo currency($total_rem); ?></td>
									<td>&nbsp;</td>
								</tr>
							</table>
							<p style="font-weight: bold;">HSP CHARGES :</p>
							<table class="table" id="no-border">
								<?php
									foreach ($data_crg as $key => $value) {
										?>
											<tr>
												<td>-</td>
												<td><?php echo ucwords(strtolower($value->SERVICE_NAME)); ?></td>
												<td><?php echo (($value->CHARGES_CURRENCY == "IDR")? "Rp": "$"); ?></td>
												<td style="text-align: right"><?php echo currency($value->AMOUNT); ?></td>
												<td>&nbsp;</td>
											</tr>
										<?php
									}
								?>
								<br>
								<tr>
									<td style="font-weight: bold;" colspan="2">TOTAL</td>
									<td style="font-weight: bold;">Rp</td>
									<td style="text-align: right;font-weight: bold;"><?php echo currency($total_crg); ?></td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<p style="margin-left: 500px; font-weight:bold;">Yours Faithfully,</p>
		<br>
		<br>
		<br>
		<p style="margin-left: 505px; font-weight:bold;">Arif Nasiruddin</p>
	</div>
</body>
</html>