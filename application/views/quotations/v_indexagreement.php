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
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Agreement Data</h3>
            <hr>
        </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<!-- table approval -->
			<table class="table table-striped table-bordered display" id="tabel-quotation" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th style="text-align:center">No.</th>
						<th style="text-align:center">Customer</th>
						<th style="text-align:center">Agreement Number</th>
						<th style="text-align:center">Amendment Number</th>
						<th style="text-align:center">Date</th>
						<th style="text-align:center">Quotation Number</th>
						<th style="text-align:center">Approval Status</th>
						<th style="text-align:center">Action</th>
						<!-- <th>Edit</th>
						<th>Need Approval</th> --><!-- 
						<th class="nosort" style="text-align:center">Print</th> -->
						<th class="nosort" style="text-align:center">View</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no = 1;
						foreach ($data_agreement as $value) {
							?>
								<tr>
									<td style="text-align:center"><?php echo $no; ?></td>
									<td><?php echo $value->COMPANY_NAME; ?></td>
									<td style="text-align:center"><?php echo $value->AGREEMENT_DOCUMENT_NUMBER; ?></td>
									<td style="text-align:center"><?php echo $value->AMENDMENT_NUMBER; ?></td>
									<td style="text-align:center"><?php echo $value->AGREEMENT_DATE; ?></td>
									<td style="text-align:center"><?php echo $value->QUOTATION_DOCUMENT_NUMBER; ?></td>
									<td style="text-align:center">
										<?php
											if ($value->APPROVAL_STATUS == "A") {
												echo "Approved";
											} elseif ($value->APPROVAL_STATUS == "W") {
												echo "Waiting";
											} elseif ($value->APPROVAL_STATUS == "N") {
												echo "New";
											} elseif ($value->APPROVAL_STATUS == "R") {
                                                echo "Rejected";
                                            } 
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
									<?php
										/*<td style="text-align:center">
											<?php
												if ($value->APPROVAL_STATUS == "A") {
													echo anchor('Quotation/amendment_agreement/'.$value->AGREEMENT_NUMBER.'/'.$value->QUOTATION_NUMBER, 'Amendment', array('class' => 'text-center'));
												} elseif ($value->APPROVAL_STATUS == "W") {
													echo "Amendment";
												} elseif ($value->APPROVAL_STATUS == "N") {
													echo "Amendment";
												} elseif ($value->APPROVAL_STATUS == "R") {
													echo "Amendment";
												}
											?>
										</td>*/
									?>
									<td style="text-align:center">
										<?php
											if ($value->APPROVAL_STATUS == "A") {
												echo anchor('Quotation/amendment_agreement/'.$value->AGREEMENT_NUMBER.'/'.$value->QUOTATION_NUMBER, 'Amendment', array('class' => 'text-center'));
											} elseif ($value->APPROVAL_STATUS == "W") {
												echo "Amendment";
											} elseif ($value->APPROVAL_STATUS == "N") {
												echo "Amendment";
											} elseif ($value->APPROVAL_STATUS == "R") {
												echo "Amendment";
											}
										?>
									</td>
									<!--<td>Amendment</td>-->
									<td style="text-align:center">
										<?php 
											echo anchor('Quotation/agreement_detail/'.$value->AGREEMENT_NUMBER . '/' . $value->QUOTATION_NUMBER,'Detail', array('class' => 'text-center'));
										?>
									</td>
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
