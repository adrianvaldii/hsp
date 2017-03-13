<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Master Cost Shipping</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Cost Name</th>
                        <th class="text-center">Start Date</th>
                        <th class="text-center">End Date</th>
                        <th class="text-center">Container Size</th>
                        <th class="text-center">Container Type</th>
                        <th class="text-center">Container Category</th>
                        <th class="text-center">From Qty</th>
                        <th class="text-center">To Qty</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Amount</th>
                        <!-- <th class="text-center">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_cost_shipping as $key => $value){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-left"><?php echo $value->COST_NAME; ?></td>
                        <td class="text-center"><?php echo $value->START_DATE; ?></td>
                        <td class="text-center"><?php echo $value->END_DATE; ?></td>
                        <td class="text-center"><?php echo $value->CONTAINER_SIZE_ID; ?></td>
                        <td class="text-center"><?php echo $value->CONTAINER_TYPE; ?></td>
                        <td class="text-center"><?php echo $value->CONTAINER_CATEGORY; ?></td>
                        <td class="text-center"><?php echo $value->FROM_QTY; ?></td>
                        <td class="text-center"><?php echo $value->TO_QTY; ?></td>
                        <td class="text-center"><?php echo $value->COST_CURRENCY; ?></td>
                        <td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
                        <?php /* <td class="text-center"><?php echo anchor('Master/edit_competitor_rate/'.$value->COMPARE_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$value->FROM_QTY.'/'.$value->TO_QTY.'/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID, 'Edit', array('class' => 'text-center')); ?></td> */ ?>
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

<!-- script datatables -->
<script>
    $(document).ready(function() {
        $('#table-service').DataTable({
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