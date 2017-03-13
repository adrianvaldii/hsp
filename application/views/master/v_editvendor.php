<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Vendor <a href="<?php echo site_url('Master/view_all_vendor'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Vendor
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
                                <div class="form-group">
                                    <label>Vendor ID</label>
                                    <input type="text" name="vendor_id" readonly="true" class="form-control" value="<?php echo set_value('vendor_id', $vendor_id); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Vendor Name</label>
                                    <input type="text" name="vendor_name" style='text-transform:uppercase' class="form-control" value="<?php echo set_value('vendor_name', $vendor_name); ?>">
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
        $(".js-example-basic-single").select2({
            theme: "bootstrap"
          });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
