<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Master Truck</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Truck Number</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">STNK Expired</th>
                        <th class="text-center">BPKB Number</th>
                        <th class="text-center">KIR Number</th>
                        <th class="text-center">KIR Expired</th>
                        <th class="text-center">Share Operation Cost</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_truck as $key => $value){ 
                        $truck_re = str_replace(' ', '_', $value->TRUCK_ID);
                    ?>
                    <tr class="<?php echo ($value->FLAG == '0')?"": "warning"; ?>">
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $value->TRUCK_ID; ?></td>
                        <td class="text-left"><?php echo $value->COMPANY_NAME; ?></td>
                        <td class="text-center"><?php echo $value->STNK_EXPIRED; ?></td>
                        <td class="text-center"><?php echo $value->BPKB_NUMBER; ?></td>
                        <td class="text-center"><?php echo $value->KIR_NUMBER; ?></td>
                        <td class="text-center"><?php echo $value->KIR_EXPIRED; ?></td>
                        <td class="text-center"><?php echo $value->SHARE_OPERATION_COST . " %"; ?></td>
                        <td class="text-center"><?php echo ($value->FLAG == '0')?"Available": "Not Available"; ?></td>
                        <td class="text-center"><?php echo anchor('Master/edit_truck/'.$truck_re, 'Edit', array('class' => 'text-center')); ?></td>
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