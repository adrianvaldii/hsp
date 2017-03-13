<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Truck</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Edit Truck
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                         
                        <?php if($this->session->flashdata('failed')) { ?>
                            <div class="alert alert-warning">
                            <?php echo $this->session->flashdata('failed'); ?>
                            </div>
                        <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>Truck Number</label>
                                    <input type="text" name="truck_id" readonly="true" class="form-control" value="<?php echo set_value('truck_id', $truck_id); ?>" placeholder="e.g. B 9090 HSP" />
                                </div>
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" name="company_id" readonly="true" class="form-control" value="<?php echo set_value('company_id', $company_id); ?>" />
                                </div>
                                <div class="form-group">
                                    <label>Share Operation Cost</label>
                                    <input type="number" name="share_operation_cost" placeholder="e.g. (in percent) 90%" class="form-control" value="<?php echo set_value('share_operation_cost', $share_operation_cost); ?>" />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>STNK Expired</label>
                                            <input type="text" name="stnk_expired" class="form-control date" value="<?php echo set_value('stnk_expired', $stnk_expired); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>BPKB Number</label>
                                            <input type="number" name="bpkb_number" class="form-control" value="<?php echo set_value('bpkb_number', $bpkb_number); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>KIR Number</label>
                                            <input type="number" name="kir_number" class="form-control" value="<?php echo set_value('kir_number', $kir_number); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>KIR Expired</label>
                                            <input type="text" name="kir_expired" class="form-control date" value="<?php echo set_value('kir_expired', $kir_expired); ?>" />
                                        </div>
                                    </div>
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
        $('.date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
