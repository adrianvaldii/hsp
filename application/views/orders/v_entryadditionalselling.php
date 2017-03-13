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
            <h3>Entry Additional Selling</h3>
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

                                <?php if($this->session->flashdata('failed_additional_selling')) { ?>
                                    <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('failed_additional_selling'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success_additional_selling')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success_additional_selling'); ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="form-group">
                                    <label>Container Number <span style="color:red">*</span></label>
                                    <input type="hidden" name="additional_selling" value="<?php echo set_value('additional_selling', $id); ?>">
                                    <select class="form-control js-example-basic-single js-states" name="container_number" id="container_number" onChange="changeContainer()">
                                        <option <?php echo set_select('container_number', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($all_wo as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value->CONTAINER_NUMBER; ?>" data-containersize="<?php echo $value->CONTAINER_SIZE_ID; ?>" data-containertype="<?php echo $value->CONTAINER_TYPE_ID; ?>" data-containercategory="<?php echo $value->CONTAINER_CATEGORY_ID; ?>" data-fromlocation="<?php echo $value->FROM_LOCATION_ID; ?>" data-tolocation="<?php echo $value->TO_LOCATION_ID; ?>" data-agreement="<?php echo $value->AGREEMENT_NUMBER; ?>" <?php echo set_select('container_number', $value->CONTAINER_NUMBER, FALSE); ?> ><?php echo $value->CONTAINER_NUMBER; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Selling Service <span style="color:red">*</span></label>
                                    <select class="form-control js-example-basic-single js-states" name="selling_service_id" id="selling_additional" onChange="changeSelling()">
                                        <option <?php echo set_select('selling_service_id', '', TRUE); ?> ></option>
                                        <?php
                                            foreach ($selling_additional as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value->SELLING_SERVICE_ID; ?>" data-amount="<?php echo $value->TARIFF_AMOUNT; ?>" <?php echo set_select('selling_service_id', $value->SELLING_SERVICE_ID, FALSE); ?> ><?php echo $value->SERVICE_NAME; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                    <!-- hidden -->
                                    <input type="hidden" name="container_size" id="container_size">
                                    <input type="hidden" name="container_type" id="container_type">
                                    <input type="hidden" name="container_category" id="container_category">
                                    <input type="hidden" name="from_location" id="from_location">
                                    <input type="hidden" name="to_location" id="to_location">
                                    <input type="hidden" name="agreement_number" id="agreement_number">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Currency <span style="color:red">*</span></label>
                                            <select name="currency" class="form-control js-example-basic-single js-states">
                                                <option <?php echo set_select('currency', '', TRUE); ?>></option>
                                                <?php 
                                                    foreach ($data_currency as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value->CODE_CURRENCY; ?>" <?php echo set_select('currency', $value->CODE_CURRENCY, FALSE); ?> ><?php echo $value->CODE_CURRENCY; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                            <label>Amount <span style="color:red">*</span></label>
                                            <input type="text" name="amount" id="amount" class="form-control" value="<?php echo set_value('amount', 0); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Remarks <span style="color:red">*</span></label>
                                    <textarea class="form-control" name="remarks"></textarea>
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
        // alert(year);
    });

    function toRp(angka){
        var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
        var rev2    = '';
        for(var i = 0; i < rev.length; i++){
            rev2  += rev[i];
            if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                rev2 += ',';
            }
        }
        return rev2.split('').reverse().join('');
    }

    function changeSelling()
    {
      var amount = $('#selling_additional').find(":selected").attr("data-amount");
      $('#amount').val(toRp(amount));
      // alert(amount);
    }

    function changeContainer()
    {
      var container_size = $('#container_number').find(":selected").attr("data-containersize");
      var container_type = $('#container_number').find(":selected").attr("data-containertype");
      var container_category = $('#container_number').find(":selected").attr("data-containercategory");
      var from_location = $('#container_number').find(":selected").attr("data-fromlocation");
      var to_location = $('#container_number').find(":selected").attr("data-tolocation");
      var agreement_number = $('#container_number').find(":selected").attr("data-agreement");

      $('#container_size').val(container_size);
      $('#container_type').val(container_type);
      $('#container_category').val(container_category);
      $('#from_location').val(from_location);
      $('#to_location').val(to_location);
      $('#agreement_number').val(agreement_number);
      // alert(amount);
    }
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
