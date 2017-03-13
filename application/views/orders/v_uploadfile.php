<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Upload Mutation Account</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form Upload</div>
                        <div class="panel-body">
                            <?php echo form_open('Order/upload_mutasi_in', array('method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('failed')) { ?>
                                    <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('failed'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="form-group">
                                    <label>File <span style="color:red">*</span></label>
                                    <input type="file" name="file" id="check_xls"/>
                                </div>
                                <button type="submit" id="btn-submit" class="btn btn-primary">Upload File</button>
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
        $("#check_xls").change(function (e) {
            var val = $(this).val();
            if (!val.match(/(?:xls)$/)) {
                swal("Ooops...", "Please upload file with 'xls' extension!", "error");
                $('#btn-submit').attr("disabled", "disabled");
            } else {
                $('#btn-submit').removeAttr("disabled");
            }
        });
        
      $(".js-example-basic-single").select2({
        theme: "bootstrap"
      });
      $('#amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
      $('#invoice_amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
    });

    $('#container_number').on('change',function () {
        var year = $(this).val();
        // $.ajax({
        //     url: 'url for get data',
        //     type: 'POST',
        //     data: {year: year},
        //     success: function (a) {
        //         data = JSON.parse(a);
        //         #get data and print data with each loop
        //     }
        // });
        alert(year);
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
