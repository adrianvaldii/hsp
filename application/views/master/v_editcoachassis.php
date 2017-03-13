<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit GL Account Chassis <a href="<?php echo site_url('Master/view_coa_chassis'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Edit GL Chassis
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
                                    <label>Chassis Number</label>
                                    <input type="text" readonly="true" name="chassis_id" class="form-control" value="<?php echo set_value('chassis_id', $chassis_id); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Cost Name</label>
                                    <input type="text" readonly="true" name="cost_name" class="form-control" value="<?php echo set_value('cost_name', $cost_name); ?>">
                                    <input type="hidden" readonly="true" name="cost_id" class="form-control" value="<?php echo set_value('cost_id', $cost_id); ?>">
                                </div>
                                <div class="form-group">
                                    <label>GL Account</label>
                                    <select name="gl_account" class="form-control js-example-basic-single js-states">
                                        <option <?php echo set_select('gl_account', '', TRUE); ?>></option>
                                        <?php 
                                            foreach ($data_gl_account as $key => $value) {
                                                ?>
                                                    <option <?php if($gl_account == $value->account_code){echo 'selected';} ?> value="<?php echo $value->account_code; ?>" <?php echo set_select('gl_account', $value->account_code, FALSE); ?> ><?php echo $value->account_code . " - " . $value->account_description; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
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
