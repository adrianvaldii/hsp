<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Master General ID</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form General ID
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                        
                        <?php if($this->session->flashdata('failed_general_id')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed_general_id'); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('success_general_id')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success_general_id'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>General ID</label>
                                    <input type="text" name="general_id" class="form-control" value="<?php echo set_value('general_id'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Classification Type</label>
                                    <select class="form-control" name="classification_id">
                                        <option></option>
                                        <?php 
                                            foreach ($classification as $key => $value) { ?>
                                                <option value="<?php echo $value->CLASSIFICATION_ID; ?>"><?php echo $value->CLASSIFICATION_ID; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="general_description" class="form-control" value="<?php echo set_value('general_description'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <input type="text" name="general_description_short" class="form-control" value="<?php echo set_value('general_description_short'); ?>">
                                </div>
                                <input type="submit" name="submit" class="btn btn-outline btn-primary">
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
