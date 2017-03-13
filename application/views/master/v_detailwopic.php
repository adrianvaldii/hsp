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
            <h3>Work Order to <strong><?php echo $pic_name; ?></strong> <a target="_blank" href="<?php echo site_url('Master/print_cost_transfered/'.$this->uri->segment(3)); ?>" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Print</a> <a href="<?php echo site_url('Master/view_cost_transfered'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
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
                        <th class="text-center">Customer</th>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Last Edited By</th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_wo as $key => $value){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-left"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                        <td class="text-left"><?php echo $value->CUSTOMER_NAME; ?></td>
                        <td class="text-left"><?php echo $value->REFERENCE_NUMBER; ?></td>
                        <td class="text-left"><?php echo $value->PIC_CREATE; ?></td>
                        <td class="text-right"><?php echo currency($value->TOTAL); ?></td>
                        <td class="text-left"><?php echo anchor('Master/detail_cost_transfered/'.$pic_id.'/'.$value->WORK_ORDER_NUMBER, 'Detail', array('class' => 'text-center')); ?></td>
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