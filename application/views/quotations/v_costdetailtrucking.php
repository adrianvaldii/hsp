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
            <h3>Cost Detail</h3>
            <hr>
        </div>
	</div>
	<div class="row">
		<div class="col-md-12 fit">
			<table class="table table-striped table-bordered" id="table-container-cost-20" class="display" cellspacing="0" width="100%">
	            <thead>
	                <tr>
	                    <th>Cost Name</th>
	                    <th>Cost Type</th>
	                    <th>Cost Group</th>
	                    <th>From Qty</th>
	                    <th>To Qty</th>
	                    <th>Start Date</th>
	                    <th>End Date</th>
	                    <th>Cost Amount</th>
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                    <th colspan="7" style="text-align:right">Total:</th>
	                    <th style="text-align:right"></th>
	                </tr>
	            </tfoot>
	            <tbody>
	                <?php 
	                    $no = 1;
	                    foreach($data_cost as $key => $data){ 
	                    ?>
	                    <tr>
	                        <td><?php echo $data->COST_NAME; ?></td>
	                        <td><?php echo $data->COST_TYPE; ?></td>
	                        <td><?php echo $data->COST_GROUP; ?></td>
	                        <td><?php echo $data->FROM_QTY; ?></td>
	                        <td><?php echo $data->TO_QTY; ?></td>
	                        <td><?php echo $data->START_DATE; ?></td>
	                        <td><?php echo $data->END_DATE; ?></td>
	                        <td style="text-align: right;">
	                            <?php echo currency($data->COST_AMOUNT); ?>
	                        </td>
	                    </tr>
	                <?php } ?>
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
	$(document).ready(function(){
		// datatables size 20
        $('#table-container-cost-20').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
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
                }
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
