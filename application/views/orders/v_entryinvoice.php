<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');

$date_inv = date('Y-m-d');
?>

<!-- content -->
<div class="container-fluid font_mini">
	 <div class="row">
		  <div class="col-md-10 col-md-offset-1">
				<h3>Entry Invoice</h3>
				<hr>
		  </div>
	 </div>
	 <div class="row">
		  <div class="col-md-10 col-md-offset-1">
				<div class="row">
					 <div class="col-md-12">
						  <?php echo form_open('Order/create_invoice', array('method' => 'post')); ?>
							  <?php if(isset($error_var) && $error_var == "error") { ?>
								  <div class="alert alert-danger">
								  <?php echo $error_cash; ?>
								  </div>
							  <?php } ?>

							  <?php if(isset($error_customer_var) && $error_customer_var == "error") { ?>
								  <div class="alert alert-danger">
								  <?php echo $error_customer; ?>
								  </div>
							  <?php } ?>

							  <?php if(isset($error_vessel_var) && $error_vessel_var == "error") { ?>
								  <div class="alert alert-danger">
								  <?php echo $error_vessel; ?>
								  </div>
							  <?php } ?>

							  <?php if(isset($error_voyage_var) && $error_voyage_var == "error") { ?>
								  <div class="alert alert-danger">
								  <?php echo $error_voyage; ?>
								  </div>
							  <?php } ?>

							  	<?php if(validation_errors()) { ?>
		                            <div class="alert alert-danger">
		                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                            <?php echo validation_errors(); ?>
		                            </div>
		                       	<?php } ?>

								<?php if($this->session->flashdata('failed')) { ?>
		                            <div class="alert alert-warning">
		                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                            <?php echo $this->session->flashdata('failed'); ?>
		                            </div>
		                        <?php } ?>

		                        <?php if($this->session->flashdata('success')) { ?>
		                            <div class="alert alert-success">
		                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                            <?php echo $this->session->flashdata('success'); ?>
		                            </div>
		                        <?php } ?>

								<div class="row">
									 <div class="col-md-5">
										  <table>
												<tr>
													 <td><strong>Invoice Number</strong></td>
													 <td style="padding: 0px 10px">:</td>
													 <td>
														  <?php echo $invoice_number; ?>
														  <input type="hidden" value="<?php echo $invoice_number; ?>" name="invoice_number">
														  </td>
												</tr>
												<tr>
													 <td><strong>Work Order Number</strong></td>
													 <td style="padding: 10px 10px">:</td>
													 <td>
														<?php 
															echo $work_order_number; 
															$loop_wo = 0;
															foreach ($data_wo as $value) {
																?>
																	<input type="hidden" value="<?php echo $value; ?>" name="work_order[]">
																<?php
																$loop_wo++;
															}
														?>

														<?php
															$loop1 = 0;
															foreach ($data_main as $value) {
																?>
																	<!-- input hidden -->		
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][work_order_number]" value="<?php echo $value['WORK_ORDER_NUMBER']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][container_number]" value="<?php echo $value['CONTAINER_NUMBER']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][kind]" value="<?php echo $value['KIND']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][type]" value="<?php echo $value['TYPE']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][description]" value="<?php echo $value['DESCRIPTION']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][currency]" value="<?php echo $value['CURRENCY']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][amount]" value="<?php echo $value['AMOUNT']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][description_type]" value="<?php echo $value['DESCRIPTION_TYPE']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][ch_kind]" value="<?php echo $value['CH_KIND']; ?>" />
																	<input type="hidden" name="invoi[<?php echo $loop1; ?>][ch_type]" value="<?php echo $value['CH_TYPE']; ?>" />
																<?php
																$loop1++;
															}
														?>  
													 </td>
												</tr>
												<tr>
													 <td><strong>Customer</strong></td>
													 <td style="padding: 0px 10px">:</td>
													 <td><?php echo $customer_name; ?></td>
												</tr>
												<tr>
													 <td><strong>Shipper</strong></td>
													 <td style="padding: 10px 10px">:</td>
													 <td><?php echo $shipper; ?></td>
												</tr>
												<tr>
													 <td><strong>Invoice Date</strong></td>
													 <td style="padding: 0px 10px">:</td>
													 <td>
													 	<input type="text" name="invoice_date" class="form-control" id="date" value="<?php echo $date_inv; ?>">
													 </td>
												</tr>
										  </table>
									 </div>
									 <div class="col-md-5">
										  <table>
												<tr>
													 <td><strong>Vessel</strong></td>
													 <td style="padding: 0px 10px">:</td>
													 <td><?php echo $vessel_name . " - " . $voyage_number; ?></td>
												</tr>
												<tr>
													 <td><strong>Shipment</strong></td>
													 <td style="padding: 10px 10px">:</td>
													 <td><?php echo $pol_name . " - " . $pod_name; ?></td>
												</tr>
												<tr>
													 <td><strong>ETD/ETA</strong></td>
													 <td style="padding: 0px 10px">:</td>
													 <td><?php echo $etd . " - " . $eta; ?></td>
												</tr>
												<tr>
													 <td><strong>Consignee</strong></td>
													 <td style="padding: 10px 10px">:</td>
													 <td><?php echo $consignee; ?></td>
												</tr>
										  </table>
										  <?php
											if ($error_var == "error" || $error_customer_var == "error") {
											  ?>
												<button type="submit" disabled class="btn btn-primary">Save</button>
											  <?php
											} else {
											  ?>
												<button type="submit" class="btn btn-primary">Save</button>
											  <?php
											}
										  ?>
										  <a href="<?php echo site_url('Order/view_entry_invoice'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
									 </div>
								</div>
						  </form>
					 </div>
				</div>
				<hr>
				<div class="row">
				  <div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Invoice Data</div>
						<div class="panel-body">
						   	<div class="table-responsive">
						   		<table class="table table-striped table-bordered" id="invoice-data">
								  <thead>
									   <tr>
									   		<th>Work Order Number</th>
											<th>Container Number</th>
											<th>Description</th>
											<th>Type</th>
											<th>Kind</th>
											<th>Currency</th>
											<th>Amount</th>
									   </tr>
								  </thead>
									<tfoot>
									  <tr>
										  <th colspan="6" style="text-align:right">Total:</th>
										  <th style="text-align:right"></th>
									  </tr>
									</tfoot>
								  <tbody>
									   <?php
									   		$loop = 0;
										  foreach ($data_main as $value) {
											?>
												<tr>
													<td>
														<?php echo $value['WORK_ORDER_NUMBER']; ?>
														<!-- input hidden -->		
														<input type="hidden" name="inv[<?php echo $loop; ?>][work_order_number]" value="<?php echo $value['WORK_ORDER_NUMBER']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][container_number]" value="<?php echo $value['CONTAINER_NUMBER']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][kind]" value="<?php echo $value['KIND']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][type]" value="<?php echo $value['TYPE']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][description]" value="<?php echo $value['DESCRIPTION']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][currency]" value="<?php echo $value['CURRENCY']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][amount]" value="<?php echo $value['AMOUNT']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][description_type]" value="<?php echo $value['DESCRIPTION_TYPE']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][ch_kind]" value="<?php echo $value['CH_KIND']; ?>" />
														<input type="hidden" name="inv[<?php echo $loop; ?>][ch_type]" value="<?php echo $value['CH_TYPE']; ?>" />
													</td>
													<td><?php echo $value['CONTAINER_NUMBER']; ?></td>
													<td><?php echo $value['KIND']; ?></td>
													<td><?php echo $value['TYPE']; ?></td>
													<td><?php echo $value['DESCRIPTION']; ?></td>
													<td><?php echo $value['CURRENCY']; ?></td>
													<td class="text-right"><?php echo currency($value['AMOUNT']); ?></td>
											   </tr>
											<?php
											$loop++;
										  }
									   ?>
								  </tbody>
								</table>
						   	</div>
						</div>
					 </div>
				  </div>
				</div>
				<div class="row">
					 <div class="col-md-12">
						  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							 <div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingTwo">
								  <h4 class="panel-title">
									 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										Selling Data
									 </a>
								  </h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								  <div class="panel-body">
										<table class="table table-striped table-bordered" id="table-selling">
											<thead>
												 <tr>
												 	<th>Work Order Number</th>
													<th>Container Number</th>
													<th>Kind</th>
													<th>Currency</th>
													<th>Amount</th>
												 </tr>
											</thead>
											<tfoot>
											  <tr>
												  <th colspan="4" style="text-align:right">Total:</th>
												  <th style="text-align:right"></th>
											  </tr>
											</tfoot>
											<tbody>
												 <?php
													foreach ($data_selling as $value) {
													  ?>
														<tr>
														  <td><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
														  <td><?php echo $value['CONTAINER_NUMBER']; ?></td>
														  <td><?php echo $value['KIND']; ?></td>
														  <td><?php echo $value['CURRENCY']; ?></td>
														  <td class="text-right"><?php echo currency($value['AMOUNT']); ?></td>
														 </tr>
													  <?php
													}
												 ?>
											</tbody>
									  	</table>
								  </div>
								</div>
							 </div>
							 <div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingThree">
								  <h4 class="panel-title">
									 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										Cost Data
									 </a>
								  </h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								  <div class="panel-body">
								  <?php
								  	/*
									<form>
										<table class="table table-striped table-bordered" id="table-cost">
											<thead>
												 <tr>
												 	<th>Work Order Number</th>
													<th>Container Number</th>
													<th>Cost Name</th>
													<th>Cost Group</th>
													<th>Cost Type</th>
													<th>Cost Kind</th>
													<th>Currency</th>
													<th>Amount</th>
													<th>Invoice Amount</th>
													<th>Actual Amount</th>
													<th>Transfered Status</th>
													<th>Finished Status</th>
												 </tr>
											</thead>
											<tfoot>
											  <tr>
												  <th colspan="7" style="text-align:right">Total:</th>
												  <th style="text-align:right"></th>
												  <th style="text-align:right"></th>
												  <th style="text-align:right"></th>
												  <th colspan="2"></th>
											  </tr>
											</tfoot>
											<tbody>
											  <?php
												foreach ($data_cost as $key => $value) {
												  ?>
													<tr>
														<td><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
														<td><?php echo $value['CONTAINER_NUMBER']; ?></td>
														<td><?php echo $value['COST_NAME']; ?></td>
														<td><?php echo $value['COST_GROUP']; ?></td>
														<td><?php echo $value['COST_TYPE']; ?></td>
														<td>
															<?php
															  if ($value['COST_KIND'] == 'S') {
																echo "STANDART COST";
															  } elseif($value['COST_KIND'] == 'A') {
																echo "ADDITIONAL COST";
															  }
															?>
														</td>
														<td><?php echo $value['COST_CURRENCY']; ?></td>
														<td class="text-right"><?php echo currency($value['COST_RECEIVED_AMOUNT']); ?></td>
														<td class="text-right"><?php echo currency($value['COST_INVOICE_AMOUNT']); ?></td>
														<td class="text-right"><?php echo currency($value['COST_ACTUAL_AMOUNT']); ?></td>
														<td>
															<?php
															  if ($value['IS_TRANSFERED'] == 'Y') {
																echo "TRANSFERED";
															  } elseif($value['IS_TRANSFERED'] == 'N') {
																echo "WAITING";
															  }
															?>
														</td>
														<td>
															<?php
															  if ($value['IS_DONE'] == 'Y') {
																echo "FINISHED";
															  } elseif($value['IS_DONE'] == 'N') {
																echo "WAITING";
															  }
															?>
														</td>
												   </tr>
												  <?php
												}
											  ?>
											</tbody>
										</table>
									</form>
									*/
									?>
										<div class="table-responsive">
											<table class="table table-striped table-bordered" id="table-cost">
												<thead>
													 <tr>
													 	<th>Work Order Number</th>
														<th>Container Number</th>
														<th>Cost Name</th>
														<th>Cost Group</th>
														<th>Cost Type</th>
														<th>Cost Kind</th>
														<th>Currency</th>
														<th>Amount</th>
														<!-- <th>Invoice Amount</th> -->
														<th>Actual Amount</th>
														<th>Transfered Status</th>
														<th>Finished Status</th>
													 </tr>
												</thead>
												<tfoot>
												  <tr>
													  <th colspan="7" style="text-align:right">Total:</th>
													  <th style="text-align:right"></th>
													  <!-- <th style="text-align:right"></th> -->
													  <th style="text-align:right"></th>
													  <th colspan="2"></th>
												  </tr>
												</tfoot>
												<tbody>
												  <?php
													foreach ($data_cost as $key => $value) {
													  ?>
														<tr>
															<td><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
															<td><?php echo $value['CONTAINER_NUMBER']; ?></td>
															<td><?php echo $value['COST_NAME']; ?></td>
															<td><?php echo $value['COST_GROUP']; ?></td>
															<td><?php echo $value['COST_TYPE']; ?></td>
															<td>
																<?php
																  if ($value['COST_KIND'] == 'S') {
																	echo "STANDART COST";
																  } elseif($value['COST_KIND'] == 'A') {
																	echo "ADDITIONAL COST";
																  }
																?>
															</td>
															<td><?php echo $value['COST_CURRENCY']; ?></td>
															<td class="text-right"><?php echo currency($value['COST_RECEIVED_AMOUNT']); ?></td>
															<?php /* <td class="text-right"><?php echo currency($value['COST_INVOICE_AMOUNT']); ?></td> */ ?>
															<td class="text-right"><?php echo currency($value['COST_ACTUAL_AMOUNT']); ?></td>
															<td>
																<?php
																  if ($value['IS_TRANSFERED'] == 'Y') {
																	echo "TRANSFERED";
																  } elseif($value['IS_TRANSFERED'] == 'N') {
																	echo "WAITING";
																  }
																?>
															</td>
															<td>
																<?php
																  if ($value['IS_DONE'] == 'Y') {
																	echo "FINISHED";
																  } elseif($value['IS_DONE'] == 'N') {
																	echo "WAITING";
																  }
																?>
															</td>
													   </tr>
													  <?php
													}
												  ?>
												</tbody>
											</table>
										</div>
								  </div>
								</div>
							 </div>
						  </div>
					 </div>
				</div>
		  </div>
	 </div>
