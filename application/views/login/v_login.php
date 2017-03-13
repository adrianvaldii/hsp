<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');
?>

<!-- content -->
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">
                    <?php if(validation_errors()) { ?>
                        <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo validation_errors(); ?>
                        </div>
                    <?php } ?>

                    <?php if(isset($nik_error) && $nik_error == "error") { ?>
                        <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo "NIK does not exists!" ?>
                        </div>
                    <?php } ?>

                    <?php if(isset($login_error) && $login_error == "error") { ?>
                        <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('login_error'); ?>
                        </div>
                    <?php } ?>

                    <?php if(isset($not_akses) && $not_akses == "error") { ?>
                        <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo "This user can't use this application!" ?>
                        </div>
                    <?php } ?>
                 
                    <?php if($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>

                    <?php echo form_open(); ?>
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" name="nik" placeholder="NIK" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="password" data-toggle="password" name="password" placeholder="Password" type="password">
                            </div>
                            <hr>
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" name="Submit" class="btn btn-lg btn-outline btn-success btn-block" value="Login">
                        </fieldset>
                    </form>
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
