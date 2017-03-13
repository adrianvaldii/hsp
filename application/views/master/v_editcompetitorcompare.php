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
            <h3>Edit Competitor Compare</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form Entry</div>
                        <div class="panel-body">
                            <?php echo form_open(); ?>
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
                                    <label>Compare ID <span style="color:red">*</span></label>
                                    <input type="text" readonly="true" name="compare_id" value="<?php echo $compare_id ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Competitor <span style="color:red">*</span></label>
                                    <select readonly="true" class="form-control js-example-basic-single js-states" name="competitor_id" id="competitor_id">competitor_id
                                        <option <?php echo set_select('competitor_id', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($data_competitor as $key => $value) {
                                                ?>
                                                    <option <?php if($competitor_id == $value->COMPETITOR_ID) echo 'selected'; ?> value="<?php echo $value->COMPETITOR_ID; ?>" <?php echo set_select('competitor_id', $value->COMPETITOR_ID, FALSE); ?> ><?php echo $value->COMPETITOR_NAME; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valid From Date <span style="color:red">*</span></label>
                                            <input type="text" name="valid_from_date" id="start_date" class="form-control" value="<?php echo set_value('valid_from_date', $valid_from_date); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valid To Date <span style="color:red">*</span></label>
                                            <input type="text" name="valid_to_date" id="end_date" class="form-control" value="<?php echo set_value('valid_to_date', $valid_to_date); ?>">
                                        </div>
                                    </div>
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

<!-- script datatables -->
<script type="text/javascript">
    $(document).ready(function() {
      // $(".js-example-basic-single").select2({
      //   theme: "bootstrap"
      // });

      $('#amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});

      $('#start_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            onShow:function( ct ){
                this.setOptions({
                    maxDate:$('#end_date').val()?$('#end_date').val():false
                })
            }
        });

        $('#end_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            onShow:function( ct ){
                this.setOptions({
                    minDate:$('#start_date').val()?$('#start_date').val():false
                })
            }
        });

    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
