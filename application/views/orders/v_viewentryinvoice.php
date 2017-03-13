<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Work Order Data <span style="font-size: 15px;">(Invoice)</span></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?php echo form_open('Order/entry_invoice'); ?>

                <?php if($this->session->flashdata('failed')) { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('failed'); ?>
                    </div>
                <?php } ?>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center nosort">
                                <input type="checkbox" name="select-all" id="select-all" />
                            </th>
                            <th class="text-center">Work Order Number</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Trade</th>
                            <th class="text-center">Vessel</th>
                            <th class="text-center nosort">Qty</th>
                            <!-- <th class="text-center nosort">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach ($result_wo as $key => $value) {
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="wo_check[]" value="<?php echo $value['WORK_ORDER_NUMBER']; ?>" />
                                        </td>
                                        <td class="text-center"><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
                                        <td><?php echo $value['CUSTOMER_NAME']; ?></td>
                                        <td class="text-center"><?php echo $value['WORK_ORDER_DATE']; ?></td>
                                        <td class="text-center"><?php echo $value['TRADE']; ?></td>
                                        <td class="text-center"><?php echo $value['VESSEL_NAME']; ?></td>
                                        <td class="text-center">
                                            <?php
                                                if ($value['TOTAL_20'] > 0) {
                                                    echo $value['TOTAL_20'] . " x " . "20";
                                                    echo "<br>";
                                                }
                                                if ($value['TOTAL_40'] > 0) {
                                                    echo $value['TOTAL_40'] . " x " . "40";
                                                    echo "<br>";
                                                }
                                                if ($value['TOTAL_4H'] > 0) {
                                                    echo $value['TOTAL_4H'] . " x " . "4H";
                                                    echo "<br>";
                                                }
                                                if ($value['TOTAL_45'] > 0) {
                                                    echo $value['TOTAL_45'] . " x " . "45";
                                                    echo "<br>";
                                                }
                                            ?>
                                        </td>
                                        <?php
                                            /*
                                                <td class="text-center"><?php echo anchor('Order/entry_invoice/'.$value['WORK_ORDER_NUMBER'], 'Entry Invoice', array('class' => 'text-center')); ?></td>
                                            */
                                        ?>
                                    </tr>
                                <?php
                                $no++;
                            }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary" id="btn-inv"><span class="glyphicon glyphicon-pencil"></span> Create Invoice</button>
            </form>
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
        $('#btn-inv').attr('disabled', 'disabled');
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

        // autocomplete from location
        $("#vessel").autocomplete({
          source: "<?php echo site_url('Cost/search_from_location'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=from_location_id]').val(data.item.location_id);
          }
        });

        var ckbox = $('input[type=checkbox]');

        $('input').on('click',function () {
            if (ckbox.is(':checked')) {
                $('#btn-inv').removeAttr('disabled');
            } else {
                $('#btn-inv').attr('disabled', 'disabled');
            }
        });
    });

    if ($('input[type=checkbox]').is(':checked')) {
       alert('ada yang di pilih');
    }

    $('#select-all').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;                        
            });
        } else {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = false;                        
            });
        }
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
