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

	<title>Surat Jalan Kontainer Nomor <?php echo $container_number; ?></title>

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
				<h1 class="text-center"><strong>PT. Hanoman Sakti Pratama</strong></h1>
				<h5 class="text-center header-do"><strong>International Freight Forwarding, Customs Agent, Transportation</strong></h5>
				<p class="text-center header-do">Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia</p>
				<p class="text-center header-do">Phone : +62 21 3520732; 3520733; 3520734 (ext. 3200) Fax. +62 21 3507920</p>
				<p class="text-center header-do">e-mail : hanoman@hanomansp.com</p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<h2 class="text-center"><strong>SURAT JALAN</strong></h2>
				<p class="text-left"><strong>No. <?php echo $do_number; ?></strong></p>
			</div>
		</div>
		<div class="main">
			<div class="row">
				<div class="col-xs-5">
					<table class="header-name">
						<tr>
							<td>Dikirimkan Kepada</td>
							<td>:</td>
							<td><?php echo $customer_name; ?></td>
						</tr>
						<tr>
							<td>No. POL</td>
							<td>:</td>
							<td><?php echo $nopol; ?></td>
						</tr>
					</table>
				</div>
				<div class="col-xs-5" style="margin-left: 30px;">
					<table class="header-name">
						<tr>
							<td>Lokasi Pemuatan Barang</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td>Pengemudi</td>
							<td>:</td>
							<td><?php echo $driver_name; ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<br>
		<p>Mohon diterima dengan baik barang-barang seperti tersebut dibawah ini</p>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th rowspan="3" class="text-center">No.</th>
							<th rowspan="3" class="text-center">NAMA BARANG</th>
							<th colspan="3" class="text-center">JENIS BARANG</th>
							<th rowspan="3" class="text-center">KETERANGAN</th>
						</tr>
						<tr>
							<th colspan="2" class="text-center">KONTAINER</th>
							<th rowspan="2" class="text-center">LAIN-LAIN</th>
						</tr>
						<tr>
							<th class="text-center">20 ft</th>
							<th class="text-center">40 ft</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$no = 1;
							foreach ($data_do as $key => $value) {
								?>
									<tr class="row_over">
										<td><?php echo $no; ?></td>
										<td><?php echo $value->COMMODITY_DESCRIPTION; ?></td>
										<?php
											if ($value->CONTAINER_SIZE_ID == '20') {
												?>
													<td><p style="font-size: 20px;">&#10003;</p></td>
													<td></td>
													<td></td>
												<?php
											} elseif ($value->CONTAINER_SIZE_ID == '40') {
												?>
													<td></td>
													<td><p style="font-size: 20px;">&#10003;</p></td>
													<td></td>
												<?php
											} else {
												?>
													<td></td>
													<td></td>
													<td><p style="font-size: 20px;">&#10003;</p></td>
												<?php
											}
										?>
										<td><?php echo $value->CONTAINER_SIZE_ID . " " . $value->CONTAINER_TYPE_ID; ?></td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<p>Atas penyerahan barang-barang tersebut diatas, penerima telah melakukan pemeriksaan fisik dan menyatakan bahwa kondisi barang sesuai / tidak sesuai dengan dokumen angkutan</p>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5 col-xs-offset-1">
				<p>Nomor Kontainer</p>
				<p><?php echo substr($container_number, 0,4); ?> <?php echo substr($container_number, 4,7); ?></p>
			</div>
			<div class="col-xs-4">
				<p>Nomor Segel</p>
				<?php
					if ($seal_number == "") {
						echo '<p>---- -------</p>';
					} else {
						echo substr($seal_number, 0,3); ?> <?php echo substr($seal_number, 3,6);
					}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<span style="float: left">Diserah-terimakan oleh para pihak dilokasi tujuan pada tanggal</span>
				<div class="kotak"></div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-4">
				<p class="text-center">Diserahkan oleh,</p>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="signature">&nbsp;</p>
				<p class="no-margin text-center">Nama Jelas</p>
			</div>
			<div class="col-xs-3">
				<p class="text-center">Pengangkut,</p>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="signature text-center"><strong><?php echo $driver_name; ?></strong></p>
				<p class="no-margin text-center">Cap / nama jelas</p>
			</div>
			<div class="col-xs-3">
				<p class="text-center">Diterima oleh,</p>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<p class="signature">&nbsp;</p>
				<p class="no-margin text-center">Cap / nama jelas</p>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-12 bawah">
				<p class="text-bawah">* Perubahan Isi/kondisi barang (rusak, hilang tidak sesuai, dll) dalam kemasan/kontainer bukan tanggung jawab penganggkut.</p>
				<p class="text-bawah">* Bila hasil pemeriksaan fisik luar tidak sesuai, harap segera menghubungi petugas kami dengan melampirkan data sesuai kebutuhan</p>
				<div class="garis"></div>
				<p>1: <strong>Customer</strong> (Putih) 2: <strong>Arsip</strong> (Merah) 3: <strong>Satpam</strong> (Kuning) 4: <strong>Gudang</strong> (Biru) 5: <strong>Operational</strong> (Hijau)</p>
			</div>
		</div>
	</div>
</body>
</html>