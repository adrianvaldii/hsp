<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Vendor Contract <a href="<?php echo site_url('Master/view_all_vendor_contract'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Vendor Contract
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
                                            <label>Contract Number</label>
                                            <input type="text" name="contract_no" readonly="true" class="form-control" value="<?php echo set_value('contract_no', $id); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <input type="text" name="company_id" readonly="true" style='text-transform:uppercase' class="form-control" value="<?php echo set_value('company_id', $company_id); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Vendor ID</label>
                                            <select class="form-control" name="vendor_id">
                                                <option></option>
                                                <?php
                                                    foreach ($data_vendor as $key => $value) {
                                                        ?>
                                                        <option <?php if($vendor_id == $value->VENDOR_ID){echo 'selected';} ?> value="<?php echo $value->VENDOR_ID; ?>"><?php echo $value->VENDOR_NAME; ?></option>    
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Vendor Kind</label>
                                            <select class="form-control" name="vendor_kind">
                                                <option></option>
                                                <?php
                                                    foreach ($data_vendor_kind as $key => $value) {
                                                        ?>
                                                        <option <?php if($vendor_kind == $value->GENERAL_ID){echo 'selected';} ?> value="<?php echo $value->GENERAL_ID; ?>"><?php echo $value->GENERAL_DESCRIPTION; ?></option>    
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <input type="text" name="reference_number" class="form-control" style='text-transform:uppercase' value="<?php echo set_value('reference_number', $reference_number); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contract Date</label>
                                            <input type="text" name="contract_date" class="form-control date" value="<?php echo set_value('contract_date', $contract_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Vendor PIC</label>
                                            <input type="text" name="vendor_pic" class="form-control" style='text-transform:uppercase' value="<?php echo set_value('vendor_pic', $vendor_pic); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valid From Date</label>
                                            <input type="text" name="valid_from_date" id="start_date" class="form-control" value="<?php echo set_value('valid_from_date', $valid_from_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valid To Date</label>
                                            <input type="text" name="valid_to_date" id="end_date" class="form-control" value="<?php echo set_value('valid_to_date', $valid_to_date); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="btns" class="btn btn-primary">Save</button>
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
        $('.date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

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
