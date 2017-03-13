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

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 950)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);

$temp_id = $this->M_order->get_max_id()->row()->id;
$temp_year = $this->M_order->get_max_id()->row()->year_temp;
$year_now = date('y');
// $date = date('Y-m-d H:i:s');

$potongan_tahun = substr($temp_year, 0,2);

if ($potongan_tahun == $year_now) {
    // echo "sama";

    $temp_id_tem = $temp_id + 1;
    if ($temp_id_tem < 10) {
        $id = $year_now . "000" . $temp_id_tem;
    } elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
        $id = $year_now . "00" . $temp_id_tem;
    } elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
        $id = $year_now . "0" . $temp_id_tem;
    } else {
        $id = $year_now . $temp_id_tem;
    }
} else {
    // echo "tidak";
    $id = $year_now . "0001";
}

$work_order_number = $id;

?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Entry Work Order</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php /* <?php echo form_open('Order/create_work_order/'.$company_id, $attr_form); ?> */ ?>
                    <?php echo form_open('Order/create_wo/'.$company_id.'/'.$quotation_no.'/'.$agreement_no, $attr_form); ?>
                        <button type="submit" name="entry" class="btn btn-success">Save</button>
                        <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
                        <hr>
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($wo_exists) && $wo_exists == "exists") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo "Work Order number already exists!"; ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($trucking_error) && $trucking_error == "error") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo "Duplicate container numbers!"; ?>
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
                                <?php 
                                    /*
                                        <div class="form-group">
                                            <label>Work Order Number <span style="color:red">*</span></label>
                                            <input type="text" name="work_order_number" class="form-control" value="<?php echo $work_order_number; ?>" readonly="true" />
                                        </div>
                                    */
                                ?>
                                <div class="form-group">
                                    <label>Date <span style="color:red">*</span></label>
                                    <input type="text" name="work_order_date" readonly="true" class="form-control" value="<?php echo set_value('work_order_date', $date); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Customer <span style="color:red">*</span></label>
                                    <input type="text" name="company_name" class="form-control" value="<?php echo $company_name; ?>" readonly="true" />
                                </div>
                                <div class="form-group">
                                    <label>Reference Number <span style="color:red">*</span></label>
                                    <input type="text" name="reference_number" class="form-control" value="<?php echo set_value('reference_number'); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Services <span style="color:red">*</span></label>
                                    <br>
                                    <?php
                                        foreach ($service_order as $key => $value) {
                                            ?>
                                                <label class="checkbox-inline">
                                                  <input type="checkbox" name="service[]" value="<?php echo $value->GENERAL_ID ?>"> <?php echo $value->GENERAL_DESCRIPTION; ?>
                                                </label>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Shipper <span style="color:red">*</span></label>
                                            <input type="text" name="shipper" class="form-control" value="<?php echo set_value('shipper'); ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Consignee <span style="color:red">*</span></label>
                                            <input type="text" name="consignee" class="form-control" value="<?php echo set_value('consignee'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Vessel <span style="color:red">*</span></label>
                                            <input type="text" name="vessel_name" class="form-control" id="vessel" value="<?php echo set_value('vessel_name'); ?>"/> <?php echo anchor_popup('Order/entry_vessel_voyage/','Entry Vessel Voyage', $attributes); ?>
                                            <input type="hidden" name="vessel_id" class="form-control" value="<?php echo set_value('vessel_id'); ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Voyage Number <span style="color:red">*</span></label>
                                            <input type="text" name="voyage_number" id="voyage_number" class="form-control" value="<?php echo set_value('voyage_number'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ETA <span style="color:red">*</span></label>
                                            <input type="text" name="eta" class="form-control" id="eta" value="<?php echo set_value('eta'); ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ETD <span style="color:red">*</span></label>
                                            <input type="text" name="etd" class="form-control" id="etd" value="<?php echo set_value('etd'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Trade <span style="color:red">*</span></label>
                                    <br>
                                    <label class="radio-inline">
                                        <input type="radio" name="trade_id" id="trade-imp" value="IMP" <?php echo set_checkbox('trade_id', 'IMP'); ?>> Import
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="trade_id" id="trade-exp" value="EXP" <?php echo set_checkbox('trade_id', 'EXP'); ?>> Export
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="trade_id" id="trade-tr" value="TR" <?php echo set_checkbox('trade_id', 'TR'); ?>> Trucking
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="port-imp">
                                            <label>Port of Loading <span style="color:red">*</span></label>
                                            <input type="text" name="pol_name" id="pol_name" class="form-control" value="<?php echo set_value('pol_name'); ?>"/>
                                            <input type="hidden" name="pol_id" id="pod_id" class="form-control" value="<?php echo set_value('pol_id'); ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="port-exp">
                                            <label>Port of Discharges <span style="color:red">*</span></label>
                                            <input type="text" name="pod_name" id="pod_name" class="form-control" value="<?php echo set_value('pod_name'); ?>"/>
                                            <input type="hidden" name="pod_id" id="pod_id" class="form-control" value="<?php echo set_value('pod_id'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <hr>
                    <div class="panel panel-default">
                        <div class="panel-heading">Trucking Work Order</div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered" id="tableTruckingWO">
                                <tr>
                                    <th></th>
                                    <th class="text-center">From</th>
                                    <th class="text-center">To</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Container No. <span style="color:red">*</span></th>
                                    <th class="text-center">Seal No. <span style="color:red">*</span></th>
                                    <th class="text-center">B/L No. <span style="color:red">*</span></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              Trucking Agreement
                            </a>
                          </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <table class="table table-striped table-bordered" id="tableTrucking">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">From</th>
                                        <th class="text-center">To</th>
                                        <th class="text-center">Size</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $loop_code = 0;
                                        foreach ($data_trucking as $key => $value) {
                                            ?>
                                                <tr id="<?php echo $loop_code; ?>">
                                                    <!-- hidden -->
                                                    <input type="hidden" name="temp_from_name<?php echo $loop_code; ?>" value="<?php echo $value['FROM_NAME']; ?>">
                                                    <input type="hidden" name="temp_to_name<?php echo $loop_code; ?>" value="<?php echo $value['TO_NAME']; ?>">
                                                    <input type="hidden" name="temp_from_name_short<?php echo $loop_code; ?>" value="<?php echo $value['FROM_NAME_SHORT']; ?>">
                                                    <input type="hidden" name="temp_to_name_short<?php echo $loop_code; ?>" value="<?php echo $value['TO_NAME_SHORT']; ?>">
                                                    <input type="hidden" name="temp_to<?php echo $loop_code; ?>" value="<?php echo $value['TO_LOCATION_ID']; ?>">
                                                    <input type="hidden" name="temp_from<?php echo $loop_code; ?>" value="<?php echo $value['FROM_LOCATION_ID']; ?>">
                                                    <input type="hidden" name="temp_size<?php echo $loop_code; ?>" value="<?php echo $value['CONTAINER_SIZE_ID']; ?>">
                                                    <input type="hidden" name="temp_type<?php echo $loop_code; ?>" value="<?php echo $value['CONTAINER_TYPE_ID']; ?>">
                                                    <input type="hidden" name="temp_category<?php echo $loop_code; ?>" value="<?php echo $value['CONTAINER_CATEGORY_ID']; ?>">
                                                    <input type="hidden" name="temp_currency<?php echo $loop_code; ?>" value="<?php echo $value['SELLING_CURRENCY']; ?>">
                                                    <input type="hidden" name="temp_amount<?php echo $loop_code; ?>" value="<?php echo $value['SELLING_OFFERING_RATE']; ?>">
                                                    <input type="hidden" name="temp_agreement_number<?php echo $loop_code; ?>" value="<?php echo $value['AGREEMENT_NUMBER']; ?>">
                                                    <input type="hidden" name="temp_quotation_number<?php echo $loop_code; ?>" value="<?php echo $value['QUOTATION_NUMBER']; ?>">
                                                    <input type="hidden" name="temp_selling_service<?php echo $loop_code; ?>" value="<?php echo $value['SELLING_SERVICE_ID']; ?>">

                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-success btn_add_agreement agree<?php echo $loop_code; ?>" id="add_agreement<?php echo $loop_code; ?>" onClick="addTrucking('<?php echo $loop_code; ?>')"><span class="glyphicon glyphicon-plus"></span></button> 
                                                    </td>

                                                    <td class="text-center"><?php echo $value['FROM_NAME']; ?></td>
                                                    <td class="text-center"><?php echo $value['TO_NAME']; ?></td>
                                                    <td class="text-center"><?php echo $value['CONTAINER_SIZE_ID']; ?></td>
                                                    <td class="text-center"><?php echo $value['CONTAINER_TYPE_ID']; ?></td>
                                                    <td class="text-center"><?php echo $value['CONTAINER_CATEGORY_ID']; ?></td>
                                                    <td class="text-right"><?php echo $value['SELLING_CURRENCY'] . " " . currency($value['SELLING_OFFERING_RATE']); ?></td>
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
    // $('.duit').autoNumeric('init',{vMin: 0, vMax: 9999999999});
    $(document).ready(function() {
        // $('.duit').autoNumeric('init',{vMin: 0, vMax: 9999999999});
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

        $('#eta').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            onShow:function( ct ){
                this.setOptions({
                    minDate:$('#etd').val()?$('#etd').val():false
                })
            }
        });
        
        $('#etd').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            onShow:function( ct ){
                this.setOptions({
                    maxDate:$('#eta').val()?$('#eta').val():false
                })
            }
        });

        $('#tableTrucking').DataTable({
                responsive: true
        });

        // autocomplete from location
        $("#vessel").autocomplete({
          source: "<?php echo site_url('Order/search_vessel'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=vessel_id]').val(data.item.vessel_id);
            $('input[name=vessel_name]').val(data.item.vessel_name);
            $('input[name=pol_id]').val(data.item.pol_id);
            $('input[name=pol_name]').val(data.item.pol_name);
            $('input[name=pod_id]').val(data.item.pod_id);
            $('input[name=pod_name]').val(data.item.pod_name);
            $('#voyage_number').val(data.item.voyage_number);
            $('#eta').val(data.item.eta);
            $('#etd').val(data.item.etd);

            if (data.item.trade == "IMP") {
                $("#trade-imp").attr('checked', 'checked');
            } else {
                $("#trade-exp").attr('checked', 'checked');
            }
          }
        });

        // // autocomplete from location
        // $("#vessel").autocomplete({
        //   source: "<?php echo site_url('Order/search_vessel2'); ?>",
        //   minLength:1,
        //   select:function(event, data){
        //     $('input[name=vessel_id]').val(data.item.vessel_id);
        //   }
        // });

        // autocomplete pol
        $("#pol_name").autocomplete({
          source: "<?php echo site_url('Order/search_port'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=pol_id]').val(data.item.port_id);
          }
        });

        // autocomplete from location
        $("#pod_name").autocomplete({
          source: "<?php echo site_url('Order/search_port'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=pod_id]').val(data.item.port_id);
          }
        });

        // $("#port-imp").hide();
        // $("#port-exp").hide();

        // if($("#trade-imp").is(":checked")) {
        //     $("#port-imp").show();
        //     $("#port-exp").hide();
        // } else {
        //     $("#port-imp").hide();
        //     $("#port-exp").hide();
        // }

        // if($("#trade-exp").is(":checked")) {
        //     $("#port-exp").show();
        //     $("#port-imp").hide();
        // } else {
        //     $("#port-imp").hide();
        //     $("#port-exp").hide();
        // }

        // $("#trade-imp").click(function() {
        //     $("#port-imp").show();
        //     $("#port-exp").hide();
        // });

        // $("#trade-exp").click(function() {
        //     $("#port-exp").show();
        //     $("#port-imp").hide();
        // });
    });

    var no = 0;
    $('.no_clone').each(function() {
        no = Math.max(this.id, no);
    });
    no = no+1;
    // console.log(no);

    function toRp(angka)
    {
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

    function removeCustoms(id)
    {
        $('.clones'+id+'').remove();
    }

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#'+button_id+'').remove();
    });

    function cloneTrucking(id)
    {
        // $('.duit').autoNumeric('init',{vMin: 0, vMax: 9999999999});
        var from_name_wo = document.forms['work_order'].elements['temp_from_name_wo'+id].value
        var to_name_wo = document.forms['work_order'].elements['temp_to_name_wo'+id].value
        var to_wo = document.forms['work_order'].elements['temp_to_wo'+id].value
        var from_wo = document.forms['work_order'].elements['temp_from_wo'+id].value
        var size_wo = document.forms['work_order'].elements['temp_size_wo'+id].value
        var type_wo = document.forms['work_order'].elements['temp_type_wo'+id].value
        var category_wo = document.forms['work_order'].elements['temp_category_wo'+id].value
        var currency_wo = document.forms['work_order'].elements['temp_currency_wo'+id].value
        var amount_wo = document.forms['work_order'].elements['temp_amount_wo'+id].value
        var agreement_number_wo = document.forms['work_order'].elements['temp_agreement_number_wo'+id].value
        var quotation_number_wo = document.forms['work_order'].elements['temp_quotation_number_wo'+id].value
        var selling_service_wo = document.forms['work_order'].elements['temp_selling_service_wo'+id].value

        // append data
        $('#tableTruckingWO').append('<tr id="baris'+number+'" class="no_clone clones'+number+'"><!-- hidden --><input type="hidden" name="temp_from_name'+number+'" value="'+from_name_wo+'"><input type="hidden" name="temp_to_name'+number+'" value="'+to_name_wo+'"><input type="hidden" name="temp_to'+number+'" value="'+to_wo+'"><input type="hidden" name="temp_from'+number+'" value="'+from_wo+'"><input type="hidden" name="temp_size'+number+'" value="'+size_wo+'"><input type="hidden" name="temp_type'+number+'" value="'+type_wo+'"><input type="hidden" name="temp_category'+number+'" value="'+category_wo+'"><input type="hidden" name="temp_currency'+number+'" value="'+currency_wo+'"><input type="hidden" name="temp_amount'+number+'" value="'+amount_wo+'"><input type="hidden" name="temp_agreement_number'+number+'" value="'+agreement_number_wo+'"><input type="hidden" name="temp_quotation_number'+number+'" value="'+quotation_number_wo+'"><input type="hidden" name="temp_selling_service'+number+'" value="'+selling_service_wo+'"><!-- hidden for insert data --><input type="hidden" name="trucking['+number+'][to_location_id]" value="'+to_wo+'"><input type="hidden" name="trucking['+number+'][from_location_id]" value="'+from_wo+'"><input type="hidden" name="trucking['+number+'][container_size_id]" value="'+size_wo+'"><input type="hidden" name="trucking['+number+'][container_type_id]" value="'+type_wo+'"><input type="hidden" name="trucking['+number+'][container_category_id]" value="'+category_wo+'"><input type="hidden" name="trucking['+number+'][currency]" value="'+currency_wo+'"><input type="hidden" name="trucking['+number+'][amount]" value="'+amount_wo+'"><input type="hidden" name="trucking['+number+'][agreement_number]" value="'+agreement_number_wo+'"><input type="hidden" name="trucking['+number+'][quotation_number]" value="'+quotation_number_wo+'"><input type="hidden" name="trucking['+number+'][selling_service_id]" value="'+selling_service_wo+'"><td class="text-center"><button class="btn btn-danger btn_remove_agree" id="'+number+'"><span class="glyphicon glyphicon-minus"></span></button></td><td class="text-center">'+from_name_wo+'</td><td class="text-center">'+to_name_wo+'</td><td class="text-center">'+size_wo+'</td><td class="text-center">'+type_wo+'</td><td class="text-center">'+category_wo+'</td><td class="text-right">'+currency_wo+"  "+toRp(amount_wo)+'</td><td class="text-center"><input type="text" name="trucking['+number+'][container_no]" class="form-control" value="<?php echo set_value('container_no'); ?>" /></td><td class="text-center"><input type="text" name="trucking['+number+'][seal_no]" class="form-control" value="<?php echo set_value('seal_no'); ?>" /></td><td class="text-center"><input type="text" name="trucking['+number+'][bl_no]" class="form-control" value="<?php echo set_value('bl_no'); ?>" /></td></tr>');

        //  document.getElementById("clickTrucking"+id).disabled = true;
        number++;
        // return false;
    }

    var number = 0;

    function addTrucking(id)
    {
        // $('.duit').autoNumeric('init',{vMin: 0, vMax: 9999999999});
        var from_name = document.forms['work_order'].elements['temp_from_name'+id].value
        var to_name = document.forms['work_order'].elements['temp_to_name'+id].value
        var from_name_short = document.forms['work_order'].elements['temp_from_name_short'+id].value
        var to_name_short = document.forms['work_order'].elements['temp_to_name_short'+id].value
        var to = document.forms['work_order'].elements['temp_to'+id].value
        var from = document.forms['work_order'].elements['temp_from'+id].value
        var size = document.forms['work_order'].elements['temp_size'+id].value
        var type = document.forms['work_order'].elements['temp_type'+id].value
        var category = document.forms['work_order'].elements['temp_category'+id].value
        var currency = document.forms['work_order'].elements['temp_currency'+id].value
        var amount = document.forms['work_order'].elements['temp_amount'+id].value
        var agreement_number = document.forms['work_order'].elements['temp_agreement_number'+id].value
        var quotation_number = document.forms['work_order'].elements['temp_quotation_number'+id].value
        var selling_service = document.forms['work_order'].elements['temp_selling_service'+id].value

        // append data
        $('#tableTruckingWO').append('<tr id="baris'+number+'" class="no_clone clones'+number+'"><!-- hidden --><input type="hidden" name="temp_from_name_wo'+number+'" value="'+from_name_short+'"><input type="hidden" name="temp_to_name_wo'+number+'" value="'+to_name_short+'"><input type="hidden" name="temp_to_wo'+number+'" value="'+to+'"><input type="hidden" name="temp_from_wo'+number+'" value="'+from+'"><input type="hidden" name="temp_size_wo'+number+'" value="'+size+'"><input type="hidden" name="temp_type_wo'+number+'" value="'+type+'"><input type="hidden" name="temp_category_wo'+number+'" value="'+category+'"><input type="hidden" name="temp_currency_wo'+number+'" value="'+currency+'"><input type="hidden" name="temp_amount_wo'+number+'" value="'+amount+'"><input type="hidden" name="temp_agreement_number_wo'+number+'" value="'+agreement_number+'"><input type="hidden" name="temp_quotation_number_wo'+number+'" value="'+quotation_number+'"><input type="hidden" name="temp_selling_service_wo'+number+'" value="'+selling_service+'"><!-- hidden for insert data --><input type="hidden" name="trucking['+number+'][to_location_id]" value="'+to+'"><input type="hidden" name="trucking['+number+'][from_location_id]" value="'+from+'"><input type="hidden" name="trucking['+number+'][container_size_id]" value="'+size+'"><input type="hidden" name="trucking['+number+'][container_type_id]" value="'+type+'"><input type="hidden" name="trucking['+number+'][container_category_id]" value="'+category+'"><input type="hidden" name="trucking['+number+'][currency]" value="'+currency+'"><input type="hidden" name="trucking['+number+'][amount]" value="'+amount+'"><input type="hidden" name="trucking['+number+'][agreement_number]" value="'+agreement_number+'"><input type="hidden" name="trucking['+number+'][quotation_number]" value="'+quotation_number+'"><input type="hidden" name="trucking['+number+'][selling_service_id]" value="'+selling_service+'"><td class="text-center"><button type="button" class="btn btn-danger btn_remove" id="'+number+'" data-id="'+id+'"><span class="glyphicon glyphicon-minus"></span></button><button type="button" class="btn btn-success btn_add" id="'+number+'" onClick="return cloneTrucking('+"'"+number+"'"+')"><span class="glyphicon glyphicon-plus"></span></button></td><td class="text-center">'+from_name_short+'</td><td class="text-center">'+to_name_short+'</td><td class="text-center">'+size+'</td><td class="text-center">'+type+'</td><td class="text-center">'+category+'</td><td class="text-right">'+currency+"  "+toRp(amount)+'</td><td class="text-center"><input type="text" name="trucking['+number+'][container_no]" class="form-control" value="<?php echo set_value('container_no'); ?>" /></td><td class="text-center"><input type="text" name="trucking['+number+'][seal_no]" class="form-control" value="<?php echo set_value('seal_no'); ?>" /></td><td class="text-center"><input type="text" name="trucking['+number+'][bl_no]" class="form-control" value="<?php echo set_value('bl_no'); ?>" /></td></tr>');
        
        document.getElementById("add_agreement"+id).disabled = true;
        
        number++;
    }

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      var but_id = $(this).attr("data-id");
      $('#baris'+button_id+'').remove();
      document.getElementById("add_agreement"+but_id).disabled = false;
    });

    $(document).on('click', '.btn_remove_agree', function(){
      var id_agree = $(this).attr("id");
      $('#baris'+id_agree+'').remove();
    });

</script>


<?php
    $this->load->view('layouts/footer.php');
?>
