<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Cost <a href="<?php echo site_url('Master/view_cost'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Edit
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
                                 
                                <?php if($this->session->flashdata('failed')) { ?>
                                    <div class="alert alert-warning">
                                    <?php echo $this->session->flashdata('failed'); ?>
                                    </div>
                                <?php } ?>
                                 
                                <?php if($this->session->flashdata('success')) { ?>
                                    <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                <?php } ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost ID</label>
                                            <input type="text" name="cost_id" class="form-control" value="<?php echo set_value('cost_id', $cost_id); ?>" readonly="true" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost Name</label>
                                            <input type="text" name="cost_name" style='text-transform:uppercase' class="form-control" value="<?php echo set_value('cost_name', $cost_name); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" name="description" class="form-control" value="<?php echo set_value('description', $description); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost Kind</label>
                                            <select class="form-control" name="cost_kind" id="cost_kind">
                                                <option></option>
                                                <option value="S" <?php if ($cost_kind == "S" ) echo 'selected' ; ?> >Standard Cost</option>
                                                <option value="A" <?php if ($cost_kind == "A" ) echo 'selected' ; ?> >Additional Cost</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost Type</label>
                                            <select class="form-control" name="cost_type" id="cost_type">
                                                <option></option>
                                                <?php
                                                    foreach ($cost_type as $key => $value) {
                                                        ?>
                                                            <option <?php if ($s_cost_type == $value->GENERAL_ID ) echo 'selected' ; ?> value="<?php echo $value->GENERAL_ID ?>"><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost Group</label>
                                            <select class="form-control" name="cost_group" id="cost_group">
                                                <option></option>
                                                <?php
                                                    foreach ($cost_group as $key => $value) {
                                                        ?>
                                                            <option <?php if ($s_cost_group == $value->GENERAL_ID ) echo 'selected' ; ?> value="<?php echo $value->GENERAL_ID ?>"><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost Share</label>
                                            <select class="form-control" name="cost_share" id="cost_share">
                                                <option></option>
                                                <option <?php if ($cost_share == "N" ) echo 'selected' ; ?> value="N">No</option>
                                                <option <?php if ($cost_share == "Y" ) echo 'selected' ; ?> value="Y">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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

<!-- script datatables -->
<script>
    $(document).ready(function() {
        $('#table-service').DataTable({
                responsive: true
        });

        $(".js-example-basic-single").select2({
            theme: "bootstrap"
          });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
