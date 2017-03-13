<?php
$nik = $this->session->userdata('nik');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- meta -->
	<!-- <meta charset="utf-8"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<title>PT. Hanoman Sakti Pratama</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">

	<!-- Sweet ALert CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sweetalert.css">

	<!-- Custom Admin CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style-admin.css">

	<!-- jQuery UI CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetimepicker.min.css">

	<!-- jQuery UI CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css">

	<!-- custom select -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2-bootstrap.css">

	<!-- DataTables CSS -->
    <link href="<?php echo base_url(); ?>assets/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="<?php echo base_url(); ?>assets/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

	<!-- Fonts -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/fonts/font-awesome/css/font-awesome.css">

	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/LogoHanoman.png">

	<style type="text/css">
		.table > thead > tr > th {
		     vertical-align: middle;
		}

		/*.table-striped tbody > tr:nth-child(odd) > td,
		.table-striped tbody > tr:nth-child(odd) > th*/

		.stripe1 {
		    background-color: #FFFFFF;
		}
		.stripe2 {
		    background-color: #f2f2f2;
		    border-bottom: solid #f2f2f2;
		}

	</style>
</head>
<body>
	<!-- navbar -->
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="<?php echo site_url('Dashboard/index'); ?>">Hanoman Sakti</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li><a href="<?php echo site_url('Dashboard/index'); ?>">Dashboard <span class="sr-only">(current)</span></a></li>
	        <?php  
	        	// $nik = $this->session->userdata('nik');
	        	// $this->db->select("*");
	        	// $this->db->from("dbo.MMENU");
	        	// $this->db->where("LINK_MENU = 0");
	        	// $this->db->where("ACTIVE = 1");
	        	// $this->db->order_by("SEQUENCE");
	        	// $data_menu = $this->db->get()->result();
	        	
	        	$data_menu = $this->db->query("SELECT * FROM HSP..MMENU_AKSES MAIN LEFT JOIN HSP..MMENU MENU ON MAIN.KD_MENU = MENU.ID_MENU WHERE MAIN.NIK = '$nik' AND MENU.LINK_MENU = '0' AND MENU.ACTIVE = '1' ORDER BY SEQUENCE")->result();

	        	foreach ($data_menu as $value) {
	        		// $this->db->select("*");
		        	// $this->db->from("dbo.MMENU");
		        	// $this->db->where("LINK_MENU", $value->ID_MENU);
		        	// $this->db->where("ACTIVE = 1");
		        	// $this->db->order_by("SEQUENCE");
		        	// $check_has_sub = $this->db->get();

	        		$check_has_sub = $this->db->query("SELECT * FROM HSP..MMENU_AKSES MAIN LEFT JOIN HSP..MMENU MENU ON MAIN.KD_MENU = MENU.ID_MENU WHERE MAIN.NIK = '$nik' AND MENU.LINK_MENU = '$value->ID_MENU' AND MENU.ACTIVE = '1' ORDER BY SEQUENCE");

		        	if ($check_has_sub->num_rows() > 0) {
		        		?>
		        		<!-- menu utama -->
		        		<li class="dropdown">
				          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $value->DESC_MENU; ?> <span class="caret"></span></a>
				        <?php
				        	// $this->db->select("*");
				        	// $this->db->from("dbo.MMENU");
				        	// $this->db->where("LINK_MENU", $value->ID_MENU);
				        	// $this->db->where("ACTIVE = 1");
				        	// $this->db->order_by("SEQUENCE");
				        	// $data_sub_menu = $this->db->get();

				        	$data_sub_menu = $this->db->query("SELECT * FROM HSP..MMENU_AKSES MAIN LEFT JOIN HSP..MMENU MENU ON MAIN.KD_MENU = MENU.ID_MENU WHERE MAIN.NIK = '$nik' AND MENU.LINK_MENU = '$value->ID_MENU' AND MENU.ACTIVE = '1' ORDER BY SEQUENCE");

				        	if ($data_sub_menu->num_rows() > 0) {
				        		echo '<ul class = "dropdown-menu">';
				        	}
				        	$group = 0;
				        	foreach ($data_sub_menu->result() as $value1) {
				        		// $this->db->select("*");
					        	// $this->db->from("dbo.MMENU");
					        	// $this->db->where("LINK_MENU", $value1->ID_MENU);
					        	// $this->db->where("ACTIVE = 1");
					        	// $this->db->order_by("SEQUENCE");
					        	// $data_sub2_menu = $this->db->get();

				        		$data_sub2_menu = $this->db->query("SELECT * FROM HSP..MMENU_AKSES MAIN LEFT JOIN HSP..MMENU MENU ON MAIN.KD_MENU = MENU.ID_MENU WHERE MAIN.NIK = '$nik' AND MENU.LINK_MENU = '$value1->ID_MENU' AND MENU.ACTIVE = '1' ORDER BY SEQUENCE");

				        		// sub menu dari menu utama
				        		if ($data_sub2_menu->num_rows() > 0) {
				        			echo "<li>";
				        			?>
				        				<a class="trigger right-caret"><?php echo $value1->DESC_MENU; ?></a>
					            		<ul class="dropdown-menu sub-menu">
				        			<?php
				        				// sub sub menu dari sub menu
				        				foreach ($data_sub2_menu->result() as $value2) {
				        					// $this->db->select("*");
								        	// $this->db->from("dbo.MMENU");
								        	// $this->db->where("LINK_MENU", $value2->ID_MENU);
								        	// $this->db->where("ACTIVE = 1");
								        	// $this->db->order_by("SEQUENCE");
								        	// $data_sub3_menu = $this->db->get();

								        	$data_sub3_menu = $this->db->query("SELECT * FROM HSP..MMENU_AKSES MAIN LEFT JOIN HSP..MMENU MENU ON MAIN.KD_MENU = MENU.ID_MENU WHERE MAIN.NIK = '$nik' AND MENU.LINK_MENU = '$value2->ID_MENU' AND MENU.ACTIVE = '1' ORDER BY SEQUENCE");

								        	if ($data_sub3_menu->num_rows() > 0) {
								        		echo "<li>";
								        		?>
								        			<a class="trigger right-caret"><?php echo $value2->DESC_MENU; ?></a>
					            					<ul class="dropdown-menu sub-menu">
								        		<?php

								        		// sub sub sub menu dari sub sub menu
								        		foreach ($data_sub3_menu->result() as $value3) {
								        			?>
						        						<li><a href="<?php echo site_url($value3->LOCATION_URL); ?>"><?php echo $value3->DESC_MENU; ?></a></li>
						        					<?php
								        		}
								        		echo "</ul>";
								        	echo "</li>";
								        	} else {
								        		?>
					        						<li><a href="<?php echo site_url($value2->LOCATION_URL); ?>"><?php echo $value2->DESC_MENU; ?></a></li>
					        					<?php
								        	}
				        				}
										echo "</ul>";
									echo "</li>";
				        		} else {
				        				if ($group != $value1->GROUP_MENU) {
				        					echo '<li role="separator" class="divider"></li>';
				        				}
				        			?>
				        				<li><a href="<?php echo site_url($value1->LOCATION_URL); ?>"><?php echo $value1->DESC_MENU; ?></a></li>
				        			<?php
				        			$group = $value1->GROUP_MENU;
				        		}
				        	}

				        	if ($data_sub_menu->num_rows() > 0) {
				        		echo '</ul>';
				        	}
				        echo '</li>';
		        	} else {
		        		?>
		        			<li><a href="<?php echo site_url($value->LOCATION_URL); ?>"><?php echo $value->DESC_MENU; ?></a></li>
		        		<?php
		        	}
	        	}
	        ?>
	      </ul>
	      <ul class="nav navbar-nav navbar-right">
	      	<li><a href="<?php echo site_url('Others/faq'); ?>">FAQ</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <!-- <li><a href="#"><i class="glyphicon glyphicon-user"></i> User Profile</a></li>
	            <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Settings</a></li>
	            <li role="separator" class="divider"></li> -->
	            <li><a href="<?php echo site_url('Dashboard/logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
	          </ul>
	        </li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>