<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Master Hoarding</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Master Hoarding
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                        
                        <?php if($this->session->flashdata('failed_hoarding')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed_hoarding'); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('success_hoarding')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success_hoarding'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>Hoarding ID</label>
                                    <input type="text" name="hoarding_id" class="form-control" value="<?php echo set_value('hoarding_id', $hoarding_id); ?>" readonly="true" />
                                </div>
                                <div class="form-group">
                                    <label>Hoarding Location</label>
                                    <input type="text" style="text-transform:uppercase" name="hoarding_name" class="form-control" value="<?php echo set_value('hoarding_name'); ?>">
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
        $('#table-service').DataTable({
                responsive: true
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
