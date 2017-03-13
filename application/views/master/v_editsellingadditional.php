<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Edit Selling Service Additional Rate</h3>
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
                                    <label>Services <span style="color:red">*</span></label>
                                    <select class="form-control" readonly="true" name="selling_service_id" id="selling_service_id">
                                        <option <?php if($service_id == NULL || $service_id == "") echo 'selected' ?> <?php echo set_select('selling_service_id', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($services as $key => $value) {
                                                ?>
                                                    <option <?php if($service_id == $value->SELLING_SERVICE_ID) echo 'selected'; ?> value="<?php echo $value->SELLING_SERVICE_ID; ?>" <?php echo set_select('selling_service_id', $value->SELLING_SERVICE_ID, FALSE); ?> ><?php echo $value->SERVICE_NAME; ?></option>
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
                                                <option <?php if($currency == NULL || $currency == "") echo 'selected' ?> <?php echo set_select('currency', '', TRUE); ?>></option>
                                                <?php 
                                                    foreach ($data_currency as $key => $value) {
                                                        ?>
                                                            <option <?php if($currency == $value->CODE_CURRENCY) echo 'selected'; ?> value="<?php echo $value->CODE_CURRENCY; ?>" <?php echo set_select('currency', $value->CODE_CURRENCY, FALSE); ?> ><?php echo $value->CODE_CURRENCY; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount <span style="color:red">*</span></label>
                                            <input type="text" name="amount" id="amount" class="form-control" value="<?php echo set_value('amount', $amount); ?>">
                                        </div>
                                    </div>
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
