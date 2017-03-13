<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Vendor Truck <a href="<?php echo site_url('Master/view_all_vendor_truck'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Vendor Truck
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo form_open(); ?>
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>
                                
                                <?php if(isset($error_var) && $error_var == "error") { ?>
                                    <div class="alert alert-warning">
                                    <?php echo $error_msg; ?>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contract</label>
                                            <input type="text" name="contract_no" class="form-control" value="<?php echo $contract_no; ?>" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <input type="text" class="form-control" readonly="true" name="company_id" value="<?php echo set_value('company_id', $company_id) ?>">
                                        </div>
                                    </div>
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
                                <button type="submit" id="btns" class="btn btn-primary">Save</button>
                                <a href="<?php echo site_url('Master/view_all_vendor_truck'); ?>" class="btn btn-default">Cancel</a>
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

<!-- js -->
<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
