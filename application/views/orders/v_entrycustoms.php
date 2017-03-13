<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

// currency
$this->load->helper('currency_helper');

$attr_form = array(
    'name' => 'work_order'
 );

$date = date('Y-m-d');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Entry PIB/PEB</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo form_open('Order/entry_customs/'.$work_order_number, $attr_form); ?>
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
                        <hr>
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($customs_exists) && $customs_exists == "exists") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo "Customs Clearance already exists for this Work Order!"; ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($error_var_gro) && $error_var_gro == "error") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $error_var_gro_msg; ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($error_var_gro_mea) && $error_var_gro_mea == "error") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $error_var_gro_mea_msg; ?>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Work Order Number <span style="color:red">*</span></label>
                                    <input type="text" name="work_order_number" class="form-control" value="<?php echo $work_order_number; ?>" readonly="true" />
                                    <input type="hidden" name="company_id" id="company_id" class="form-control" value="<?php echo set_value('company_id', $company_id); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Work Order Date <span style="color:red">*</span></label>
                                    <input type="text" name="work_order_date" class="form-control" value="<?php echo $work_order_date; ?>" readonly="true" />
                                </div>
                                <div class="form-group">
                                    <label>Customer <span style="color:red">*</span></label>
                                    <input type="text" name="company_name" class="form-control" value="<?php echo $customer_name; ?>" readonly="true" />
                                </div>
                                <div class="form-group">
                                    <?php
                                        /*
                                            <label>Unit Terminal Location <span style="color:red">*</span></label>
                                            <input type="text" name="hoarding_name" class="form-control js-example-basic-single js-states" id="hoarding_name" value="<?php echo set_value('holding_location', $hoarding_name); ?>"/>
                                            <input type="hidden" name="hoarding_id" class="form-control" id="hoarding_id" value="<?php echo set_value('holding_location', $hoarding_id); ?>"/>
                                        */
                                    ?>
                                    <label>Terminal Location <span style="color:red">*</span></label>
                                    <select class="form-control js-example-basic-single js-states" name="hoarding_id">
                                        <option <?php echo set_select('hoarding_id', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($data_hoarding as $key => $value) {
                                                ?>
                                                    <option <?php if ($hoarding_id == $value->HOARDING_ID ) echo 'selected' ; ?> value="<?php echo $value->HOARDING_ID; ?>" <?php echo set_select('hoarding_id', $value->HOARDING_ID, FALSE); ?> ><?php echo $value->HOARDING_NAME; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Register Number <span style="color:red">*</span></label>
                                    <input type="text" name="register_number" class="form-control" id="vessel" value="<?php echo set_value('register_number', $register_number); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Register Date <span style="color:red">*</span></label>
                                    <input type="text" name="register_date" class="form-control" id="date" value="<?php echo set_value('register_date', $register_date); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Importir/ Exportir <span style="color:red">*</span></label>
                                    <input type="text" name="importir_name" class="form-control" id="importir_name" value="<?php echo set_value('importir_name', $importir_name); ?>"/>
                                    <input type="hidden" name="importir_id" class="form-control" id="vessel" value="<?php echo set_value('importir_id', $importir_id); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>PPJK <span style="color:red">*</span></label>
                                    <select name="ppjk_id" class="form-control">
                                        <option <?php if ($ppjk_id == "" ) echo 'selected' ; ?> <?php echo set_select('ppjk_id', '', TRUE); ?>></option>
                                        <?php  
                                            foreach ($company_hanoman as $key => $value) {?>
                                                <option <?php if ($ppjk_id == $value->COMPANY_ID ) echo 'selected' ; ?> value="<?php echo $value->COMPANY_ID; ?>" <?php echo set_select('ppjk_id', $value->COMPANY_ID, FALSE); ?> ><?php echo $value->COMPANY_NAME; ?></option>
                                            <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <hr>
                    <div class="panel panel-default">
                        <div class="panel-heading">Trucking Data</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tableTrucking">
                                    <thead>
                                        <tr>
                                            <th class="text-center">From/ To</th>
                                            <th class="text-center">Container Detail</th>
                                            <th class="text-center">Container No.</th>
                                            <th class="text-center">B/L No.</th>
                                            <th class="text-center">Customs Location<span style="color:red">*</span></th>
                                            <th class="text-center">Customs Lane<span style="color:red">*</span></th>
                                            <th class="text-center">Commodity<span style="color:red">*</span></th>
                                            <th class="text-center">Gross Weight<span style="color:red">*</span></th>
                                            <th class="text-center">Gross Weight Measurement<span style="color:red">*</span></th>
                                            <th class="text-center">Net Weight<span style="color:red">*</span></th>
                                            <th class="text-center">Net Weight Measurement<span style="color:red">*</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $loop_code = 0;
                                            foreach ($data_trucking as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo $value->FROM_NAME . " - " .$value->TO_NAME; ?>
                                                            <input type="hidden" name="trucking[<?php echo $loop_code; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                            <input type="hidden" name="trucking[<?php echo $loop_code; ?>][from_location_id]" value="<?php echo $value->FROM_LOCATION_ID; ?>" />
                                                            <input type="hidden" name="trucking[<?php echo $loop_code; ?>][to_location_id]" value="<?php echo $value->TO_LOCATION_ID; ?>" />
                                                            <input type="hidden" name="trucking[<?php echo $loop_code; ?>][container_size_id]" value="<?php echo $value->CONTAINER_SIZE_ID; ?>" />
                                                            <input type="hidden" name="trucking[<?php echo $loop_code; ?>][container_type_id]" value="<?php echo $value->CONTAINER_TYPE_ID; ?>" />
                                                            <input type="hidden" name="trucking[<?php echo $loop_code; ?>][container_category_id]" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>" />
                                                        </td>
                                                        <?php
                                                            if ($value->CONTAINER_CATEGORY_ID != 'NG') {
                                                                ?>
                                                                    <td class="text-center"><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?></td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                    <td class="text-center"><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID; ?></td>
                                                                <?php
                                                            }
                                                        ?>
                                                        <td class="text-center"><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                        <td class="text-center"><?php echo $value->BL_NUMBER; ?></td>
                                                        <td class="text-center">
                                                            <select name="trucking[<?php echo $loop_code; ?>][customs_location]" class="form-control">
                                                                <option <?php if ($value->CUSTOMS_LOCATION == "" ) echo 'selected' ; ?> ></option>
                                                                <?php
                                                                    foreach ($data_customs_location as $key1 => $value1) {
                                                                        ?>
                                                                            <option <?php if ($value->CUSTOMS_LOCATION == $value1->GENERAL_ID ) echo 'selected' ; ?> value="<?php echo $value1->GENERAL_ID; ?>"><?php echo $value1->GENERAL_DESCRIPTION ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="trucking[<?php echo $loop_code; ?>][customs_lane]" class="form-control">
                                                                <option <?php if ($value->CUSTOMS_LANE == "" ) echo 'selected' ; ?> ></option>
                                                                <?php
                                                                    foreach ($data_lane as $key1 => $value1) {
                                                                        ?>
                                                                            <option <?php if ($value->CUSTOMS_LANE == $value1->GENERAL_ID ) echo 'selected' ; ?> value="<?php echo $value1->GENERAL_ID; ?>"><?php echo $value1->GENERAL_DESCRIPTION ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                                /*
                                                                    <input type="text" name="trucking[<?php echo $loop_code; ?>][commodity_name]" class="form-control" value="<?php echo set_value('commodity_name', $value->COMMODITY_DESCRIPTION); ?>" onClick="search_commodity(<?php echo $loop_code; ?>)" id="commo<?php echo $loop_code; ?>" />
                                                                    <input type="hidden" name="trucking[<?php echo $loop_code; ?>][commodity_id]" class="form-control" value="<?php echo set_value('commodity_id', $value->COMMODITY_ID); ?>" id="commo_id<?php echo $loop_code; ?>" />
                                                                */
                                                            ?>
                                                            <select class="form-control js-example-basic-single js-states" name="trucking[<?php echo $loop_code; ?>][commodity_id]">
                                                                <option <?php echo set_select('commodity_id', '', TRUE); ?> ></option>
                                                                <?php 
                                                                    foreach ($data_commodity as $key1 => $value1) {
                                                                        ?>
                                                                            <option <?php if ($value->COMMODITY_ID == $value1->COMMODITY_ID ) echo 'selected' ; ?> value="<?php echo $value1->COMMODITY_ID; ?>" ><?php echo $value1->COMMODITY_DESCRIPTION; ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" name="trucking[<?php echo $loop_code; ?>][gross_weight]" class="form-control default-number" value="<?php echo set_value('gross_weight', $value->GROSS_WEIGHT); ?>" />
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="trucking[<?php echo $loop_code; ?>][gross_weight_measurement]" class="form-control">
                                                                <option <?php if ($value->GROSS_WEIGHT_MEASUREMENT == "" ) echo 'selected' ; ?> ></option>
                                                                <?php
                                                                    foreach ($data_measurement as $key1 => $value1) {
                                                                        ?>
                                                                            <option <?php if ($value->GROSS_WEIGHT_MEASUREMENT == $value1->GENERAL_ID ) echo 'selected' ; ?> value="<?php echo $value1->GENERAL_ID; ?>"><?php echo $value1->GENERAL_ID ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" name="trucking[<?php echo $loop_code; ?>][net_weight]" class="form-control" value="<?php echo $value->NET_WEIGHT; ?>" />
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="trucking[<?php echo $loop_code; ?>][net_weight_measurement]" class="form-control">
                                                                <option <?php if ($value->NET_WEIGHT_MEASUREMENT == "" ) echo 'selected' ; ?> ></option>
                                                                <?php
                                                                    foreach ($data_measurement as $key1 => $value1) {
                                                                        ?>
                                                                            <option <?php if ($value->NET_WEIGHT_MEASUREMENT == $value1->GENERAL_ID ) echo 'selected' ; ?> value="<?php echo $value1->GENERAL_ID; ?>"><?php echo $value1->GENERAL_ID ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php
                                                $loop_code++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

<!-- script datatables -->
<script type="text/javascript">
    function search_commodity(id)
    {
        // autocomplete hoarding
        $('#commo'+id+'').autocomplete({
          source: "<?php echo site_url('Order/search_commodity'); ?>",
          minLength:1,
          select:function(event, data){
            $('#commo_id'+id+'').val(data.item.commodity_id);
          }
        });
    }

    $(document).ready(function() {
        // document.getElementsByClassName("default-number").value = 0;
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

        $(".js-example-basic-single").select2({
            theme: "bootstrap"
        });

        $('#table-trucking').DataTable({
                responsive: true
        });

        // autocomplete hoarding
        $("#hoarding_name").autocomplete({
          source: "<?php echo site_url('Order/search_hoarding'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=hoarding_id]').val(data.item.hoarding_id);
          }
        });

        // // autocomplete hoarding
        // $('#').autocomplete({
        //   source: "<?php echo site_url('Order/search_nik'); ?>",
        //   minLength:1,
        //   select:function(event, data){
        //     $('#nik_isi'+id+'').val(data.item.pic_id);
        //   }
        // });

        // autocomplete importir
        $("#importir_name").autocomplete({
          source: "<?php echo site_url('Order/search_company'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=importir_id]').val(data.item.company_id);
          }
        });
    });

</script>


<?php
    $this->load->view('layouts/footer.php');
?>
