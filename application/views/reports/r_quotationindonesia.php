<?php 
	date_default_timezone_set('Asia/Jakarta');
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

	<title>Hanoman Sakti</title>

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
	<div class="container format-print">
		<div class="row">
			<div class="col-xs-12 detail-ref">
				<h6 class="ref-no">Ref. No : <?php echo $quotation_document_number; ?></h6>
				<h6 class="tanggal">Jakarta, <?php echo $date_quotation; ?></h6>
			</div>
		</div>
		<br>
		<div class="main">
			<div class="row">
				<div class="col-xs-12">
					<table class="header-name">
						<tr>
							<td>To</td>
							<td>:</td>
							<td><?php echo $pic_company; ?></td>
						</tr>
						<tr>
							<td>Attn</td>
							<td>:</td>
							<td><span class="text-capitalize"><?php echo $pic_namdep . " " . $pic_name; ?></span></td>
						</tr>
						<tr>
							<td>Perihal</td>
							<td>:</td>
							<td class="text-capitalize">Rate Proposal for <?php echo $service; ?></td>
						</tr>
					</table>
					<br>
					<p class="text-capitalize">Kepada Yth <?php echo $pic_namdep . " " . $pic_name; ?>,</p>
					<p>Terlampir kami sampaikan penawaran (IDR) kami sebagai berikut:</p>
					<?php
						if ($count_customs > 0) {
							?>
								- &emsp; Customs Clearance via Jakarta (Seaport):
								<br>
								<br>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th rowspan="2" style="text-align:center"></th>
											<th rowspan="2" style="text-align:center">Description</th>
											
											<?php
												if ($remarks == "no") {
													echo '<th colspan="3" style="text-align:center">Jakarta</th>';
												} else {
													echo '<th colspan="2" style="text-align:center">Jakarta</th>';
												}
											?>
										</tr>
										<tr>
											<th style="text-align:center">20'</th>
											<th style="text-align:center">40'</th>
											<?php
												if ($remarks == "no") {
													echo '<th style="text-align:center">Remarks</th>';
												}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($hasil_custom_jakarta as $key => $value) {
												?>
													<tr>
														<td><?php echo $value['CUSTOM_KIND']; ?></td>
														<td><?php echo $value['CUSTOM_LINE']; ?></td>
														<td style="text-align:right"><?php echo $value['TARIF_20_OFFERING']; ?></td>
														<td style="text-align:right"><?php echo $value['TARIF_40_OFFERING']; ?></td>
														<?php
															if ($remarks == "no") {
																echo '<td style="text-align:center">per cont</td>';
															}
														?>
													</tr>
												<?php
											}
										?>
									</tbody>
								</table>
							<?php
						}
					?>
					<?php
						if ($count_trucking > 0) {
							?>
								- &emsp; Harga Trucking untuk FCL dari Pelabuhan Tanjung Priok :
								<br>
								<br>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="text-center">FROM / TO</th>
				                            <th class="text-center">DESTINATION</th>
				                            <th class="text-center">20'</th>
				                            <th class="text-center">40'</th>
				                            <?php
												if ($qty == "no") {
													echo '<th class="text-center">Qty</th>';
												}
											?>
				                            <th class="text-center">Type</th>
				                            <?php
												if ($remarks == "no") {
													echo '<th style="text-align:center">Remarks</th>';
												}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($hasil_jakarta as $key => $value) {
												?>
													<tr>
														<td><?php echo $value['FROM_NAME']; ?></td>
														<td><?php echo $value['TO_NAME']; ?></td>
														<td style="text-align:right"><?php echo $value['TARIF_20']; ?></td>
														<td style="text-align:right"><?php echo $value['TARIF_40']; ?></td>
														<?php
															if ($qty == "no") {
																?>
																	<td><?php echo $value['FROM_QTY'] . " - " . $value['TO_QTY']; ?></td>
																<?php
															}
														?>

														<td><?php echo $value['CONTAINER_TYPE_ID']; ?></td>
														
														<?php
															if ($remarks == "no") {
																?>
																	<td><?php echo $value['CALC_TYPE']; ?></td>
																<?php
															}
														?>
													</tr>
												<?php
											}
										?>
									</tbody>
								</table>
							<?php
						}
					?>
					<?php
						if ($count_weight > 0) {
							?>
								- &emsp; Harga Break Bulk (LCL) dari Pelabuhan Tanjung Priok :
								<br>
								<br>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="text-center">FROM / TO</th>
				                            <th class="text-center">DESTINATION</th>
				                            <th class="text-center">Weight (per ton)</th>
				                            <th class="text-center">Price</th>
				                            <?php
												if ($remarks == "no") {
													echo '<th style="text-align:center">Remarks</th>';
												}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($hasil_weight_jakarta as $key => $value) {
												?>
													<tr>
														<td><?php echo $value->FROM_NAME; ?></td>
														<td><?php echo $value->TO_NAME; ?></td>
														<td  class="text-center"><?php echo $value->FROM_WEIGHT . " - " . $value->TO_WEIGHT; ?></td>
														<td style="text-align:right"><?php echo currency($value->SELLING_OFFERING_RATE); ?></td>
														
														<?php
															if ($remarks == "no") {
																?>
																	<td class="text-center"><?php echo $value->MEASUREMENT_UNIT; ?></td>
																<?php
															}
														?>
													</tr>
												<?php
											}
										?>
									</tbody>
								</table>
							<?php
						}
					?>
					<?php
						if ($count_location > 0) {
							?>
								- &emsp; Harga non trucking :
								<br>
								<br>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="text-center">FROM / TO</th>
				                            <th class="text-center">DESTINATION</th>
				                            <th class="text-center">TRUCK</th>
				                            <th class="text-right">Price</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($hasil_location as $key => $value) {
												?>
													<tr>
														<td><?php echo $value->FROM_NAME; ?></td>
														<td><?php echo $value->TO_NAME; ?></td>
														<td><?php echo $value->TRUCK_NAME; ?></td>
														<td style="text-align:right"><?php echo currency($value->SELLING_OFFERING_RATE); ?></td>
													</tr>
												<?php
											}
										?>
									</tbody>
								</table>
							<?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<?php  
					foreach ($template as $key => $value) {
						echo $value->TEMPLATE_TEXT1;
					}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<p>Demikian penawaran ini kami sampaikan, sebagai tanda persetujuan mohon ditanda tangan dibawah ini kemudian dikembalikan kepada kami via email atau fax.</p>
				<p>Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-4">
				Best Regards,
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="signature"></p>
				<p class="no-margin bold"><strong>Fitria Utami</strong></p>
				<p class="no-margin">Head of Marketing</p>
				<p class="no-margin">PT Hanoman Sakti Pratama</p>
			</div>
			<div class="col-xs-3">
				Disetujui,
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="signature"></p>
				<p class="no-margin bold"><strong><?php echo $pic_name; ?></strong></p>
				<p class="no-margin text-capitalize"><?php echo $pic_jabatan; ?></p>
				<p class="no-margin"><?php echo $pic_company; ?></p>
			</div>
			<div class="col-xs-3">
				Tanggal,
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="signature"></p>
			</div>
		</div>
	</div>
</body>
</html>