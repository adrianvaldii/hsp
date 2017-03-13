<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Work Order Cash Request</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table table-striped table-bordered" id="table-customer">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">WO Number</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_wo as $key => $value) {
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $no; ?>
                                    </td>
                                    <td class="text-center"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                    <td><?php echo $value->CUSTOMER_NAME; ?></td>
                                    <td class="text-center"><?php echo anchor('Order/entry_cash_request/'.$value->WORK_ORDER_NUMBER, 'Edit', array('class' => 'text-center')); ?></td>
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
