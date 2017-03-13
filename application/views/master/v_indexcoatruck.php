<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Master GL Cost Truck</h3>
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
                        <th class="text-center">Cost Name</th>
                        <th class="text-center">Account Code</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_coa_truck as $key => $value){
                    $truck_re = str_replace(" ", "_", $value->TRUCK_ID); 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $value->TRUCK_ID; ?></td>
                        <td class="text-left"><?php echo $value->COST_NAME; ?></td>
                        <td class="text-center"><?php echo $value->ACCOUNT_CODE; ?></td>
                        <td class="text-center"><?php echo anchor('Master/edit_coa_truck/'.$truck_re.'/'.$value->COST_ID, 'Edit', array('class' => 'text-center')); ?></td>
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