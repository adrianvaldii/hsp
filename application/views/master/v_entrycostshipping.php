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
            <h3>Entry Cost Shipping</h3>
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
                                    <label>Cost <span style="color:red">*</span></label>
                                    <select class="form-control js-example-basic-single js-states" name="cost_id" id="cost_id">
                                        <option <?php echo set_select('cost_id', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($data_cost as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value->COST_ID; ?>" <?php echo set_select('cost_id', $value->COST_ID, FALSE); ?> ><?php echo $value->COST_ID . " - " . $value->COST_NAME . " - " . $value->COST_KINDS; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container Size</label>
                                            <select name="container_size_id" class="form-control">
                                                <option <?php echo set_select('container_size_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($container_size as $key => $value) {?>
                                                        <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_size_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container Type</label>
                                            <select name="container_type_id" class="form-control">
                                                <option <?php echo set_select('container_type_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($container_type as $key => $value) {?>
                                                        <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_type_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Container Category</label>
                                            <select name="container_category_id" class="form-control">
                                                <option <?php echo set_select('container_category_id', '', TRUE); ?>></option>
                                                <?php  
                                                    foreach ($container_category as $key => $value) {?>
                                                        <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_category_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
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
                                            <input type="number" name="from_qty" class="form-control" value="<?php echo set_value('from_qty') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To QTY</label>
                                            <input type="number" name="to_qty" class="form-control" value="<?php echo set_value('to_qty') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="text" name="start_date" class="form-control" id="start_date" value="<?php echo set_value('start_date') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input type="text" name="end_date" class="form-control" id="end_date" value="<?php echo set_value('end_date') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Calculation Type</label>
                                            <select class="form-control" name="calc_type">
                                                <option <?php echo set_select('calc_type', '', TRUE); ?> ></option>
                                                <?php 
                                                    foreach ($data_calc as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('calc_type', $value->GENERAL_ID, FALSE); ?> ><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Increment QTY</label>
                                            <input type="number" name="increment_qty" class="form-control" value="<?php echo set_value('increment_qty') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <select class="form-control" name="cost_currency">
                                                <option <?php echo set_select('cost_currency', '', TRUE); ?>></option>
                                                 <option value="IDR" <?php echo set_select('cost_currency', 'IDR', FALSE); ?>>IDR</option>
                                                            <option value="USD" <?php echo set_select('cost_currency', 'USD', FALSE); ?>>USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="number" name="cost_amount" class="form-control" value="<?php echo set_value('cost_amount') ?>">
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
