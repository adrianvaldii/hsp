<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Master Vendor Contract</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Contract Number</th>
                        <th class="text-center">Contract Date</th>
                        <th class="text-center">Company Name</th>
                        <th class="text-center">Vendor Name</th>
                        <th class="text-center">Vendor Kind</th>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Vendor PIC</th>
                        <th class="text-center">Valid From Date</th>
                        <th class="text-center">Valid To Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_vendor_contract as $key => $value){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $value->CONTRACT_NO; ?></td>
                        <td class="text-center"><?php echo $value->CONTRACT_DATE; ?></td>
                        <td class="text-left"><?php echo $value->COMPANY_NAME; ?></td>
                        <td class="text-left"><?php echo $value->VENDOR_NAME; ?></td>
                        <td class="text-center"><?php echo $value->VENDOR_KIND; ?></td>
                        <td class="text-center"><?php echo $value->REFERENCE_NUMBER; ?></td>
                        <td class="text-center"><?php echo $value->VENDOR_PIC; ?></td>
                        <td class="text-center"><?php echo $value->FROM_DATE; ?></td>
                        <td class="text-center"><?php echo $value->TO_DATE; ?></td>
                        <td class="text-center"><?php echo anchor('Master/edit_vendor_contract/'.$value->CONTRACT_NO, 'Edit', array('class' => 'text-center')); ?></td>
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