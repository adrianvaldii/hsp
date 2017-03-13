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

	<title>Rincian Biaya Operasional</title>

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
		}
	</style>

</head>
<body>
	<div class="container format-print">
		<div class="row">
			<div class="col-xs-12">
				<h4 class="text-left"><strong>PT. HANOMAN SAKTI PRATAMA</strong></h4>
				<h5 class="text-center" style="font-weight:bold"><strong>Rincian Biaya Operasional</strong></h5>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<table>
					<tr>
	                    <td><strong>Operational Number</strong></td>
	                    <td style="padding: 0px 1px;">:</td>
	                    <td><?php echo $this->uri->segment(3); ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Customer</strong></td>
	                    <td style="padding: 10px 1px;">:</td>
	                    <td><?php echo $customer_name ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Work Order Number</strong></td>
	                    <td style="padding: 0px 1px;">:</td>
	                    <td><?php echo $work_order_number ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Pelaksana</strong></td>
	                    <td style="padding: 10px 1px;">:</td>
	                    <td><?php echo $pic_name; ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Tanggal Pelaksanaan</strong></td>
	                    <td style="padding: 0px 1px;">:</td>
	                    <td><?php echo $opr_date ?></td>
	                </tr>
	            </table>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<h5 style="font-weight: bold;"><strong>I. REIMBURSEMENT</strong></h5>
				<table class="table table-bordered" style="margin-left: 20px;">
					<?php
						if (count($data_reimbursement) > 0) {
							?>
								<thead>
									<tr>
										<th class="text-center">Nomor</th>
										<th>Nama Biaya</th>
										<th class="text-center">Mata Uang</th>
										<th class="text-right">Biaya</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
				                        <th class="text-center" colspan="2">Total:</th>
				                        <th class="text-center">IDR</th>
				                        <th class="text-right"><?php echo currency($total_reim); ?></th>
				                    </tr>
								</tfoot>
								<tbody>
									<?php
										if (count($data_reimbursement) > 0) {
											$no_rem = 1;
											foreach ($data_reimbursement as $key => $value) {
												?>
													<tr>
														<td class="text-center"><?php echo $no_rem . "."; ?></td>
														<td><?php echo $value->COST_NAME; ?></td>
														<td class="text-center"><?php echo $value->COST_CURRENCY; ?></td>
														<td class="text-right"><?php echo currency($value->COST_ACTUAL_AMOUNT); ?></td>
													</tr>
												<?php
												$no_rem++;
											}
										} else {
											?>
												<p>Tidak Ada Data Reimbursement.</p>
											<?php
										}
									?>
								</tbody>
							<?php
						} else {
							?>
								<thead>
									<tr>
										<th class="text-center">Nomor</th>
										<th>Nama Biaya</th>
										<th class="text-center">Mata Uang</th>
										<th class="text-right">Biaya</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
				                        <th colspan="2">Total:</th>
				                        <th class="text-center">IDR</th>
				                        <th class="text-right"><?php echo currency($total_reim); ?></th>
				                    </tr>
								</tfoot>
								<tbody>
									<tr>
										<td class="text-center"></td>
										<td></td>
										<td class="text-center"></td>
										<td class="text-right"></td>
									</tr>
								</tbody>
							<?php
						}
					?>
				</table>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<h5 style="font-weight: bold;"><strong>II. NON REIMBURSEMENT</strong></h5>
				<table class="table table-bordered" style="margin-left: 20px;">
					<?php
						if (count($data_nonreim) > 0) {
							?>
								<thead>
									<tr>
										<th class="text-center">Nomor</th>
										<th>Nama Biaya</th>
										<th class="text-center">Mata Uang</th>
										<th class="text-right">Biaya</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
				                        <th class="text-center" colspan="2">Total:</th>
				                        <th class="text-center">IDR</th>
				                        <th class="text-right"><?php echo currency($total_nonreim); ?></th>
				                    </tr>
								</tfoot>
								<tbody>
									<?php
										if (count($data_nonreim) > 0) {
											$no_nonreim = 1;
											foreach ($data_nonreim as $key => $value) {
												?>
													<tr>
														<td class="text-center"><?php echo $no_nonreim . "."; ?></td>
														<td><?php echo $value->COST_NAME; ?></td>
														<td class="text-center"><?php echo $value->COST_CURRENCY; ?></td>
														<td class="text-right"><?php echo currency($value->COST_ACTUAL_AMOUNT); ?></td>
													</tr>
												<?php
												$no_nonreim++;
											}
										} else {
											?>
												<p>Tidak Ada Data Non Reimbursement.</p>
											<?php
										}
									?>
								</tbody>
							<?php
						} else {
							?>
								<thead>
									<tr>
										<th class="text-center">Nomor</th>
										<th>Nama Biaya</th>
										<th class="text-center">Mata Uang</th>
										<th class="text-right">Biaya</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
				                        <th colspan="2">Total:</th>
				                        <th class="text-center">IDR</th>
				                        <th class="text-right"><?php echo currency($total_nonreim); ?></th>
				                    </tr>
								</tfoot>
								<tbody>
									<tr>
										<td class="text-center"></td>
										<td></td>
										<td class="text-center"></td>
										<td class="text-right"></td>
									</tr>
								</tbody>
							<?php
						}
					?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<h5 style="font-weight: bold;"><strong>III. JAMINAN</strong></h5>
				<div class="row">
					<div class="col-xs-5">
						<p class="text-left" style="font-weight: bold;"><strong>&nbsp;</strong></p>
					</div>
					<div class="col-xs-3">
						<p style="padding-left:63px;"><strong>IDR/USD</strong></p>
					</div>
					<div class="col-xs-2" style="margin-left: 15px">
						<p class="text-center" style="text-align:right"><strong>..........................</strong></p>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-5">
				<p class="text-left" style="font-weight: bold;"><strong>Grand Total</strong></p>
			</div>
			<div class="col-xs-3">
				<p style="font-weight: bold;padding-left:63px;"><strong>IDR</strong></p>
			</div>
			<div class="col-xs-2" style="margin-left: 15px">
				<p class="text-center" style="font-weight: bold;text-align:right"><strong><?php echo currency($grand_total); ?></strong></p>
			</div>
		</div>
		<br>
		<br>
		<br>
		<div class="row">
			<div class="col-xs-5">
				<p>Dibuat Oleh,</p>
				<p><?php echo $pic_name . " : " . $opr_date2; ?></p>
			</div>
			<?php
				/*
					<div class="col-xs-5" style="margin-left: 50px;">
						<p>Menyetujui,</p>
						<p><?php echo $approval_name . " : " . $approval_date; ?></p>
					</div>
				*/
			?>
		</div>
		<?php
			/*
				<div class="row">
					<div class="col-xs-12">
						<p style="font-size: 8px;font-style:italic;">*) Approval Sudah Dilakukan Oleh System.</p>
					</div>
				</div>
			*/
		?>
	</div>
</body>
</html>