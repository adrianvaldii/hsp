<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');

$this->load->helper('currency_helper');

?>

<!-- content -->
<div class="container-fluid font_mini">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
            <h3>Competitor Detail</h3>
            <hr>
        </div>
	</div>
	<div class="row">
		<div class="col-md-12 fit">
			<table class="table table-striped table-bordered" id="table-container-cost-20" class="display" cellspacing="0" width="100%">
	            <thead>
	                <tr>
	                    <th class="nosort">Competitor Name</th>
	                    <th class="nosort">Container Detail</th>
	                    <th class="nosort">From / To</th>
	                    <th class="nosort">Destination</th>
	                    <th>Buying Rate</th>
	                </tr>
	            </thead>
	            <tbody>
	                <?php 
	                    $no = 1;
	                    foreach($data_vendor as $key => $data){ 
	                    ?>
	                    <tr>
	                        <td><?php echo $data->COMPETITOR_NAME; ?></td>
	                        <td><?php echo $data->CONTAINER_SIZE_ID . " - " . $data->CONTAINER_TYPE_ID . " - " . $data->CONTAINER_CATEGORY_ID; ?></td>
	                        <td><?php echo $data->FROM_NAME; ?></td>
	                        <td><?php echo $data->TO_NAME; ?></td>
	                        <td style="text-align: right;"><?php echo $data->BUYING_CURRENCY . " " . currency($data->BUYING_RATE); ?></td>
	                    </tr>
	                <?php } ?>
	            </tbody>
	        </table>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-4">
			<table class="table borderless">
				<tr>
					<td>Floor Price</td>
					<td>:</td>
					<td><?php echo $currency . " " . currency($floor_price); ?></td>
				</tr>
				<br>
				<tr>
					<td>Market Price</td>
					<td>:</td>
					<td><?php echo $currency . " " . currency($market_price); ?></td>
				</tr>
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
	$(document).ready(function(){
		// datatables size 20
        $('#table-container-cost-20').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 4, "desc" ]]
        });
	});
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
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
