<?php 
	date_default_timezone_set('Asia/Jakarta');
	$this->load->helper('currency_helper');
	$grand_total = $total_reim + $total_nonreim;
	$date = date('d F Y')
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

	<title>Deklarasi</title>

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

		@page {
			sheet-size: 420mm 100mm;
		}
	</style>

</head>
<body>
	<div class="container format-print">
		<div class="row">
			<div class="col-xs-12">
				<h4 class="text-left"><strong>PT. HANOMAN SAKTI PRATAMA</strong></h4>
				<h5 class="text-center" style="font-weight:bold"><strong>DEKLARASI</strong></h5>
				<h5 class="text-center" style="font-weight:bold"><strong>PENGELUARAN NO RECEIPT</strong></h5>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<table>
					<tr>
	                    <td><strong>Operational Number</strong></td>
	                    <td style="padding: 0px 1px;">:</td>
	                    <td><?php echo $trx_operational; ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Work Order Number</strong></td>
	                    <td style="padding: 4px 1px;">:</td>
	                    <td><?php echo $work_order_number; ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Nama</strong></td>
	                    <td style="padding: 0px 1px;">:</td>
	                    <td><?php echo $pic_name; ?></td>
	                </tr>
	                <tr>
	                    <td><strong>NIK</strong></td>
	                    <td style="padding: 4px 1px;">:</td>
	                    <td><?php echo $pic_id; ?></td>
	                </tr>
	                <tr>
	                    <td><strong>Date</strong></td>
	                    <td style="padding: 0px 1px;">:</td>
	                    <td><?php echo $date; ?></td>
	                </tr>
	            </table>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<p>Telah membayar/mengeluarkan uang sejumlah : Rp <span style="font-style: italic;font-weight:bold"><?php echo currency($total); ?></span> dengan rincian sebagai berikut :</p>
				<ol>
					<?php
						foreach ($data_nonreim as $value) {
							?>
								<li><?php echo $value['CONTAINER_NUMBER'] . " - " . $value['COST_NAME'] . " - " . ($value['COST_CURRENCY']?'Rp':'$') . " " . currency($value['COST_ACTUAL_AMOUNT']) ?></li>
							<?php
						}
					?>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<p>Demikian deklarasi ini saya buat dengan sebenarnya</p>
				<p>Jakarta, <?php echo $date; ?></p>
			</div>
		</div>
		<br>
		<div class="row" style="padding-left: 40px;">
			<div class="col-xs-3">
				<p style="text-align:center;">Dibuat oleh,</p>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="no-margin bold" style="text-align:center;">( <?php echo $pic_name; ?> )</p>
			</div>
			<div class="col-xs-3">
				<p style="text-align:center;">Mengetahui,</p>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="no-margin bold" style="text-align:center;">( .................................. )</p>
			</div>
			<div class="col-xs-3">
				<p style="text-align:center;">Menyetujui,</p>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="no-margin bold" style="text-align:center;">( .................................. )</p>
			</div>
		</div>
	</div>
</body>
</html>