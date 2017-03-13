<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 990)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);

$attributes_app = array(
    'width'     =>  '400',
    'height'    =>  '400',
    'screenx'   =>  '\'+((parseInt(screen.width) - 400)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 450)/2)+\'',
);
?>

<!-- content -->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<h3>Entry Agreement</h3>
			<hr>
		</div>
	</div>
    <div class="row">
		<div class="col-md-10 col-md-offset-1">
			<!-- table approval -->
			<table class="table table-striped table-bordered display" id="tabel-quotation" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th style="text-align:center">No.</th>
						<th style="text-align:center">Customer</th>
						<th style="text-align:center">Quotation Number</th>
						<th style="text-align:center">Revision</th>
						<th style="text-align:center">Date</th>
						<!-- <th>Edit</th>
						<th>Need Approval</th> --><!-- 
						<th class="nosort" style="text-align:center">Print</th> -->
						<th class="nosort" style="text-align:center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no = 1;
						foreach ($data_quotation as $value) {
							?>
								<tr>
									<td style="text-align:center"><?php echo $no; ?></td>
									<td><?php echo $value->COMPANY_NAME; ?></td>
									<td style="text-align:center"><?php echo $value->QUOTATION_DOCUMENT_NUMBER; ?></td>
									<td style="text-align:center"><?php echo $value->REVESION_NUMBER; ?></td>
									<td style="text-align:center"><?php echo $value->QUOTATION_DATE; ?></td>
									<td style="text-align:center">
										<?php
											echo anchor('Quotation/create_agreement/'.$value->QUOTATION_NUMBER,'Entry Agreement', array('class' => 'text-center'));
										?>
									</td>
									<?php
										/* 
										<td style="text-align:center">
											<?php 
												if ($value->APPROVAL_STATUS != 'A') {
													echo anchor_popup('Quotation/edit_quotation/'.$value->QUOTATION_NUMBER, 'Edit', $attributes);
												} else {
													echo 'Edit';
												}
											?>
										</td>
										<td style="text-align:center">
											<?php 
												if ($value->APPROVAL_STATUS != 'A') {
													echo anchor_popup('Quotation/need_approval/'.$value->QUOTATION_NUMBER, 'Need Approval', $attributes_app);
												} else {
													echo 'Need Approval';
												}
											?>
										</td>
										*/
									?>
								</tr>
							<?php
							$no++;
						}
					?>
				</tbody>
			</table>
		</div>    	
    </div>
</div>
<!-- end of content -->

<!-- js -->
<?php
    $this->load->view('layouts/js.php');  
?>

<script type="text/javascript">
	$(document).ready(function ()
	{
		$('#tabel-quotation').DataTable({
                responsive: true,
	            'aoColumnDefs': [{
	                'bSortable': false,
	                'aTargets': ['nosort']
	            }]
        });
	});
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
