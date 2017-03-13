<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Services</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Service Description</th>
                        <th>Unit Measurement</th>
                        <th>Service Type</th>
                        <th class="nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($services as $key => $data){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $data->SELLING_SERVICE_ID; ?></td>
                        <td class="text-center"><?php echo $data->SERVICE_NAME; ?></td>
                        <td class="text-center"><?php echo $data->SERVICE_DESCRIPTION; ?></td>
                        <td class="text-center"><?php echo $data->UOM_NAME; ?></td>
                        <td class="text-center"><?php echo $data->SERVICE_TYPE; ?></td>
                        <td class="text-center"><?php echo anchor('Service/edit/'.$data->SELLING_SERVICE_ID,'Edit'); ?></td>
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