</div>
<!-- end of content -->

<!-- js -->
<?php
	 $this->load->view('layouts/js.php');  
?>

<!-- script datatables -->
<script type="text/javascript">
	// convert rupiah
	function toRp(angka){
		var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
		var rev2    = '';
		for(var i = 0; i < rev.length; i++){
			rev2  += rev[i];
			if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
				rev2 += ',';
			}
		}
		return rev2.split('').reverse().join('');
	}
	 $(document).ready(function() {
		$(".js-example-basic-single").select2({
		  theme: "bootstrap"
		});
		$('#amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
		$('#invoice_amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});

		a = "<?php echo $wo_date; ?>";

		 $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

		$('#invoice-data').DataTable({
			"order": [[ 1, "asc" ]],
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				// Total over all pages
				total = api
					.column( 6 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
				pageTotal = api
					.column( 6, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column( 6 ).footer() ).html(toRp(total));
			}
		});

		$('#table-selling').DataTable({
			responsive: true,
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				// Total over all pages
				total = api
					.column( 4 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
				pageTotal = api
					.column( 4, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column( 4 ).footer() ).html(toRp(total));
			}
		});

		$('#table-cost').DataTable({
			responsive: true,
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				// Total over all pages
				total = api
					.column( 7 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
				pageTotal = api
					.column( 7, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column( 7 ).footer() ).html(toRp(total));

				// Total over all pages
				total2 = api
					.column( 8 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
				pageTotal2 = api
					.column( 8, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column( 8 ).footer() ).html(toRp(total2));

				// // Total over all pages
				// total3 = api
				// 	.column( 9 )
				// 	.data()
				// 	.reduce( function (a, b) {
				// 		return intVal(a) + intVal(b);
				// 	}, 0 );
	 
				// // Total over this page
				// pageTotal3 = api
				// 	.column( 9, { page: 'current'} )
				// 	.data()
				// 	.reduce( function (a, b) {
				// 		return intVal(a) + intVal(b);
				// 	}, 0 );
	 
				// // Update footer
				// $( api.column( 9 ).footer() ).html(toRp(total3));
			}
		});
	 });

	 $('#container_number').on('change',function () {
		  var year = $(this).val();
		  // $.ajax({
		  //     url: 'url for get data',
		  //     type: 'POST',
		  //     data: {year: year},
		  //     success: function (a) {
		  //         data = JSON.parse(a);
		  //         #get data and print data with each loop
		  //     }
		  // });
		  alert(year);
	 });
</script>


<?php
	 $this->load->view('layouts/footer.php');
?>
