<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Add Company Service</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Company Service
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(validation_errors()) { ?>
                                <div class="alert alert-danger">
                                <?php echo validation_errors(); ?>
                                </div>
                            <?php } ?>

                            <?php if($this->session->flashdata('data_exist')) { ?>
                                <div class="alert alert-warning">
                                <?php echo $this->session->flashdata('data_exist'); ?>
                                </div>
                            <?php } ?>

                            <?php if($this->session->flashdata('failed_entry_company_service')) { ?>
                                <div class="alert alert-warning">
                                <?php echo $this->session->flashdata('failed_entry_company_service'); ?>
                                </div>
                            <?php } ?>

                            <?php if($this->session->flashdata('success_entry_company_service')) { ?>
                                <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success_entry_company_service'); ?>
                                </div>
                            <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="form-group">
                                    <label>Company</label>
                                    <select class="form-control" name="COMPANY_SERVICE_ID">
                                        <option></option>
                                        <?php  
                                            foreach ($company as $key => $data_company) {
                                                ?>
                                                <option value="<?php echo $data_company->COMPANY_ID; ?>"><?php echo $data_company->COMPANY_ID . " - " . $data_company->COMPANY_NAME; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Service</label>
                                    <select class="form-control" name="SELLING_SERVICE_ID">
                                        <option></option>
                                        <?php  
                                            foreach ($service as $data_service) {
                                                ?>
                                                <option value="<?php echo $data_service->SELLING_SERVICE_ID; ?>"><?php echo $data_service->SELLING_SERVICE_ID . " - " . $data_service->SERVICE_NAME; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-outline btn-primary" id="save" onClick="click()" name="submit">Save</button>
                                <a href="<?php echo site_url('Service/company_service') ?>" class="btn btn-link">Cancel</a>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#table-service').DataTable({
                responsive: true
        });
    });

    function click()
    {
        setTimeout(function () {    
            window.location.href = '<?php echo site_url("Service/company_service"); ?>'; 
        },5000);
    }
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
