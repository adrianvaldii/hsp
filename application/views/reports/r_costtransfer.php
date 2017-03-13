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

	<title>Yang Belum PertanggungJawaban</title>

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
				<h5 class="text-center" style="font-weight:bold"><strong>PT. HANOMAN SAKTI PRATAMA</strong></h5>
				<h5 class="text-center" style="font-weight:bold"><strong>WORK ORDER YANG BELUM PERJAB</strong></h5>
				<p class="text-left" style="font-weight:bold">PIC : <?php echo $pic_name ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-bordered" id="table-service">
	                <thead>
	                    <tr>
	                        <th class="text-center">No.</th>
	                        <th class="text-center">Work Order Number</th>
	                        <th class="text-center">Customer</th>
	                        <th class="text-center">Total Amount</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php 
	                    $no = 1;
	                    foreach($data_wo as $key => $value){ 
	                    ?>
	                    <tr>
	                        <td class="text-center"><?php echo $no++; ?></td>
	                        <td class="text-left"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
	                        <td class="text-left"><?php echo $value->CUSTOMER_NAME; ?></td>
	                        <td class="text-right"><?php echo currency($value->TOTAL); ?></td>
	                    </tr>
	                    <?php } ?>
	                </tbody>
	            </table>
			</div>
		</div>
	</div>
</body>
</html>