<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$no = 1;

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 990)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);
?>

<!-- content -->
<div class="container-fluid font-mini">
    <div class="row">
    	<div class="col-md-10 col-md-offset-1">
    		<h3>History Approval</h3>
    		<hr>
    	</div>
    </div>
    <div class="row">
    	<div class="col-md-12">
    		<table class="table table-striped table-bordered display" id="tabel-approval" cellspacing="0" width="100%" >
    			<thead>
    				<tr>
    					<th style="text-align:center">No</th>
    					<th style="text-align:center">Document Number</th>
    					<th style="text-align:center">Revision Number</th>
    					<th style="text-align:center">Document</th>
    					<!-- <th style="text-align:center">Description</th> -->
    					<th style="text-align:center">Request Approval</th>
    					<th style="text-align:center">Status</th>
    					<!-- <th style="text-align:center">View</th> -->
    				</tr>
    			</thead>
    			<tbody>
    				<?php
    					foreach ($data_approval as $key => $value) {
    						?>
    							<tr>
			    					<td style="text-align:center"> 
			    						<?php echo $no; ?> 
			    					</td>
			    					<td style="text-align:center"> <?php echo $value->TRANSACTION_NUMBER; ?> </td>
			    					<td style="text-align:center"> <?php echo $value->REVISION_NUMBER; ?> </td>
			    					<td style="text-align:center"> <?php echo $value->DOCUMENT_NAME; ?> </td>
			    					<?php
                                        /*
                                            <td style="text-align:center"> <?php echo $value->COMPANY_NAME; ?> </td>
                                        */
                                    ?>
			    					<td style="text-align:center"> <?php echo $value->REQUEST_APPROVAL_DATE; ?> </td>
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
		$('#tabel-approval').DataTable({
                responsive: true,
        });
	});
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
