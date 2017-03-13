<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');

$temp_id = $this->M_order->get_max_additional()->row()->id;
if ($temp_id != NULL) {
    $temp_id++;
    $id = $temp_id;
} else {
    $id = 160;
}

?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Entry Additional Cost</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form Entry</div>
                        <div class="panel-body">
                            <?php echo form_open(); ?>
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('failed')) { ?>
                                    <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('failed'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="form-group">
                                    <label>Container Number <span style="color:red">*</span></label>
                                    <input type="hidden" name="additional_number" value="<?php echo set_value('additional_number', $id); ?>">
                                    <select class="form-control js-example-basic-single js-states" name="container_number" id="container_number">
                                        <option <?php echo set_select('container_number', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($all_wo as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value->CONTAINER_NUMBER; ?>" <?php echo set_select('container_number', $value->CONTAINER_NUMBER, FALSE); ?> ><?php echo $value->CONTAINER_NUMBER; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cost <span style="color:red">*</span></label>
                                    <select class="form-control js-example-basic-single js-states" name="cost_id">
                                        <option <?php echo set_select('cost_id', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($data_cost as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value->COST_ID; ?>" <?php echo set_select('cost_id', $value->COST_ID, FALSE); ?> ><?php echo $value->COST_NAME . " - " . $value->COST_TYPE_ID . " - " . $value->COST_GROUP_ID; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Currency <span style="color:red">*</span></label>
                                            <select name="currency" class="form-control js-example-basic-single js-states">
                                                <option <?php echo set_select('currency', '', TRUE); ?>></option>
                                                <?php 
                                                    foreach ($data_currency as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value->CODE_CURRENCY; ?>" <?php echo set_select('currency', $value->CODE_CURRENCY, FALSE); ?> ><?php echo $value->CODE_CURRENCY; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount <span style="color:red">*</span></label>
                                            <input type="text" name="amount" id="amount" class="form-control" value="<?php echo set_value('amount', 0); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Remarks <span style="color:red">*</span></label>
                                    <textarea class="form-control" name="remarks"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
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
      $(".js-example-basic-single").select2({
        theme: "bootstrap"
      });
      $('#amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
    });

    $('#container_number').on('change',function () {
        var year = $(this).val();
        // $.ajax({
        //     url: 'url for get data',
        //     type: 'POST',
        //     data: {year: year},
        //     success: function (a) {
        //         data = JSON.parse(a);
        //         #get data and print data with each loop
        //     }
        // });
        // alert(year);
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
