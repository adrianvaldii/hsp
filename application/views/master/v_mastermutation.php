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
            <h3>Master Mutation Bank</h3>
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
                        <th class="text-center">Transaction ID</th>
                        <th class="text-center">Bank ID</th>
                        <th class="text-center">Transaction Date</th>
                        <th class="text-center">Work Order Number</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Uploaded By</th>
                        <th class="text-center">Uploaded Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_mutation as $key => $value) {
                            ?>
                                <tr class="<?php echo ($value->IS_DONE == 'Y')?"success": "default"; ?>">
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td class="text-center"><?php echo anchor('Master/edit_mutation/'.$value->TRANSACTION_ID, $value->TRANSACTION_ID, array('class' => 'text-center')); ?></td>
                                    <td><?php echo $value->BANK_ID; ?></td>
                                    <td><?php echo $value->TRANSACTION_DATE; ?></td>
                                    <td><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                    <td><?php echo $value->DESCRIPTION_1; ?></td>
                                    <td><?php echo $value->ORIGINAL_CURRENCY; ?></td>
                                    <td><?php echo currency($value->HOME_DEBIT); ?></td>
                                    <td><?php echo currency($value->HOME_CREDIT); ?></td>
                                    <td><?php echo ($value->IS_DONE == 'Y')?"Not Available":"Available"; ?></td>
                                    <td><?php echo $value->NAME; ?></td>
                                    <td><?php echo $value->USER_DATE; ?></td>
                                </tr>
                            <?php
                            $no++;
                        }
                    ?>
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