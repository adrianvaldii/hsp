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
            <h3>Cost Transfered to <strong><?php echo $pic_name; ?></strong> From Work Order <strong><?php echo $work_order_number; ?></strong> <a href="<?php echo site_url('Master/detail_wo_pic/'.$pic_id); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Work Order Number</th>
                        <th class="text-center">Container Number</th>
                        <th class="text-center">Cost Name</th>
                        <th class="text-center">Cost Type</th>
                        <th class="text-center">Cost Group</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Received By</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Received Date</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_cost as $key => $value){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-left"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                        <td class="text-left"><?php echo $value->CONTAINER_NUMBER; ?></td>
                        <td class="text-left"><?php echo $value->COST_NAME; ?></td>
                        <td class="text-left"><?php echo $value->COST_TYPE; ?></td>
                        <td class="text-left"><?php echo $value->COST_GROUP; ?></td>
                        <td class="text-left"><?php echo $value->PIC_CREATE; ?></td>
                        <td class="text-left"><?php echo $value->PIC_RECEIVED; ?></td>
                        <td class="text-left"><?php echo $value->REQUEST_DATE; ?></td>
                        <td class="text-left"><?php echo $value->TRANSFER_DATE; ?></td>
                        <td class="text-right"><?php echo currency($value->COST_RECEIVED_AMOUNT); ?></td>
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