<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Master Driver</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Driver ID</th>
                        <th class="text-center">Driver Name</th>
                        <th class="text-center">Place of Birth</th>
                        <th class="text-center">Date of Birth</th>
                        <th class="text-center">KTP Number</th>
                        <th class="text-center">SIM Number</th>
                        <th class="text-center">SIM Expired</th>
                        <th class="text-center">Phone Number</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_driver as $key => $value){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $value->DRIVER_ID; ?></td>
                        <td><?php echo $value->DRIVER_NAME; ?></td>
                        <td class="text-center"><?php echo $value->BORN_OF_LOCATION; ?></td>
                        <td class="text-center"><?php echo $value->BORN_DATE; ?></td>
                        <td class="text-center"><?php echo $value->KTP_NUMBER; ?></td>
                        <td class="text-center"><?php echo $value->LICENSE_DRIVER_ID; ?></td>
                        <td class="text-center"><?php echo $value->LICENSE_DATE; ?></td>
                        <td class="text-center"><?php echo $value->PERSONAL_PHONE_NUMBER; ?></td>
                        <td class="text-center"><?php echo ($value->FLAG == '1')?"Not Available":"Available"; ?></td>
                        <td class="text-center"><?php echo anchor('Master/edit_driver/'.$value->DRIVER_ID, 'Edit', array('class' => 'text-center')); ?></td>
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