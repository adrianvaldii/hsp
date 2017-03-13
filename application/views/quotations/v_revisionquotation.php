<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

// currency
$this->load->helper('currency_helper');

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 950)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);

$attributes_compare = array(
    'width'     =>  '300',
    'height'    =>  '300',
    'screenx'   =>  '\'+((parseInt(screen.width) - 300)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 550)/2)+\'',
);

$attr_form = array(
                'name' => 'quotation'
             );
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Revision Quotation</h3>
            <hr>
        </div>
    </div>
    <?php echo form_open('Quotation/revision_quotation/'.$quotation_number2, $attr_form); ?>
        <div class="panel panel-default">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <?php if(validation_errors()) { ?>
                    <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo validation_errors(); ?>
                    </div>
                <?php } ?>

                <?php if(isset($truck_error) && $truck_error == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data trucking not valid. Please check data before revision!"; ?>
                    </div>
                <?php } ?>

                <?php if(isset($customs_error) && $customs_error == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data Customs Clearance not valid. Please check data before revision!"; ?>
                    </div>
                <?php } ?>

                <?php if(isset($location_error) && $location_error == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data Non Trailler not valid. Please check data before revision!"; ?>
                    </div>
                <?php } ?>

                <?php if(isset($weight_error) && $weight_error == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data Weight Measurement not valid. Please check data before revision!"; ?>
                    </div>
                <?php } ?>

                <?php if(isset($ocean_error) && $ocean_error == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data Ocean Freight not valid. Please check data before revision!"; ?>
                    </div>
                <?php } ?>

                <?php if(isset($error_trucking) && $error_trucking == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data trucking empty!" ?>
                    </div>
                <?php } ?>

                <?php if(isset($error_customs) && $error_customs == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data customs empty!" ?>
                    </div>
                <?php } ?>

                <?php if(isset($error_location) && $error_location == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data non trailler empty!" ?>
                    </div>
                <?php } ?>

                <?php if(isset($error_weight) && $error_weight == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data weight measurement empty!" ?>
                    </div>
                <?php } ?>

                <?php if(isset($error_ocean) && $error_ocean == "error") { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "Data ocean freight empty!" ?>
                    </div>
                <?php } ?>

                <?php if($this->session->flashdata('failed_revision_quotation')) { ?>
                    <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('failed_revision_quotation'); ?>
                    </div>
                <?php } ?>
                
                <?php if($this->session->flashdata('success_revision_quotation')) { ?>
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('success_revision_quotation'); ?>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <button class="btn btn-success">Save</button>
                    <button class="btn btn-danger">Cancel</button>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quotation</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Quotation Number<span style="color:red">*</span></label>
                                                    <input type="text" name="quotation_document_number" class="form-control" value="<?php echo $document_number; ?>" readonly="true">
                                                    <input type="hidden" name="quotation_number" class="form-control" value="<?php echo $quotation_number2; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company<span style="color:red">*</span></label>
                                                    <input type="text" name="company_name" class="form-control" id="company" readonly="true" value="<?php echo $customer_name_revision; ?>">

                                                    <!-- hidden -->
                                                    <input type="hidden" name="company_id" value="<?php echo $customer_id_revision; ?>">
                                                </div>  
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales<span style="color:red">*</span></label>
                                                    <input type="text" name="sales_id" class="form-control" value="<?php echo $marketing_id; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Remarks</label>
                                                    <textarea name="remarks" class="form-control" style="text-align:left;">
                                                        <?php echo $remarks; ?>
                                                    </textarea>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Start Date<span style="color:red">*</span></label>
                                                    <input type="text" name="start_date" class="form-control" value="<?php echo $start_date_revision; ?>" id="start_date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>End Date<span style="color:red">*</span></label>
                                                    <input type="text" name="end_date" class="form-control" id="end_date" value="<?php echo $end_date_revision; ?>">
                                                </div>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Syarat dan Kondisi<span style="color:red">*</span></div>
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <textarea class="form-control" name="template_text1" id="text-indonesia">
                                                                <?php echo $template_text1; ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Term and Condition<span style="color:red">*</span></div>
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <textarea class="form-control" name="template_text2" id="text-inggris">
                                                                <?php echo $template_text2; ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h5><strong>Services</strong></h5>
                                            <?php
                                                if ($combo_trucking > 0) {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="trucking" id="service-trucking" checked>
                                                            Trailer Trucking
                                                          </label>
                                                        </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="trucking" id="service-trucking">
                                                            Trailer Trucking
                                                          </label>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                            <?php
                                                if ($combo_customs > 0) {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="customs" id="service-customs" checked>
                                                            Container Custom Clearance
                                                          </label>
                                                        </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="customs" id="service-customs">
                                                            Container Custom Clearance
                                                          </label>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                            <?php
                                                if ($combo_location > 0) {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="location" id="service-location" checked>
                                                            Non Trailer Trucking
                                                          </label>
                                                        </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="location" id="service-location">
                                                            Non Trailer Trucking
                                                          </label>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                            <?php
                                                if ($combo_weight > 0) {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="weight" id="service-weight" checked>
                                                            Weight Measurement Trucking
                                                          </label>
                                                        </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="weight" id="service-weight">
                                                            Weight Measurement Trucking
                                                          </label>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                            <?php
                                                if ($combo_ocean > 0) {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="ocean" id="service-ocean" checked>
                                                            Freight
                                                          </label>
                                                        </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <div class="checkbox">
                                                          <label>
                                                            <input type="checkbox" name="service[]" value="ocean" id="service-ocean">
                                                            Freight
                                                          </label>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Selling</div>
                                    <div class="panel-body">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default" id="div-trucking">
                                                <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                      Trailler Trucking
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" class="display" id="tableTempTrucking" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="nosort text-center">From / To</th>
                                                                    <th class="nosort text-center">Container</th>
                                                                    <th class="nosort text-center">Category</th>
                                                                    <th class="nosort text-center">Qty</th>
                                                                    <th class="nosort text-center">Tariff</th>
                                                                    <th class="nosort text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    $loop_code = 0; 
                                                                    foreach ($data_trucking as $key => $value) {
                                                                        ?>
                                                                            <tr class="fit">
                                                                                <td class="fit">
                                                                                    <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?>
                                                                                    <!-- declare input hidden -->
                                                                            <input type="hidden" name="temp_from<?php echo $loop_code; ?>" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                            <input type="hidden" name="temp_to<?php echo $loop_code; ?>" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                            <input type="hidden" name="temp_company<?php echo $loop_code; ?>" value="<?php echo $value->COMPANY_ID; ?>">
                                                                            <input type="hidden" name="temp_service<?php echo $loop_code; ?>" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                            <input type="hidden" name="temp_size<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_SIZE_ID; ?>">
                                                                            <input type="hidden" name="temp_type<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_TYPE_ID; ?>">
                                                                            <input type="hidden" name="temp_category<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>">
                                                                            <input type="hidden" name="temp_fromqty<?php echo $loop_code; ?>" value="<?php echo $value->FROM_QTY; ?>">
                                                                            <input type="hidden" name="temp_toqty<?php echo $loop_code; ?>" value="<?php echo $value->TO_QTY; ?>">
                                                                            <input type="hidden" name="temp_start<?php echo $loop_code; ?>" value="<?php echo $value->START_DATE; ?>">
                                                                            <input type="hidden" name="temp_end<?php echo $loop_code; ?>" value="<?php echo $value->END_DATE; ?>">
                                                                            <input type="hidden" name="temp_currency<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_CURRENCY; ?>">
                                                                            <input type="hidden" name="temp_amount<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_AMOUNT; ?>">
                                                                            <input type="hidden" name="temp_calc<?php echo $loop_code; ?>" value="<?php echo $value->CALC_TYPE; ?>">
                                                                            <input type="hidden" name="temp_increment<?php echo $loop_code; ?>" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                            <input type="hidden" name="temp_from_name<?php echo $loop_code; ?>" value="<?php echo $value->FROM_NAME; ?>">
                                                                            <input type="hidden" name="temp_to_name<?php echo $loop_code; ?>" value="<?php echo $value->TO_NAME; ?>">

                                                                                </td>
                                                                                <td class="fit" style="text-align: center;">
                                                                                    <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID; ?>
                                                                                </td>
                                                                                <td class="fit" style="text-align: center;">
                                                                                    <?php echo $value->CONTAINER_CATEGORY_ID; ?>
                                                                                </td>
                                                                                <td class="fit">
                                                                                    <?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?>
                                                                                </td>
                                                                                <td class="fit" style="text-align: right;">
                                                                                    <?php echo currency($value->TARIFF_AMOUNT); ?>
                                                                                    <br>
                                                                                    <?php echo anchor_popup('Quotation/vendor_detail/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$value->FROM_QTY.'/'.$value->TO_QTY,'Compare', $attributes); ?>
                                                                                </td>
                                                                                <?php
                                                                                    if ($value->TOTAL > 0) {
                                                                                        ?>
                                                                                            <td class="fit" style="text-align: center;"><button type="button" class="addRow addclick btn btn-primary" id="clickTrucking<?php echo $loop_code; ?>" onClick="addTrucking('<?php echo $loop_code; ?>')">Add</button></td>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                            <td class="fit" style="text-align: center;">
                                                                                                <button class="addRow addclick btn btn-primary" disabled id="clickTrucking<?php echo $loop_code; ?>" onClick="addTrucking('<?php echo $loop_code; ?>')">Add</button>
                                                                                                <br>
                                                                                                <p style="color:red">Cost Empty</p>
                                                                                            </td>
                                                                                        <?php
                                                                                    }
                                                                                ?>
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
                                          </div>
                                          <div class="panel panel-default" id="div-customs">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                      Container Customs Clearance
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                <div class="panel-body">
                                                    <table class="table table-striped table-bordered display fit" id="tableTempCustoms" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="nosort text-center">Location</th>
                                                                <th class="nosort text-center">Customs</th>
                                                                <th class="nosort text-center">Category</th>
                                                                <th class="nosort text-center">Qty</th>
                                                                <th class="nosort text-center">Tariff</th>
                                                                <th class="nosort text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $loop_code = 0; 
                                                                foreach ($data_customs as $key => $value) {
                                                                    ?>
                                                                        <tr class="fit">
                                                                            <td class="fit">
                                                                                <?php echo $value->CUSTOM_LOCATION_ID; ?>
                                                                                <!-- declare input hidden -->
                                                                        <input type="hidden" name="temp_customs_service<?php echo $loop_code; ?>" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_from<?php echo $loop_code; ?>" value="<?php echo $value->CUSTOM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_company<?php echo $loop_code; ?>" value="<?php echo $value->COMPANY_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_line<?php echo $loop_code; ?>" value="<?php echo $value->CUSTOM_LINE_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_kind<?php echo $loop_code; ?>" value="<?php echo $value->CUSTOM_KIND_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_size<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_SIZE_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_type<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_TYPE_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_category<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>">
                                                                        <input type="hidden" name="temp_customs_fromqty<?php echo $loop_code; ?>" value="<?php echo $value->FROM_QTY; ?>">
                                                                        <input type="hidden" name="temp_customs_toqty<?php echo $loop_code; ?>" value="<?php echo $value->TO_QTY; ?>">
                                                                        <input type="hidden" name="temp_customs_start<?php echo $loop_code; ?>" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="temp_customs_end<?php echo $loop_code; ?>" value="<?php echo $value->END_DATE; ?>">
                                                                        <input type="hidden" name="temp_customs_currency<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_CURRENCY; ?>">
                                                                        <input type="hidden" name="temp_customs_amount<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_AMOUNT; ?>">
                                                                        <input type="hidden" name="temp_customs_calc<?php echo $loop_code; ?>" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="temp_customs_increment<?php echo $loop_code; ?>" value="<?php echo $value->INCREMENT_QTY; ?>">

                                                                            </td>
                                                                            <td class="fit" style="text-align: center;">
                                                                                <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CUSTOM_KIND_ID . " - " . $value->CUSTOM_LINE_ID; ?>
                                                                            </td>
                                                                            <td class="fit" style="text-align: center;">
                                                                                <?php echo $value->CONTAINER_CATEGORY_ID; ?>
                                                                            </td>
                                                                            <td class="fit">
                                                                                <?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?>
                                                                            </td>
                                                                            <td class="fit" style="text-align: right;">
                                                                                <?php echo currency($value->TARIFF_AMOUNT); ?>
                                                                                <br>
                                                                                <?php echo anchor_popup('Quotation/compare/'.$value->CUSTOM_LOCATION_ID.'/'.$value->CUSTOM_LINE_ID.'/'.$value->CUSTOM_KIND_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$value->FROM_QTY.'/'.$value->TO_QTY,'Compare', $attributes_compare); ?>
                                                                            </td>
                                                                            <?php
                                                                                if ($value->TOTAL > 0) {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button type="button" class="addRow addclick btn btn-primary" id="clickCustoms<?php echo $loop_code; ?>" onClick="addCustoms('<?php echo $loop_code; ?>')">Add</button></td>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button class="addRow addclick btn btn-primary" disabled id="clickCustoms<?php echo $loop_code; ?>" onClick="addCustoms('<?php echo $loop_code; ?>')">Add</button>
                                                                                            <br>
                                                                                            <p style="color:red">Cost Empty</p>
                                                                                        </td>
                                                                                    <?php
                                                                                }
                                                                            ?>
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
                                          <div class="panel panel-default" id="div-location">
                                            <div class="panel-heading" role="tab">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                  Non Trailler Trucking
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                              <div class="panel-body">
                                                <table class="table table-striped table-bordered display fit" id="tableTempLocation" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="nosort text-center">From / To</th>
                                                                <th class="nosort text-center">Truck</th>
                                                                <th class="nosort text-center">Distance</th>
                                                                <th class="nosort text-center">Tariff</th>
                                                                <th class="nosort text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $loop_code = 0; 
                                                                foreach ($data_location as $key => $value) {
                                                                    ?>
                                                                        <tr class="fit">
                                                                            <td class="fit">
                                                                                <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?>
                                                                                <!-- declare input hidden -->
                                                                        <input type="hidden" name="temp_location_company_service<?php echo $loop_code; ?>" value="<?php echo $value->COMPANY_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_location_service<?php echo $loop_code; ?>" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_location_from<?php echo $loop_code; ?>" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_location_to<?php echo $loop_code; ?>" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_location_truck<?php echo $loop_code; ?>" value="<?php echo $value->TRUCK_ID; ?>">
                                                                        <input type="hidden" name="temp_location_start<?php echo $loop_code; ?>" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="temp_location_end<?php echo $loop_code; ?>" value="<?php echo $value->END_DATE; ?>">
                                                                        <input type="hidden" name="temp_location_increment<?php echo $loop_code; ?>" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="temp_location_distance<?php echo $loop_code; ?>" value="<?php echo $value->DISTANCE; ?>">
                                                                        <input type="hidden" name="temp_location_distanceliter<?php echo $loop_code; ?>" value="<?php echo $value->DISTANCE_PER_LITRE; ?>">
                                                                        <input type="hidden" name="temp_location_currency<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_CURRENCY; ?>">
                                                                        <input type="hidden" name="temp_location_amount<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_AMOUNT; ?>">
                                                                        <input type="hidden" name="temp_location_calc<?php echo $loop_code; ?>" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="temp_location_from_name<?php echo $loop_code; ?>" value="<?php echo $value->FROM_NAME; ?>">
                                                                        <input type="hidden" name="temp_location_to_name<?php echo $loop_code; ?>" value="<?php echo $value->TO_NAME; ?>">
                                                                        <input type="hidden" name="temp_location_truck_name<?php echo $loop_code; ?>" value="<?php echo $value->TRUCK_NAME; ?>">
                                                                            </td>
                                                                            <td class="fit" style="text-align: center;">
                                                                                <?php echo $value->TRUCK_NAME; ?>
                                                                            </td>
                                                                            <td class="fit" style="text-align: center;">
                                                                                <?php echo $value->DISTANCE . " Km - " . $value->DISTANCE_PER_LITRE . " Lt"; ?>
                                                                            </td>
                                                                            <td class="fit" style="text-align: right;">
                                                                                <?php echo currency($value->TARIFF_AMOUNT); ?>
                                                                                <br>
                                                                                
                                                                            </td>
                                                                            <?php
                                                                                if ($value->TOTAL > 0) {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button type="button" class="addRow addclick btn btn-primary" id="clickLocation<?php echo $loop_code; ?>" onClick="addLocation('<?php echo $loop_code; ?>')">Add</button></td>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button class="addRow addclick btn btn-primary" disabled id="clickLocation<?php echo $loop_code; ?>" onClick="addLocation('<?php echo $loop_code; ?>')">Add</button>
                                                                                            <br>
                                                                                            <p style="color:red">Cost Empty</p>
                                                                                        </td>
                                                                                    <?php
                                                                                }
                                                                            ?>
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
                                          <!-- weight service -->
                                          <div class="panel panel-default" id="div-weight">
                                            <div class="panel-heading" role="tab">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                  Weight Measurement
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                              <div class="panel-body">
                                                <table class="table table-striped table-bordered display fit" id="tableTempWeight" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="nosort text-center">From / To</th>
                                                                <th class="nosort text-center">Weight</th>
                                                                <th class="nosort text-center">Tariff</th>
                                                                <th class="nosort text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $loop_code = 0; 
                                                                foreach ($data_weight as $key => $value) {
                                                                    ?>
                                                                        <tr class="fit">
                                                                            <td class="fit">
                                                                                <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?>
                                                                                <!-- declare input hidden -->
                                                                        <input type="hidden" name="temp_weight_from<?php echo $loop_code; ?>" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_weight_to<?php echo $loop_code; ?>" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_weight_company<?php echo $loop_code; ?>" value="<?php echo $value->COMPANY_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_weight_service<?php echo $loop_code; ?>" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_weight_fromweight<?php echo $loop_code; ?>" value="<?php echo $value->FROM_WEIGHT; ?>">
                                                                        <input type="hidden" name="temp_weight_toweight<?php echo $loop_code; ?>" value="<?php echo $value->TO_WEIGHT; ?>">
                                                                        <input type="hidden" name="temp_weight_start<?php echo $loop_code; ?>" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="temp_weight_end<?php echo $loop_code; ?>" value="<?php echo $value->END_DATE; ?>">
                                                                        <input type="hidden" name="temp_weight_currency<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_CURRENCY; ?>">
                                                                        <input type="hidden" name="temp_weight_amount<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_AMOUNT; ?>">
                                                                        <input type="hidden" name="temp_weight_calc<?php echo $loop_code; ?>" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="temp_weight_increment<?php echo $loop_code; ?>" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="temp_weight_from_name<?php echo $loop_code; ?>" value="<?php echo $value->FROM_NAME; ?>">
                                                                        <input type="hidden" name="temp_weight_to_name<?php echo $loop_code; ?>" value="<?php echo $value->TO_NAME; ?>">
                                                                        <input type="hidden" name="temp_weight_measurement<?php echo $loop_code; ?>" value="<?php echo $value->MEASUREMENT_UNIT; ?>">

                                                                            </td>
                                                                            <td class="fit">
                                                                                <?php echo $value->FROM_WEIGHT . " - " . $value->TO_WEIGHT . " " . $value->MEASUREMENT_UNIT; ?>
                                                                            </td>
                                                                            <td class="fit" style="text-align: right;">
                                                                                <?php echo currency($value->TARIFF_AMOUNT); ?>
                                                                            </td>
                                                                            <?php
                                                                                if ($value->TOTAL > 0) {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button type="button" class="addRow addclick btn btn-primary" id="clickWeight<?php echo $loop_code; ?>" onClick="addWeight('<?php echo $loop_code; ?>')">Add</button></td>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button class="addRow addclick btn btn-primary" disabled id="clickWeight<?php echo $loop_code; ?>" onClick="addWeight('<?php echo $loop_code; ?>')">Add</button>
                                                                                            <br>
                                                                                            <p style="color:red">Cost Empty</p>
                                                                                        </td>
                                                                                    <?php
                                                                                }
                                                                            ?>
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
                                          <!-- weight service -->
                                          <div class="panel panel-default" id="div-ocean">
                                            <div class="panel-heading" role="tab">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                  Ocean Freight
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                              <div class="panel-body">
                                                <table class="table table-striped table-bordered display fit" id="tableTempOcean" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="nosort text-center">From / To</th>
                                                                <th class="nosort text-center">Charge Kind</th>
                                                                <th class="nosort text-center">Size</th>
                                                                <th class="nosort text-center">Qty</th>
                                                                <th class="nosort text-center">Tariff</th>
                                                                <th class="nosort text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $loop_code = 0; 
                                                                foreach ($data_ocean as $key => $value) {
                                                                    ?>
                                                                        <tr class="fit">
                                                                            <td class="fit">
                                                                                <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?>
                                                                                <!-- declare input hidden -->
                                                                        <input type="hidden" name="temp_ocean_company<?php echo $loop_code; ?>" value="<?php echo $value->COMPANY_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_service<?php echo $loop_code; ?>" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_from<?php echo $loop_code; ?>" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_to<?php echo $loop_code; ?>" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_charge<?php echo $loop_code; ?>" value="<?php echo $value->CHARGE_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_size<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_SIZE_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_type<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_TYPE_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_category<?php echo $loop_code; ?>" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>">
                                                                        <input type="hidden" name="temp_ocean_fromqty<?php echo $loop_code; ?>" value="<?php echo $value->FROM_QTY; ?>">
                                                                        <input type="hidden" name="temp_ocean_toqty<?php echo $loop_code; ?>" value="<?php echo $value->TO_QTY; ?>">
                                                                        <input type="hidden" name="temp_ocean_start<?php echo $loop_code; ?>" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="temp_ocean_end<?php echo $loop_code; ?>" value="<?php echo $value->END_DATE; ?>">
                                                                        <input type="hidden" name="temp_ocean_currency<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_CURRENCY; ?>">
                                                                        <input type="hidden" name="temp_ocean_amount<?php echo $loop_code; ?>" value="<?php echo $value->TARIFF_AMOUNT; ?>">
                                                                        <input type="hidden" name="temp_ocean_calc<?php echo $loop_code; ?>" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="temp_ocean_increment<?php echo $loop_code; ?>" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="temp_ocean_from_name<?php echo $loop_code; ?>" value="<?php echo $value->FROM_NAME; ?>">
                                                                        <input type="hidden" name="temp_ocean_to_name<?php echo $loop_code; ?>" value="<?php echo $value->TO_NAME; ?>">

                                                                            </td>
                                                                            <td class="fit">
                                                                                <?php echo $value->CHARGE_ID; ?>
                                                                            </td>
                                                                            <td class="fit">
                                                                                <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID. " - " . $value->CONTAINER_CATEGORY_ID; ?>
                                                                            </td>
                                                                            <td class="fit">
                                                                                <?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?>
                                                                            </td>
                                                                            <td class="fit" style="text-align: right;">
                                                                                <?php echo currency($value->TARIFF_AMOUNT); ?>
                                                                            </td>
                                                                            <?php
                                                                                if ($value->TOTAL > 0) {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button type="button" class="addRow addclick btn btn-primary" id="clickOcean<?php echo $loop_code; ?>" onClick="addOcean('<?php echo $loop_code; ?>')">Add</button></td>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <td class="fit" style="text-align: center;"><button class="addRow addclick btn btn-primary" disabled id="clickOcean<?php echo $loop_code; ?>" onClick="addOcean('<?php echo $loop_code; ?>')">Add</button>
                                                                                            <br>
                                                                                            <p style="color:red">Cost Empty</p>
                                                                                        </td>
                                                                                    <?php
                                                                                }
                                                                            ?>
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
                                        </div>
                                        <!-- batas -->
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quotation Detail</div>
                                    <div class="panel-body">
                                        <!-- quotation -->
                                        <div class="panel-group" id="allquotation" role="tablist" aria-multiselectable="true">
                                          <div class="panel panel-default" id="divtruck">
                                            <div class="panel-heading" role="tab" id="quotationtruck">
                                              <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#allquotation" href="#quotetruck" aria-expanded="true" aria-controls="quotetruck">
                                                  Trailler Trucking
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="quotetruck" class="panel-collapse collapse" role="tabpanel" aria-labelledby="quotationtruck">
                                              <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped" id="tableTrucking">
                                                        <tr>
                                                            <th>From / To</th>
                                                            <th>Destination</th>
                                                            <th>Size</th>
                                                            <th>Selling</th>
                                                            <th>Selling Offering</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        <?php
                                                        $id_remove = 0;
                                                        foreach ($data_trucking2 as $key => $value) {
                                                            ?>
                                                                <tr id="<?php echo $id_remove; ?>" class="rev<?php echo $id_remove; ?> no_rev">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][selling_service]" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][size]" value="<?php echo $value->CONTAINER_SIZE_ID; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][type]" value="<?php echo $value->CONTAINER_TYPE_ID; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][category]" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][from]" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][to]" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][currency]" value="<?php echo $value->SELLING_CURRENCY; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][amount]" value="<?php echo $value->SELLING_STANDART_RATE; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][from_qty]" value="<?php echo $value->FROM_QTY; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][to_qty]" value="<?php echo $value->TO_QTY; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][calc]" value="<?php echo $value->CALC_TYPE; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][increment]" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][start_date]" value="<?php echo $value->START_DATE; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][end_date]" value="<?php echo $value->END_DATE; ?>">
                                                                    <input type="hidden" name="trucking[<?php echo $id_remove; ?>][company_id]" value="<?php echo $value->COMPANY_ID; ?>">

                                                                    <td> <?php echo $value->FROM_NAME; ?> </td>
                                                                    <td> <?php echo $value->TO_NAME; ?> </td>
                                                                    <td> <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?> </td>
                                                                    <td style="text-align:right"> <?php echo currency($value->SELLING_STANDART_RATE); ?> </td>
                                                                    <td><input type="number" pattern="(\d{3})" name="trucking[<?php echo $id_remove; ?>][offer_price]" style="text-align: center;" value="<?php echo $value->SELLING_OFFERING_RATE; ?>"></td>
                                                                    <td class="fit" style="text-align: center;"><a class="addRow addclick" id="clickTrucking<?php echo $id_remove; ?>" onClick="removeTrucking('<?php echo $id_remove; ?>')">Remove</a> | <?php echo anchor_popup('Quotation/cost_detail_trucking/'.$value->SELLING_SERVICE_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->COMPANY_ID, 'Cost Detail', $attributes) ?></td>
                                                                </tr>
                                                            <?php
                                                            $id_remove++;
                                                        }
                                                        ?>
                                                    </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="panel panel-default" id="divcustoms">
                                            <div class="panel-heading" role="tab" id="quotationcustoms">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#allquotation" href="#quotecustoms" aria-expanded="false" aria-controls="quotecustoms">
                                                  Container Customs Clearance
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="quotecustoms" class="panel-collapse collapse" role="tabpanel" aria-labelledby="quotationcustoms">
                                              <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table" id="tableCustoms">
                                                        <tr>
                                                            <th class="nosort text-center">Location</th>
                                                            <th class="nosort text-center">Customs</th>
                                                            <th class="nosort text-center">Category</th>
                                                            <th class="nosort text-center">Qty</th>
                                                            <th class="nosort text-center">Selling</th>
                                                            <th class="nosort text-center">Selling Offering</th>
                                                            <th class="nosort text-center">Action</th>
                                                        </tr>
                                                        <?php
                                                            $id_remove_customs = 0;
                                                            foreach ($data_customs2 as $key => $value) {
                                                                ?>
                                                                    <tr id="<?php echo $id_remove_customs; ?>" class="revcustoms<?php echo $id_remove_customs; ?> no_rev_customs">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][selling_customs]" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][size_customs]" value="<?php echo $value->CONTAINER_SIZE_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][type_customs]" value="<?php echo $value->CONTAINER_TYPE_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][line_customs]" value="<?php echo $value->CUSTOM_LINE_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][kind_customs]" value="<?php echo $value->CUSTOM_KIND_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][category_customs]" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][from_customs]" value="<?php echo $value->CUSTOM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][currency_customs]" value="<?php echo $value->SELLING_CURRENCY; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][amount_customs]" value="<?php echo $value->SELLING_STANDART_RATE; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][from_qty_customs]" value="<?php echo $value->FROM_QTY; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][to_qty_customs]" value="<?php echo $value->TO_QTY; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][calc_customs]" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][increment_customs]" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][start_customs]" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][end_customs]" value="<?php echo $value->END_DATE; ?>">
                                                                        <input type="hidden" name="customs[<?php echo $id_remove_customs; ?>][company_id]" value="<?php echo $value->COMPANY_ID; ?>">
                                                                        <td style="text-align: center;"><?php echo $value->CUSTOM_LOCATION_ID; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CUSTOM_LINE_ID . " - " . $value->CUSTOM_KIND_ID; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->CONTAINER_CATEGORY_ID; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?></td>
                                                                        <td style="text-align: right;"><?php echo currency($value->SELLING_STANDART_RATE); ?></td>
                                                                        <td style="text-align: center;">
                                                                            <input type="number" name="customs[<?php echo $id_remove_customs; ?>][offer_customs]" style="text-align: center;" value="<?php echo $value->SELLING_OFFERING_RATE; ?>">
                                                                        </td>
                                                                        <td class="fit" style="text-align: center;"><a class="addRow addclick" id="clickCustoms<?php echo $id_remove_customs; ?>" onClick="removeCustoms('<?php echo $id_remove_customs; ?>')">Remove</a> | <?php echo anchor_popup('Quotation/cost_detail_customs/'.$value->SELLING_SERVICE_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CUSTOM_LOCATION_ID.'/'.$value->CUSTOM_LINE_ID.'/'.$value->CUSTOM_KIND_ID.'/'.$value->COMPANY_ID, 'Cost Detail', $attributes) ?></td>
                                                                    </tr>
                                                                <?php
                                                                $id_remove_customs++;
                                                            }
                                                        ?>
                                                    </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="panel panel-default" id="divlocation">
                                            <div class="panel-heading" role="tab" id="quotationlocation">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#allquotation" href="#quotelocation" aria-expanded="false" aria-controls="quotelocation">
                                                  Non Trailler Trucking
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="quotelocation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="quotationlocation">
                                              <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table" id="tableLocation">
                                                        <tr>
                                                            <th class="nosort text-center">From / To</th>
                                                            <th class="nosort text-center">Truck</th>
                                                            <th class="nosort text-center">Distance</th>
                                                            <th class="nosort text-center">Distance in Litre</th>
                                                            <th class="nosort text-center">Selling</th>
                                                            <th class="nosort text-center">Selling Offering</th>
                                                            <th class="nosort text-center">Action</th>
                                                        </tr>
                                                        <?php
                                                            $id_remove_location = 0;
                                                            foreach ($data_location2 as $key => $value) {
                                                                ?>
                                                                    <tr id="<?php echo $id_remove_location; ?>" class="revlocation<?php echo $id_remove_location; ?> no_rev_location">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][selling_service]" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][from]" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][to]" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][truck]" value="<?php echo $value->TRUCK_KIND_ID; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][distance]" value="<?php echo $value->DISTANCE; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][distanceliter]" value="<?php echo $value->DISTANCE_PER_LITRE; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][currency]" value="<?php echo $value->SELLING_CURRENCY; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][amount]" value="<?php echo $value->SELLING_STANDART_RATE; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][calc]" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][increment]" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][start_date]" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="location[<?php echo $id_remove_location; ?>][end_date]" value="<?php echo $value->END_DATE; ?>">
                                                                        <td style="text-align: center;"><?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->TRUCK_NAME; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->DISTANCE . " Km"; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->DISTANCE_PER_LITRE . " Lt"; ?></td>
                                                                        <td style="text-align: right;"><?php echo currency($value->SELLING_STANDART_RATE); ?></td>
                                                                        <td style="text-align: center;">
                                                                            <input type="number" name="location[<?php echo $id_remove_location; ?>][offer_price]" style="text-align: center;" value="<?php echo $value->SELLING_OFFERING_RATE; ?>">
                                                                        </td>
                                                                        <td class="fit" style="text-align: center;"><a class="addRow addclick" id="clickLocation<?php echo $id_remove_location; ?>" onClick="removeLocation('<?php echo $id_remove_location; ?>')">Remove</a> | <?php echo anchor_popup('Quotation/cost_detail_location/'.$value->SELLING_SERVICE_ID.'/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->TRUCK_KIND_ID, 'Cost Detail', $attributes) ?></td>
                                                                    </tr>
                                                                <?php
                                                                $id_remove_location++;
                                                            }
                                                        ?>
                                                    </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- quotation weight -->
                                          <div class="panel panel-default" id="divweight">
                                            <div class="panel-heading" role="tab" id="quotationweight">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#allquotation" href="#quoteweight" aria-expanded="false" aria-controls="quoteweight">
                                                  Weight Measurement Trucking
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="quoteweight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="quotationweight">
                                              <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table" id="tableWeight">
                                                        <tr>
                                                            <th class="nosort text-center">From / To</th>
                                                            <th class="nosort text-center">Destination</th>
                                                            <th class="nosort text-center">Weight</th>
                                                            <th class="nosort text-center">Calc Type</th>
                                                            <th class="nosort text-center">Selling</th>
                                                            <th class="nosort text-center">Selling Offering</th>
                                                            <th class="nosort text-center">Action</th>
                                                        </tr>
                                                        <?php
                                                            $id_remove_weight = 0;
                                                            foreach ($data_weight2 as $key => $value) {
                                                                ?>
                                                                    <tr id="<?php echo $id_remove_weight; ?>" class="revweight<?php echo $id_remove_weight; ?> no_rev_weight">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][selling_service]" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][from]" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][to]" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][currency]" value="<?php echo $value->SELLING_CURRENCY; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][amount]" value="<?php echo $value->SELLING_STANDART_RATE; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][from_weight]" value="<?php echo $value->FROM_WEIGHT; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][to_weight]" value="<?php echo $value->TO_WEIGHT; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][measurement]" value="<?php echo $value->MEASUREMENT_UNIT; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][calc]" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][increment]" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][start_date]" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="weight[<?php echo $id_remove_weight; ?>][end_date]" value="<?php echo $value->END_DATE; ?>">
                                                                        <td style="text-align: center;"><?php echo $value->FROM_NAME; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->TO_NAME; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->FROM_WEIGHT . " - " . $value->TO_WEIGHT . " " . $value->MEASUREMENT_UNIT; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->CALC_TYPE; ?></td>
                                                                        <td style="text-align: right;"><?php echo currency($value->SELLING_STANDART_RATE); ?></td>
                                                                        <td style="text-align: center;">
                                                                            <input type="number" name="weight[<?php echo $id_remove_weight; ?>][offer_price]" style="text-align: center;" value="<?php echo $value->SELLING_OFFERING_RATE; ?>">
                                                                        </td>
                                                                        <td class="fit" style="text-align: center;"><a class="addRow addclick" id="clickWeight<?php echo $id_remove_weight; ?>" onClick="removeWeight('<?php echo $id_remove_weight; ?>')">Remove</a> | <?php echo anchor_popup('Quotation/cost_detail_weight/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID, 'Cost Detail', $attributes) ?></td>
                                                                    </tr>
                                                                <?php
                                                                $id_remove_weight++;
                                                            }
                                                        ?>
                                                    </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- quotation ocean -->
                                          <div class="panel panel-default" id="divocean">
                                            <div class="panel-heading" role="tab" id="quotationocean">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#allquotation" href="#quoteocean" aria-expanded="false" aria-controls="quoteocean">
                                                  Ocean Freight
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="quoteocean" class="panel-collapse collapse" role="tabpanel" aria-labelledby="quotationocean">
                                              <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table" id="tableOcean">
                                                        <tr>
                                                            <th class="nosort text-center">From / To</th>
                                                            <th class="nosort text-center">Charge Kind</th>
                                                            <th class="nosort text-center">Size</th>
                                                            <th class="nosort text-center">Qty</th>
                                                            <th class="nosort text-center">Selling</th>
                                                            <th class="nosort text-center">Selling Offering</th>
                                                            <th class="nosort text-center">Action</th>
                                                        </tr>
                                                        <?php
                                                            $id_remove_ocean = 0;
                                                            foreach ($data_ocean_freight2 as $key => $value) {
                                                                ?>
                                                                    <tr id="<?php echo $id_remove_ocean; ?>" class="revocean<?php echo $id_remove_ocean; ?> no_rev_ocean">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][selling_service]" value="<?php echo $value->SELLING_SERVICE_ID; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][from]" value="<?php echo $value->FROM_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][to]" value="<?php echo $value->TO_LOCATION_ID; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][currency]" value="<?php echo $value->SELLING_CURRENCY; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][amount]" value="<?php echo $value->SELLING_STANDART_RATE; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][from_qty]" value="<?php echo $value->FROM_QTY; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][to_qty]" value="<?php echo $value->TO_QTY; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][charge]" value="<?php echo $value->CHARGE_ID; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][calc]" value="<?php echo $value->CALC_TYPE; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][increment]" value="<?php echo $value->INCREMENT_QTY; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][start_date]" value="<?php echo $value->START_DATE; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][end_date]" value="<?php echo $value->END_DATE; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][size]" value="<?php echo $value->CONTAINER_SIZE_ID; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][type]" value="<?php echo $value->CONTAINER_TYPE_ID; ?>">
                                                                        <input type="hidden" name="ocean[<?php echo $id_remove_ocean; ?>][category]" value="<?php echo $value->CONTAINER_CATEGORY_ID; ?>">
                                                                        <td style="text-align: center;"><?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->CHARGE_ID; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?></td>
                                                                        <td style="text-align: center;"><?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?></td>
                                                                        <td style="text-align: right;"><?php echo currency($value->SELLING_STANDART_RATE); ?></td>
                                                                        <td style="text-align: center;">
                                                                            <input type="number" style="text-align: center;" name="ocean[<?php echo $id_remove_ocean; ?>][offer_price]" style="text-align: center;" value="<?php echo $value->SELLING_OFFERING_RATE; ?>">
                                                                        </td>
                                                                        <td class="fit" style="text-align: center;"><a class="addRow addclick" id="clickOcean<?php echo $id_remove_ocean; ?>" onClick="removeOcean('<?php echo $id_remove_ocean; ?>')">Remove</a> | <?php echo anchor_popup('Quotation/cost_detail_ocean/'.$value->SELLING_SERVICE_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CHARGE_ID, 'Cost Detail', $attributes) ?></td>
                                                                    </tr>
                                                                <?php
                                                                $id_remove_ocean++;
                                                            }
                                                        ?>
                                                    </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- quotation -->
                        </div>
                        <!-- batas row -->
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end of content -->

