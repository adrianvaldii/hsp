<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3 class="page-header">Edit Service</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
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

                        <?php if($this->session->flashdata('failed_edit_service')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed_edit_service'); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('success_edit_service')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success_edit_service'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                            	<div class="form-group">
                                    <label>Service ID</label>
                                    <input type="text" class="form-control" name="SELLING_SERVICE_ID" readonly="true" value="<?php echo $service_id; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Service Name</label>
                                    <input type="text" class="form-control" name="SERVICE_NAME" value="<?php echo $service_name; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Service Description</label>
                                    <input type="text" class="form-control" name="SERVICE_DESCRIPTION" value="<?php echo $service_description; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Selling Unit Measurment</label>
                                    <!-- <input type="text" class="form-control" name="SELL_UOM_ID"> -->
                                    <select class="form-control" name="SELL_UOM_ID">
                                        <option></option>
                                        <?php  
                                            foreach ($general as $data) {
                                                ?>
                                                <option <?php if($sell_uom_id == $data->GENERAL_ID) echo 'selected'; ?> value="<?php echo $data->GENERAL_ID; ?>"><?php echo $data->GENERAL_ID . " - " . $data->GENERAL_DESCRIPTION; ?></option>
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
                                            foreach ($service_types as $key => $data) {
                                                ?>
                                                <option <?php if($service_type == $data->GENERAL_ID) echo 'selected'; ?> value="<?php echo $data->GENERAL_ID; ?>"><?php echo $data->GENERAL_ID . " - " . $data->GENERAL_DESCRIPTION; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Active Status</label>
                                    <br>
                                    <label class="radio-inline">
                                      <input type="radio" name="flag" id="inlineRadio1" value="1" <?php if($flag == '1') echo 'checked' ?>> Active
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="flag" id="inlineRadio2" value="0" <?php if($flag == '0') echo 'checked' ?>> Unactive
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-outline btn-primary">Save</button>
                                <a href="<?php echo site_url('Service/index_edit') ?>" class="btn btn-link">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
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