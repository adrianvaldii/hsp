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
            <h3>Customers</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Company ID</th>
                        <th class="text-center">Company Name</th>
                        <th class="text-center">PIC</th>
                        <th class="text-center">Address 1</th>
                        <th class="text-center">Address 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_customer as $key => $value){
                    // $truck_re = str_replace(" ", "_", $value->TRUCK_ID); 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-left"><?php echo $value->COMPANY_ID; ?></td>
                        <td class="text-left"><?php echo $value->COMPANY_NAME; ?></td>
                        <td class="text-left"><?php echo $value->PIC_NAME; ?></td>
                        <td class="text-left"><?php echo $value->ADDRESS_1; ?></td>
                        <td class="text-left"><?php echo $value->ADDRESS_2; ?></td>
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