<!-- js -->
<?php
    $this->load->view('layouts/js.php');  
?>

<script type="text/javascript">
    var i=1;
    var i_cust = 1;
    var i_loc = 1;
    var i_wei = 1;
    var i_oce = 1;
    tinymce.init({ 
          selector:'textarea#text-indonesia',
          theme: 'modern',
          plugins: [
            'advlist autolink lists charmap',
            'searchreplace wordcount',
            'save table contextmenu directionality',
            'paste'
          ],
          toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
          toolbar2: 'print preview media | forecolor backcolor emoticons',
          image_advtab: true,
          resize: false
    });
    tinymce.init({ 
          selector:'textarea#text-inggris',
          theme: 'modern',
          plugins: [
            'advlist autolink lists charmap',
            'searchreplace wordcount',
            'save table contextmenu directionality',
            'paste'
          ],
          toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
          toolbar2: 'print preview media | forecolor backcolor emoticons',
          image_advtab: true,
          resize: false
    });
    $(document).ready(function(){
        // selling
        $("#div-trucking").hide();
        $("#div-customs").hide();
        $("#div-location").hide();
        $("#div-weight").hide();
        $("#div-ocean").hide();
        // quotation
        $("#divtruck").hide();
        $("#divcustoms").hide();
        $("#divlocation").hide();
        $("#divweight").hide();
        $("#divocean").hide();

        if($("#service-trucking").is(":checked")) {
            $("#div-trucking").show();
            $("#divtruck").show();
        } else {
            $("#div-trucking").hide();
            $("#divtruck").hide();
        }

        if($("#service-customs").is(":checked")) {
            $("#div-customs").show();
            $("#divcustoms").show();
        } else {
            $("#div-customs").hide();
            $("#divcustoms").hide();
        }

        if($("#service-location").is(":checked")) {
            $("#div-location").show();
            $("#divlocation").show();
        } else {
            $("#div-location").hide();
            $("#divlocation").hide();
        }

        if($("#service-weight").is(":checked")) {
            $("#div-weight").show();
            $("#divweight").show();
        } else {
            $("#div-weight").hide();
            $("#divweight").hide();
        }

        if($("#service-ocean").is(":checked")) {
            $("#div-ocean").show();
            $("#divocean").show();
        } else {
            $("#div-ocean").hide();
            $("#divocean").hide();
        }

        $("#service-trucking").click(function() {
            if($(this).is(":checked")) {
                $("#div-trucking").show();
                $("#divtruck").show();
            } else {
                $("#div-trucking").hide();
                $("#divtruck").hide();
            }
        });

        $("#service-customs").click(function() {
            if($(this).is(":checked")) {
                $("#div-customs").show();
                $("#divcustoms").show();
            } else {
                $("#div-customs").hide();
                $("#divcustoms").hide();
            }
        });

        $("#service-location").click(function() {
            if($(this).is(":checked")) {
                $("#div-location").show();
                $("#divlocation").show();
            } else {
                $("#div-location").hide();
                $("#divlocation").hide();
            }
        });

        $("#service-weight").click(function() {
            if($(this).is(":checked")) {
                $("#div-weight").show();
                $("#divweight").show();
            } else {
                $("#div-weight").hide();
                $("#divweight").hide();
            }
        });


        $("#service-ocean").click(function() {
            if($(this).is(":checked")) {
                $("#div-ocean").show();
                $("#divocean").show();
            } else {
                $("#div-ocean").hide();
                $("#divocean").hide();
            }
        });

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

        $('#tableTempTrucking').DataTable({
                responsive: true,
                "columnDefs": [
                    {
                        "visible": false,
                        "searchable": false
                    }
                ],
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tableTempCustoms').DataTable({
                responsive: true,
                "columnDefs": [
                    {
                        "visible": false,
                        "searchable": false
                    }
                ],
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tableTempLocation').DataTable({
                responsive: true,
                "columnDefs": [
                    {
                        "visible": false,
                        "searchable": false
                    }
                ],
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tableTempWeight').DataTable({
                responsive: true,
                "columnDefs": [
                    {
                        "visible": false,
                        "searchable": false
                    }
                ],
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tableTempOcean').DataTable({
                responsive: true,
                "columnDefs": [
                    {
                        "visible": false,
                        "searchable": false
                    }
                ],
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        // autocomplete from location
        $("#company").autocomplete({
          source: "<?php echo site_url('Quotation/search_customer'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=company_id]').val(data.item.company_id);
            $('input[name=pic_id]').val(data.item.customer_id);
            $('input[name=pic_name]').val(data.item.customer_name);
          }
        });


    });

    var no = 0;
    $('.no_rev_customs').each(function() {
        no = Math.max(this.id, no);
    });
    no = no+1;
    // console.log(no+1);

    var no_trucking = 0;
    $('.no_rev').each(function() {
        no_trucking = Math.max(this.id, no_trucking);
    });
    no_trucking = no_trucking+1;
    // console.log(no_trucking+1);
    // no_trucking = 0;
    var no_location = 0;
    $('.no_rev_location').each(function() {
        no_location = Math.max(this.id, no_location);
    });
    no_location = no_location+1;

    var no_weight = 0;
    $('.no_rev_weight').each(function() {
        no_weight = Math.max(this.id, no_weight);
    });
    no_weight = no_weight+1;

    no_ocean = 0;
    var no_ocean = 0;
    $('.no_rev_ocean').each(function() {
        no_ocean = Math.max(this.id, no_ocean);
    });
    no_ocean = no_ocean+1;

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

    function removeTrucking(id)
    {
        $('.rev'+id+'').remove();
    }

    function removeCustoms(id)
    {
        $('.revcustoms'+id+'').remove();
    }

    function removeLocation(id)
    {
        $('.revlocation'+id+'').remove();
    }

    function removeWeight(id)
    {
        $('.revweight'+id+'').remove();
    }

    function removeOcean(id)
    {
        $('.revocean'+id+'').remove();
    }

    
    function addTrucking(id)
    {
        var selling_service = document.forms['quotation'].elements['temp_service'+id].value
        var size = document.forms['quotation'].elements['temp_size'+id].value
        var type = document.forms['quotation'].elements['temp_type'+id].value
        var category = document.forms['quotation'].elements['temp_category'+id].value
        var from = document.forms['quotation'].elements['temp_from'+id].value
        var to = document.forms['quotation'].elements['temp_to'+id].value
        var amount = document.forms['quotation'].elements['temp_amount'+id].value
        var currency = document.forms['quotation'].elements['temp_currency'+id].value
        var from_qty = document.forms['quotation'].elements['temp_fromqty'+id].value
        var to_qty = document.forms['quotation'].elements['temp_toqty'+id].value
        var increment = document.forms['quotation'].elements['temp_increment'+id].value
        var calc = document.forms['quotation'].elements['temp_calc'+id].value
        var start = document.forms['quotation'].elements['temp_start'+id].value
        var end = document.forms['quotation'].elements['temp_end'+id].value
        var from_name = document.forms['quotation'].elements['temp_from_name'+id].value
        var to_name = document.forms['quotation'].elements['temp_to_name'+id].value
        var company_id = document.forms['quotation'].elements['temp_company'+id].value

        // append data
        $('#tableTrucking').append('<tr id="baris'+id+'"><input type="hidden" name="trucking['+no_trucking+'][selling_service]" value="'+selling_service+'"><input type="hidden" name="trucking['+no_trucking+'][size]" value="'+size+'"><input type="hidden" name="trucking['+no_trucking+'][type]" value="'+type+'"><input type="hidden" name="trucking['+no_trucking+'][category]" value="'+category+'"><input type="hidden" name="trucking['+no_trucking+'][from]" value="'+from+'"><input type="hidden" name="trucking['+no_trucking+'][to]" value="'+to+'"><input type="hidden" name="trucking['+no_trucking+'][currency]" value="'+currency+'"><input type="hidden" name="trucking['+no_trucking+'][amount]" value="'+amount+'"><input type="hidden" name="trucking['+no_trucking+'][from_qty]" value="'+from_qty+'"><input type="hidden" name="trucking['+no_trucking+'][to_qty]" value="'+to_qty+'"><input type="hidden" name="trucking['+no_trucking+'][calc]" value="'+calc+'"><input type="hidden" name="trucking['+no_trucking+'][increment]" value="'+increment+'"><input type="hidden" name="trucking['+no_trucking+'][start_date]" value="'+start+'"><input type="hidden" name="trucking['+no_trucking+'][end_date]" value="'+end+'"><input type="hidden" name="trucking['+no_trucking+'][company_id]" value="'+company_id+'"><td>'+from_name+'</td><td>'+to_name+'</td><td>'+size+" - "+type+" - "+category+'</td><td>'+toRp(amount)+'</td><td><input type="number" pattern="(\d{3})" name="trucking['+no_trucking+'][offer_price]" style="text-align: center;" value="'+amount+'"></td><td class="fit" style="text-align: center;"><a class="btn_remove addclick" id="'+id+'">Remove</a> | <a href="<?php echo site_url('Quotation/cost_detail_trucking'); ?>" onclick="window.open(\'<?php echo site_url('Quotation/cost_detail_trucking/'); ?>'+selling_service+'/'+type+'/'+category+'/'+from+'/'+to+'/'+size+'/'+company+'\', \'_blank\', \'width=1000,height=620,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=\'+((parseInt(screen.width) - 950)/2)+\',screeny=\'+((parseInt(screen.height) - 700)/2)+\'\'); return false;">Cost Detail</a></td></tr>');

         document.getElementById("clickTrucking"+id).disabled = true;

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
          document.getElementById("clickTrucking"+button_id).disabled = false;
        });

        no_trucking++;
    }

    var win = null;
    function NewWindow(mypage,myname,w,h,scroll){
        LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
        TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
        settings =
        'titlebar=no,copyhistory=no,toolbar=no,location=no,directories=no,status=no,menubar=no,height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
        win = window.open(mypage,myname,settings)
    }

    function addCustoms(id)
    {
        var selling_service = document.forms['quotation'].elements['temp_customs_service'+id].value
        var size = document.forms['quotation'].elements['temp_customs_size'+id].value
        var type = document.forms['quotation'].elements['temp_customs_type'+id].value
        var line = document.forms['quotation'].elements['temp_customs_line'+id].value
        var kind = document.forms['quotation'].elements['temp_customs_kind'+id].value
        var category = document.forms['quotation'].elements['temp_customs_category'+id].value
        var from = document.forms['quotation'].elements['temp_customs_from'+id].value
        var amount = document.forms['quotation'].elements['temp_customs_amount'+id].value
        var currency = document.forms['quotation'].elements['temp_customs_currency'+id].value
        var from_qty = document.forms['quotation'].elements['temp_customs_fromqty'+id].value
        var to_qty = document.forms['quotation'].elements['temp_customs_toqty'+id].value
        var increment = document.forms['quotation'].elements['temp_customs_increment'+id].value
        var calc = document.forms['quotation'].elements['temp_customs_calc'+id].value
        var start = document.forms['quotation'].elements['temp_customs_start'+id].value
        var end = document.forms['quotation'].elements['temp_customs_end'+id].value
        var company_id = document.forms['quotation'].elements['temp_customs_company'+id].value

        // append data
        $('#tableCustoms').append('<tr id="baris'+id+'"><input type="hidden" name="customs['+no+'][selling_customs]" value="'+selling_service+'"><input type="hidden" name="customs['+no+'][size_customs]" value="'+size+'"><input type="hidden" name="customs['+no+'][type_customs]" value="'+type+'"><input type="hidden" name="customs['+no+'][line_customs]" value="'+line+'"><input type="hidden" name="customs['+no+'][kind_customs]" value="'+kind+'"><input type="hidden" name="customs['+no+'][category_customs]" value="'+category+'"><input type="hidden" name="customs['+no+'][from_customs]" value="'+from+'"><input type="hidden" name="customs['+no+'][currency_customs]" value="'+currency+'"><input type="hidden" name="customs['+no+'][amount_customs]" value="'+amount+'"><input type="hidden" name="customs['+no+'][from_qty_customs]" value="'+from_qty+'"><input type="hidden" name="customs['+no+'][to_qty_customs]" value="'+to_qty+'"><input type="hidden" name="customs['+no+'][calc_customs]" value="'+calc+'"><input type="hidden" name="customs['+no+'][increment_customs]" value="'+increment+'"><input type="hidden" name="customs['+no+'][start_customs]" value="'+start+'"><input type="hidden" name="customs['+no+'][end_customs]" value="'+end+'"><input type="hidden" name="customs['+no+'][company_id]" value="'+company_id+'"><td style="text-align: center;">'+from+'</td><td style="text-align: center;">'+size+" - "+type+" - "+line+" - "+kind+'</td><td style="text-align: center;">'+category+'</td><td style="text-align: center;">'+from_qty+ " - "+to_qty+'</td><td style="text-align: right;">'+toRp(amount)+'</td><td style="text-align: center;"><input type="number" name="customs['+no+'][offer_customs]" style="text-align: center;" value="'+amount+'"></td><td class="fit" style="text-align: center;"><a class="btn_remove addclick" id="'+id+'">Remove</a> | <a href="<?php echo site_url('Quotation/cost_detail_customs'); ?>" onclick="window.open(\'<?php echo site_url('Quotation/cost_detail_customs/'); ?>'+selling_service+'/'+type+'/'+category+'/'+size+'/'+from+'/'+line+'/'+kind+'/'+company+'\', \'_blank\', \'width=1000,height=620,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=\'+((parseInt(screen.width) - 950)/2)+\',screeny=\'+((parseInt(screen.height) - 700)/2)+\'\'); return false;">Cost Detail</a></td></tr>');

        document.getElementById("clickCustoms"+id).disabled = true;

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
          document.getElementById("clickCustoms"+button_id).disabled = false;
        });

        no++;
    }

    function addLocation(id)
    {
        var selling_service = document.forms['quotation'].elements['temp_location_service'+id].value
        var from = document.forms['quotation'].elements['temp_location_from'+id].value
        var to = document.forms['quotation'].elements['temp_location_to'+id].value
        var truck = document.forms['quotation'].elements['temp_location_truck'+id].value
        var amount = document.forms['quotation'].elements['temp_location_amount'+id].value
        var currency = document.forms['quotation'].elements['temp_location_currency'+id].value
        var increment = document.forms['quotation'].elements['temp_location_increment'+id].value
        var calc = document.forms['quotation'].elements['temp_location_calc'+id].value
        var start = document.forms['quotation'].elements['temp_location_start'+id].value
        var end = document.forms['quotation'].elements['temp_location_end'+id].value
        var from_name = document.forms['quotation'].elements['temp_location_from_name'+id].value
        var to_name = document.forms['quotation'].elements['temp_location_to_name'+id].value
        var truck_name = document.forms['quotation'].elements['temp_location_truck_name'+id].value
        var distance = document.forms['quotation'].elements['temp_location_distance'+id].value
        var distanceliter = document.forms['quotation'].elements['temp_location_distanceliter'+id].value

        // append data
        $('#tableLocation').append('<tr id="baris'+id+'"><input type="hidden" name="location['+no_location+'][selling_service]" value="'+selling_service+'"><input type="hidden" name="location['+no_location+'][from]" value="'+from+'"><input type="hidden" name="location['+no_location+'][to]" value="'+to+'"><input type="hidden" name="location['+no_location+'][truck]" value="'+truck+'"><input type="hidden" name="location['+no_location+'][distance]" value="'+distance+'"><input type="hidden" name="location['+no_location+'][distanceliter]" value="'+distanceliter+'"><input type="hidden" name="location['+no_location+'][currency]" value="'+currency+'"><input type="hidden" name="location['+no_location+'][amount]" value="'+amount+'"><input type="hidden" name="location['+no_location+'][calc]" value="'+calc+'"><input type="hidden" name="location['+no_location+'][increment]" value="'+increment+'"><input type="hidden" name="location['+no_location+'][start_date]" value="'+start+'"><input type="hidden" name="location['+no_location+'][end_date]" value="'+end+'"><td style="text-align: center;">'+from_name+' - '+to_name+'</td><td style="text-align: center;">'+truck_name+'</td><td style="text-align: center;">'+distance+" Km"+'</td><td style="text-align: center;">'+distanceliter+" Lt"+'</td><td style="text-align: right;">'+toRp(amount)+'</td><td style="text-align: center;"><input type="number" name="location['+no_location+'][offer_price]" style="text-align: center;" value="'+amount+'"></td><td class="fit" style="text-align: center;"><a class="btn_remove addclick" id="'+id+'">Remove</a> | <a href="http://192.168.11.31/hsp/Quotation/cost_detail_location/" onclick="window.open(\'http://192.168.11.31/hsp/Quotation/cost_detail_location/'+selling_service+'/'+from+'/'+to+'/'+truck+'\', \'_blank\', \'width=1000,height=620,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=\'+((parseInt(screen.width) - 950)/2)+\',screeny=\'+((parseInt(screen.height) - 700)/2)+\'\'); return false;">Cost Detail</a></td></tr>');

        document.getElementById("clickLocation"+id).disabled = true;

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
          document.getElementById("clickLocation"+button_id).disabled = false;
        });

        no_location++;
    }

    function addWeight(id)
    {
        var selling_service = document.forms['quotation'].elements['temp_weight_service'+id].value
        var from = document.forms['quotation'].elements['temp_weight_from'+id].value
        var to = document.forms['quotation'].elements['temp_weight_to'+id].value
        var amount = document.forms['quotation'].elements['temp_weight_amount'+id].value
        var currency = document.forms['quotation'].elements['temp_weight_currency'+id].value
        var from_weight = document.forms['quotation'].elements['temp_weight_fromweight'+id].value
        var to_weight = document.forms['quotation'].elements['temp_weight_toweight'+id].value
        var increment = document.forms['quotation'].elements['temp_weight_increment'+id].value
        var calc = document.forms['quotation'].elements['temp_weight_calc'+id].value
        var start = document.forms['quotation'].elements['temp_weight_start'+id].value
        var end = document.forms['quotation'].elements['temp_weight_end'+id].value
        var from_name = document.forms['quotation'].elements['temp_weight_from_name'+id].value
        var to_name = document.forms['quotation'].elements['temp_weight_to_name'+id].value
        var measurement = document.forms['quotation'].elements['temp_weight_measurement'+id].value

        // append data
        // $('#tableWeight').append('<tr id="baris'+id+'"><input type="hidden" name="selling_service_weight[]" value="'+selling_service+'"><input type="hidden" name="from_weight[]" value="'+from+'"><input type="hidden" name="to_weight[]" value="'+to+'"><input type="hidden" name="currency_weight[]" value="'+currency+'"><input type="hidden" name="amount_weight[]" value="'+amount+'"><input type="hidden" name="from_weight[]" value="'+from_weight+'">"hidden" name="to_weight[]" value="'+to_weight+'"><input type="hidden" name="measurement[]" value="'+measurement+'"><input type="hidden" name="calc_weight[]" value="'+calc+'"><input type="hidden" name="increment_weight[]" value="'+increment+'"><input type="hidden" name="start_weight[]" value="'+start+'"><input type="hidden" name="end_weight[]" value="'+end+'"><input type="hidden" name="from_location_weight[]" value="'+from+'"><input type="hidden" name="to_location_weight[]" value="'+to+'"><td style="text-align: center;">'+from_name+'</td><td style="text-align: center;">'+to_name+'</td><td style="text-align: center;">'+from_weight+ " - "+to_weight+" " +measurement+'</td><td style="text-align: right;">'+toRp(amount)+'</td><td style="text-align: center;"><input type="number" name="offer_price_weight[]" style="text-align: center;" value="'+amount+'"></td><td class="fit" style="text-align: center;"><a class="btn_remove addclick" id="'+id+'">Remove</a> | <a href="http://192.168.11.31/hsp/Quotation/cost_detail_weight/" onclick="window.open(\'http://192.168.11.31/hsp/Quotation/cost_detail_weight/'+from+'/'+to+'\', \'_blank\', \'width=1000,height=620,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=\'+((parseInt(screen.width) - 950)/2)+\',screeny=\'+((parseInt(screen.height) - 700)/2)+\'\'); return false;">Cost Detail</a></td></tr>');

        $('#tableWeight').append('<tr id="baris'+id+'"><input type="hidden" name="weight['+no_weight+'][selling_service]" value="'+selling_service+'"><input type="hidden" name="weight['+no_weight+'][from]" value="'+from+'"><input type="hidden" name="weight['+no_weight+'][to]" value="'+to+'"><input type="hidden" name="weight['+no_weight+'][currency]" value="'+currency+'"><input type="hidden" name="weight['+no_weight+'][amount]" value="'+amount+'"><input type="hidden" name="weight['+no_weight+'][from_weight]" value="'+from_weight+'"><input type="hidden" name="weight['+no_weight+'][to_weight]" value="'+to_weight+'"><input type="hidden" name="weight['+no_weight+'][measurement]" value="'+measurement+'"><input type="hidden" name="weight['+no_weight+'][calc]" value="'+calc+'"><input type="hidden" name="weight['+no_weight+'][increment]" value="'+increment+'"><input type="hidden" name="weight['+no_weight+'][start_date]" value="'+start+'"><input type="hidden" name="weight['+no_weight+'][end_date]" value="'+end+'"><td style="text-align: center;">'+from_name+'</td><td style="text-align: center;">'+to_name+'</td><td style="text-align: center;">'+from_weight+ " - "+to_weight+" " +measurement+'</td><td style="text-align: center;">'+calc+'</td><td style="text-align: right;">'+toRp(amount)+'</td><td style="text-align: center;"><input type="number" name="weight['+no_weight+'][offer_price]" style="text-align: center;" value="'+amount+'"></td><td style="text-align: center;"><a class="btn_remove addclick" id="'+id+'">Remove</a> | <a href="http://192.168.11.31/hsp/Quotation/cost_detail_weight/" onclick="window.open(\'http://192.168.11.31/hsp/Quotation/cost_detail_weight/'+from+'/'+to+'\', \'_blank\', \'width=1000,height=620,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=\'+((parseInt(screen.width) - 950)/2)+\',screeny=\'+((parseInt(screen.height) - 700)/2)+\'\'); return false;">Cost Detail</a></td></tr>');

        document.getElementById("clickWeight"+id).disabled = true;

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
          document.getElementById("clickWeight"+button_id).disabled = false;
        });

        no_weight++;
    }

    function addOcean(id)
    {
        var selling_service = document.forms['quotation'].elements['temp_ocean_service'+id].value
        var from = document.forms['quotation'].elements['temp_ocean_from'+id].value
        var to = document.forms['quotation'].elements['temp_ocean_to'+id].value
        var amount = document.forms['quotation'].elements['temp_ocean_amount'+id].value
        var currency = document.forms['quotation'].elements['temp_ocean_currency'+id].value
        var from_qty = document.forms['quotation'].elements['temp_ocean_fromqty'+id].value
        var to_qty = document.forms['quotation'].elements['temp_ocean_toqty'+id].value
        var increment = document.forms['quotation'].elements['temp_ocean_increment'+id].value
        var calc = document.forms['quotation'].elements['temp_ocean_calc'+id].value
        var start = document.forms['quotation'].elements['temp_ocean_start'+id].value
        var end = document.forms['quotation'].elements['temp_ocean_end'+id].value
        var from_name = document.forms['quotation'].elements['temp_ocean_from_name'+id].value
        var to_name = document.forms['quotation'].elements['temp_ocean_to_name'+id].value
        var charge = document.forms['quotation'].elements['temp_ocean_charge'+id].value
        var size = document.forms['quotation'].elements['temp_ocean_size'+id].value
        var type = document.forms['quotation'].elements['temp_ocean_type'+id].value
        var category = document.forms['quotation'].elements['temp_ocean_category'+id].value

        // append data
        $('#tableOcean').append('<tr id="baris'+id+'"><input type="hidden" name="ocean['+no_ocean+'][selling_service]" value="'+selling_service+'"><input type="hidden" name="ocean['+no_ocean+'][from]" value="'+from+'"><input type="hidden" name="ocean['+no_ocean+'][to]" value="'+to+'"><input type="hidden" name="ocean['+no_ocean+'][currency]" value="'+currency+'"><input type="hidden" name="ocean['+no_ocean+'][amount]" value="'+amount+'"><input type="hidden" name="ocean['+no_ocean+'][from_qty]" value="'+from_qty+'"><input type="hidden" name="ocean['+no_ocean+'][to_qty]" value="'+to_qty+'"><input type="hidden" name="ocean['+no_ocean+'][charge]" value="'+charge+'"><input type="hidden" name="ocean['+no_ocean+'][calc]" value="'+calc+'"><input type="hidden" name="ocean['+no_ocean+'][increment]" value="'+increment+'"><input type="hidden" name="ocean['+no_ocean+'][start_date]" value="'+start+'"><input type="hidden" name="ocean['+no_ocean+'][end_date]" value="'+end+'"><input type="hidden" name="ocean['+no_ocean+'][size]" value="'+size+'"><input type="hidden" name="ocean['+no_ocean+'][type]" value="'+type+'"><input type="hidden" name="ocean['+no_ocean+'][category]" value="'+category+'"><td style="text-align: center;">'+from_name+" - "+to_name+'</td><td style="text-align: center;">'+charge+'</td><td style="text-align: center;">'+size+" - "+type+" - "+category+'</td><td style="text-align: center;">'+from_qty+ " - "+to_qty+" "+'</td><td style="text-align: right;">'+toRp(amount)+'</td><td style="text-align: center;"><input type="number" style="text-align: center;" name="ocean['+no_ocean+'][offer_price]" style="text-align: center;" value="'+amount+'"></td><td class="fit" style="text-align: center;"><a class="btn_remove addclick" id="'+id+'">Remove</a> | <a href="http://192.168.11.31/hsp/Quotation/cost_detail_ocean/" onclick="window.open(\'http://192.168.11.31/hsp/Quotation/cost_detail_ocean/'+selling_service+'/'+type+'/'+category+'/'+from+'/'+to+'/'+size+'\', \'_blank\', \'width=1000,height=620,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=\'+((parseInt(screen.width) - 950)/2)+\',screeny=\'+((parseInt(screen.height) - 700)/2)+\'\'); return false;">Cost Detail</a></td></tr>');

        document.getElementById("clickOcean"+id).disabled = true;

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
          document.getElementById("clickOcean"+button_id).disabled = false;
        });

        no_ocean++;
    }

</script>

<?php
    $this->load->view('layouts/footer.php');
?>
