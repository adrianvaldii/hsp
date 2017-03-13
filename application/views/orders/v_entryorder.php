<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$date = date('Y-m-d');
$date2 = "2018-01-01";
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Customer Agreement Data <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table table-striped table-bordered" id="table-customer">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Agreement Number</th>
                        <th class="text-center">Agreement Periode Start</th>
                        <th class="text-center">Agreement Periode End</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_customer as $key => $value) {
                            ?>
                                <tr class="<?php if((strtotime($date) < strtotime($value->PERIODE_START)) || (strtotime($date) > strtotime($value->PERIODE_END))) {echo 'danger';} ?>">
                                    <td class="text-center">
                                        <?php echo $no; ?>
                                        <input type="hidden" name="customer_id" value="<?php echo $value->CUSTOMER_ID; ?>">
                                    </td>
                                    <td><?php echo $value->NAME; ?></td>
                                    <td class="text-center"><?php echo $value->AGREEMENT_DOCUMENT_NUMBER; ?></td>
                                    <td class="text-center"><?php echo $value->PERIODE_START; ?></td>
                                    <td class="text-center"><?php echo $value->PERIODE_END; ?></td>
                                    <td class="text-center">
                                        <?php
                                            if ((strtotime($date) < strtotime($value->PERIODE_START)) || (strtotime($date) > strtotime($value->PERIODE_END))) {
                                                echo "Create Work Order";
                                            } else {
                                                echo anchor('Order/create_work_order/'.$value->CUSTOMER_ID.'/'.$value->QUOTATION_NUMBER.'/'.$value->AGREEMENT_NUMBER, 'Create Work Order', array('class' => 'text-center'));
                                            }
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

        // autocomplete from location
        $("#vessel").autocomplete({
          source: "<?php echo site_url('Cost/search_from_location'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=from_location_id]').val(data.item.location_id);
          }
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
