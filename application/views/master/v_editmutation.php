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
            <h1>Change Status Mutation <a href="<?php echo site_url('Master/view_mutation'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Mutation
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('failed')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open('Master/edit_mutation', array('name' => 'test')); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table>
                                            <tr>
                                                <td><strong>Transaction ID</strong></td>
                                                <td style="padding: 0px 10px">:</td>
                                                <td>
                                                    <?php echo $transaction_id; ?>

                                                    <!-- input hidden -->
                                                    <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo $transaction_id; ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Transaction Date</strong></td>
                                                <td style="padding: 0px 10px">:</td>
                                                <td><?php echo $transaction_date; ?></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Work Order Number</label>
                                                                <select class="form-control js-example-basic-single js-states" id="status" name="work_order_number">
                                                                    <option <?php if ($work_order_number == NULL || $work_order_number == ""){echo "selected";} ?> ></option>
                                                                    <option <?php if ($work_order_number == "XXXX"){echo "selected";} ?> value="XXXX">XXXX</option>
                                                                    <?php 
                                                                        foreach ($data_wo as $key => $value) {
                                                                            ?>
                                                                                <option <?php if ($work_order_number == $value->WORK_ORDER_NUMBER){echo "selected";} ?> value="<?php echo $value->WORK_ORDER_NUMBER; ?>" <?php echo set_select('work_order_number', $value->WORK_ORDER_NUMBER, FALSE); ?> ><?php echo $value->WORK_ORDER_NUMBER . "  -  " . $value->WORK_ORDER_DATE . "  -  " . $value->COMPANY_NAME; ?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Mutation Status</label>
                                                                <select class="form-control" id="status" name="status">
                                                                    <option <?php if ($status == "N"){echo "selected";} ?> value="N"></option>
                                                                    <option <?php if ($status == "Y"){echo "selected";} ?> value="Y">Done</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary" style="margin-top: -10px;">Save</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table>
                                            <tr>
                                                <td><strong>Account Bank</strong></td>
                                                <td style="padding: 0px 10px">:</td>
                                                <td><?php echo $bank_id; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>PIC</strong></td>
                                                <td style="padding: 0px 10px">:</td>
                                                <td><?php echo $pic_name; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Transaction Date</th>
                                                    <th>Bank Account</th>
                                                    <th>PIC Name</th>
                                                    <th>Description</th>
                                                    <th>Currency</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($transaction_data as $key => $value) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $value->TRANSACTION_ID ?></td>
                                                                <td><?php echo $value->TRANS_DATE ?></td>
                                                                <td><?php echo $value->BANK_ID ?></td>
                                                                <td><?php echo $value->PIC_NAME ?></td>
                                                                <td><?php echo $value->DESCRIPTION_1 ?></td>
                                                                <td><?php echo $value->ORIGINAL_CURRENCY ?></td>
                                                                <td><?php echo currency($value->HOME_DEBIT) ?></td>
                                                                <td><?php echo currency($value->HOME_CREDIT) ?></td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
        $('#table-service').DataTable({
                responsive: true
        });
        $('.date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });
        $(".js-example-basic-single").select2({
            theme: "bootstrap"
        });
    });

    $('#btns').click(function (){
        // var test = $('form').serialize();
        // alert(test);
        var status = $('#status').val();
        var transaction_id = $('#transaction_id').val();
        $.ajax({
            url: "<?php echo site_url('Master/update_mutation') ?>",
            type: "POST",
            data: {status_mutation: status, mutation_id: transaction_id},
            success: function(res) {
                swal("Good job!", "You changed this status mutation!", "success");
            }
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
