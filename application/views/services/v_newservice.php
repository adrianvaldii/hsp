<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>New Service</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Selling Service
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('failed_entry_service')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed_entry_service'); ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('success_entry_service')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success_entry_service'); ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('data_exist')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('data_exist'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>Service ID</label>
                                    <input type="text" class="form-control" name="SELLING_SERVICE_ID" value="<?php echo $service_id; ?>" readonly="true">
                                </div>
                                <div class="form-group">
                                    <label>Service Name</label>
                                    <input type="text" class="form-control" name="SERVICE_NAME" value="<?php echo set_value('SERVICE_NAME'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Service Description</label>
                                    <input type="text" class="form-control" name="SERVICE_DESCRIPTION" value="<?php echo set_value('SERVICE_DESCRIPTION'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Selling Unit Measurment</label>
                                    <!-- <input type="text" class="form-control" name="SELL_UOM_ID"> -->
                                    <select class="form-control" name="SELL_UOM_ID">
                                        <option></option>
                                        <?php  
                                            foreach ($general as $data) {
                                                ?>
                                                <option value="<?php echo $data->GENERAL_ID; ?>"><?php echo $data->GENERAL_ID . " - " . $data->GENERAL_DESCRIPTION; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Service Type</label>
                                    <!-- <input type="text" class="form-control" name="SELL_UOM_ID"> -->
                                    <select class="form-control" name="service_type">
                                        <option></option>
                                        <?php  
                                            foreach ($service_type as $data) {
                                                ?>
                                                <option value="<?php echo $data->GENERAL_ID; ?>"><?php echo $data->GENERAL_ID . " - " . $data->GENERAL_DESCRIPTION; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Active Status</label>
                                    <br>
                                    <label class="radio-inline">
                                      <input type="radio" name="flag" id="inlineRadio1" value="1"> Active
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="flag" id="inlineRadio2" value="0"> Unactive
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-outline btn-primary">Save</button>
                                <a href="<?php echo site_url('Service/index') ?>" class="btn btn-link">Cancel</a>
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
        $('#table-service').DataTable({
                responsive: true
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
