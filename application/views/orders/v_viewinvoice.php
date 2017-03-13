<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Invoice data</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

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
                        <th class="text-center">Invoice Number</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">Invoice Date</th>
                        <th class="text-center">Total Non Reimbursement</th>
                        <th class="text-center">Total Reimbursement</th>
                        <th class="text-center">Total Invoice</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_invoice as $key => $value) {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td class="text-center"><?php echo $value->INVOICE_NUMBER; ?></td>
                                    <td class="text-left"><?php echo $value->COMPANY_NAME; ?></td>
                                    <td class="text-center"><?php echo $value->INVOICE_DATE; ?></td>
                                    <td class="text-right"><?php echo currency($value->TOTAL_NON_REIMBURSEMENT); ?></td>
                                    <td class="text-right"><?php echo currency($value->TOTAL_REIMBURSEMENT); ?></td>
                                    <td class="text-right"><?php echo currency($value->TOTAL_INVOICE); ?></td>
                                    <td class="text-center">
                                        <?php
                                            echo anchor('Order/detail_invoice/'.$value->INVOICE_NUMBER, 'Detail', array('class' => 'text-center'));
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
                "order": [[ 1, "asc" ]]
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
