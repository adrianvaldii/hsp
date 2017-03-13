<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <?php echo form_open(); ?>
            <div class="col-md-12">
                <br>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">Data Quotation</div>
                    <div class="panel-body">
                        <?php if(isset($data_exists) && $data_exists == "exist") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo "Already sent approval!" ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?>
                        <?php  
                            foreach ($data_quotation as $value) {
                                ?>
                                    <div class="form-group">
                                        <input type="text" name="quotation_number" class="form-control" readonly="true" value="<?php echo $value->QUOTATION_DOCUMENT_NUMBER; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="quotation_number" class="form-control" readonly="true" value="<?php echo $value->COMPANY_NAME; ?>">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-outline btn-primary">Need Approve</button>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </form>
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
