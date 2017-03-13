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
            <h3>Location</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">Location ID</th>
                        <th class="text-center">Location Name</th>
                        <th class="text-center">Location Short Name</th>
                        <th class="text-center">Location Nation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_location as $key => $value){
                    // $truck_re = str_replace(" ", "_", $value->TRUCK_ID); 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $value->LOCATION_ID; ?></td>
                        <td class="text-left"><?php echo $value->LOCATION_NAME; ?></td>
                        <td class="text-left"><?php echo $value->LOCATION_NAME_SHORT; ?></td>
                        <td class="text-left"><?php echo $value->LOCATION_NATION; ?></td>
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