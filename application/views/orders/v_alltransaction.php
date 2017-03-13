<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper')
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Transaction Data <a href="<?php echo site_url('Order/transfer_cost') ?>" class="btn btn-success">Create Voucher Customs and Trucking</a> <a href="<?php echo site_url('Order/transfer_do') ?>" class="btn btn-primary">Create Voucher DO</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            <table class="table table-striped table-bordered display" id="table-customer" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Transaction Number</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Work Order Number</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Group</th>
                        <th class="text-center">Voucher Number</th>
                        <th class="text-center">Entry By</th>
                        <th class="text-center">Receiver</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_transaction as $key => $value) {
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $no; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $value['TRX_NUMBER']; ?>
                                    </td>
                                    <td class="text-right"><?php echo $value['TRX_DATE']; ?></td>
                                    <td class="text-left"><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
                                    <td class="text-left"><?php echo $value['CUSTOMER_NAME']; ?></td>
                                    <td class="text-left"><?php echo $value['COST_GROUP']; ?></td>
                                    <td class="text-center"><?php echo $value['VOUCHER_NUMBER']; ?></td>
                                    <td class="text-left"><?php echo $value['PIC_NAME']; ?></td>
                                    <td class="text-left"><?php echo $value['RECEIVER']; ?></td>
                                    <td class="text-right"><?php echo $value['CURRENCY'] . " " . currency($value['TOTAL_AMOUNT']); ?></td>
                                    <td class="text-center">
                                        <?php
                                           echo anchor('Order/detail_transaction/'.$value['TRX_NUMBER'], 'Detail', array('class' => 'text-center'));
                                        ?>
                                    </td>
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
<script type="text/javascript">
    $(document).ready(function() {
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

        $('#table-customer').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 1, "desc" ]]
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
