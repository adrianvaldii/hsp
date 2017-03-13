<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1>Access Menu</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Access Menu
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                        
                        <?php if($this->session->flashdata('failed_entry_menu')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed_entry_menu'); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('success_entry_menu')) { ?>
                            <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success_entry_menu'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control" value="<?php echo set_value('nik'); ?>">
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

<?php
    $this->load->view('layouts/footer.php');
?>
