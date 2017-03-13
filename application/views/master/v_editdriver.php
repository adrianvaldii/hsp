<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Master Driver <a href="<?php echo site_url('Master/view_all_driver'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Master Driver
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                        
                        <?php if($this->session->flashdata('failed_edit_driver')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed_edit_driver'); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('success_edit_driver')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success_edit_driver'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Driver ID <span style="color:red">*</span></label>
                                            <input type="text" name="driver_id" class="form-control" value="<?php echo set_value('driver_id', $id); ?>" readonly="true" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Driver Name <span style="color:red">*</span></label>
                                            <input type="text" style="text-transform:uppercase" name="driver_name" class="form-control" value="<?php echo set_value('driver_name', $driver_name); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Place of Birth <span style="color:red">*</span></label>
                                            <input type="text" name="born_of_location" class="form-control" value="<?php echo set_value('born_of_location', $born_of_location); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span style="color:red">*</span></label>
                                            <input type="text" name="born_of_date" class="form-control date" id="born-date" value="<?php echo set_value('born_of_date', $born_of_date); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>KTP Number <span style="color:red">*</span></label>
                                    <input type="text" name="ktp_number" class="form-control" value="<?php echo set_value('ktp_number', $ktp_number); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SIM Number <span style="color:red">*</span></label>
                                            <input type="text" name="license_driver_id" class="form-control" id="born-date" value="<?php echo set_value('license_driver_id', $license_driver_id); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SIM Expired <span style="color:red">*</span></label>
                                            <input type="text" name="license_driver_expired" class="form-control date" id="born-date" value="<?php echo set_value('license_driver_expired', $license_driver_expired); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number <span style="color:red">*</span></label>
                                    <input type="number" name="phone_number" class="form-control" value="<?php echo set_value('phone_number', $phone_number); ?>">
                                </div>
                                <input type="submit" name="submit" value="Save" class="btn btn-outline btn-primary">
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
<script>
    $(document).ready(function() {
        $('.date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
