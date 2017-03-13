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
            <h3>Operational Cost Data <a href="<?php echo site_url('Order/entry_operational_cost') ?>" class="btn btn-success">Entry Operational Cost</a></h3>
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
                        <th class="text-center">Operational Number</th>
                        <th class="text-center">Operational Date</th>
                        <th class="text-center">Work Order Number</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">PIC Name</th>
                        <th class="text-center">Amount Received</th>
                        <th class="text-center">Actual Amount</th>
                        <th class="text-center">Detail</th>
                        <th class="text-center">Approval Status</th>
                        <th class="text-center">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_pic as $key => $value) {
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $no; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $value->TRX_OPERATIONAL; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $value->OPERATIONAL_DATE; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $value->WORK_ORDER_NUMBER; ?>
                                    </td>
                                    <td class="text-left">
                                        <?php echo $value->NAME; ?>
                                    </td>
                                    <td class="text-left"><?php echo $value->PIC_NAME; ?></td>
                                    <td class="text-right"><?php echo currency($value->TOTAL_AMOUNT); ?></td>
                                    <td class="text-right"><?php echo currency($value->TOTAL_AMOUNTS); ?></td>
                                    <td class="text-center">
                                        <?php
                                           echo anchor('Order/detail_operational_cost/'.$value->TRX_OPERATIONAL.'/'.$value->WORK_ORDER_NUMBER.'/'.$value->PIC_ID, 'Detail', array('class' => 'text-center'));
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                           if ($value->STATUS == 'A') {
                                               echo "Approved";
                                           } else {
                                               echo "Waiting Approval";
                                           }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                           if ($value->STATUS == 'A') {
                                               echo "Edit";
                                           } else {
                                                echo anchor('Order/edit_operational_cost/'.$value->TRX_OPERATIONAL . '/' . $value->WORK_ORDER_NUMBER.'/'.$value->PIC_ID, 'Edit', array('class' => 'text-center'));
                                           }
                                            /* echo anchor('Order/edit_operational_cost/'.$value->TRX_OPERATIONAL . '/' . $value->WORK_ORDER_NUMBER.'/'.$value->PIC_ID, 'Edit', array('class' => 'text-center')); */
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
                responsive: true
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
