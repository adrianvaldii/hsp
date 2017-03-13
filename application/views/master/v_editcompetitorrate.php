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
            <h3>Master Competitor Rate</h3>
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
                                    <label>Compare <span style="color:red">*</span></label>
                                    <input type="text" name="compare_id" readonly="true" value="<?php echo $compare_id; ?>" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container Size</label>
                                            <select name="container_size_id" class="form-control" readonly="true">
                                                <option <?php echo set_select('container_size_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($container_size as $key => $value) {?>
                                                        <option <?php if($container_size_id == $value->GENERAL_ID) {echo 'selected';} ?> value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_size_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container Type</label>
                                            <select name="container_type_id" class="form-control" readonly="true">
                                                <option <?php echo set_select('container_type_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($container_type as $key => $value) {?>
                                                        <option <?php if($container_type_id == $value->GENERAL_ID) {echo 'selected';} ?> value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_type_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container Category</label>
                                            <select name="container_category_id" class="form-control" readonly="true">
                                                <option <?php echo set_select('container_category_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($container_category as $key => $value) {?>
                                                        <option <?php if($container_category_id == $value->GENERAL_ID) {echo 'selected';} ?> value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_category_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From QTY</label>
                                            <input type="number" name="from_qty" class="form-control" readonly="true" value="<?php echo set_value('from_qty', $from_qty) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To QTY</label>
                                            <input type="number" name="to_qty" class="form-control" readonly="true" value="<?php echo set_value('to_qty', $to_qty) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From Location</label>
                                            <select readonly="true" name="from_location_id" class="form-control">
                                                <option <?php echo set_select('from_location_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($data_location as $key => $value) {?>
                                                        <option <?php if($from_location_id == $value->LOCATION_ID) {echo 'selected';} ?> value="<?php echo $value->LOCATION_ID; ?>" <?php echo set_select('from_location_id', $value->LOCATION_ID, FALSE); ?>><?php echo $value->LOCATION_NAME; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To Location</label>
                                            <select readonly="true" name="to_location_id" class="form-control">
                                                <option <?php echo set_select('to_location_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($data_location as $key => $value) {?>
                                                        <option <?php if($to_location_id == $value->LOCATION_ID) {echo 'selected';} ?> value="<?php echo $value->LOCATION_ID; ?>" <?php echo set_select('to_location_id', $value->LOCATION_ID, FALSE); ?>><?php echo $value->LOCATION_NAME; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <select class="form-control" name="buying_currency">
                                                <option <?php echo set_select('buying_currency', '', TRUE); ?>></option>
                                                <option <?php if($buying_currency == "IDR") {echo 'selected';} ?> value="IDR" <?php echo set_select('buying_currency', 'IDR', FALSE); ?>>IDR</option>
                                                <option <?php if($buying_currency == "USD") {echo 'selected';} ?> value="USD" <?php echo set_select('buying_currency', 'USD', FALSE); ?>>USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="number" name="buying_rate" class="form-control" value="<?php echo set_value('buying_rate', $buying_rate) ?>">
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

      $('#start_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            onShow:function( ct ){
                this.setOptions({
                    maxDate:$('#end_date').val()?$('#end_date').val():false
                })
            }
        });

        $('#end_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            onShow:function( ct ){
                this.setOptions({
                    minDate:$('#start_date').val()?$('#start_date').val():false
                })
            }
        });

    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
