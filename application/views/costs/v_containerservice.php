<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

// load currency
$this->load->helper('currency_helper');

$n_money = 5000000;

// echo "<pre>";
// print_r($hasil);
// echo "</pre>";

// die();

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 950)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);

?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Selling Rate Services</h3>
            <hr>
            <table>
                    <?php
                        /*
                            <tr>
                                <td><strong>Company Name</strong></td>
                                <td style="padding: 0 20px;">:</td>
                                <td>
                                    <!-- form select -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form role="form">
                                                <div class="form-group">
                                                    <select class="form-control" id="company_name">
                                                        <option id="default">::::::::::: SELECT COMPANY :::::::::::</option>
                                                        <?php  
                                                            foreach ($companies as $key => $value) { ?>
                                                                <option id="<?php echo $value->COMPANY_SERVICE_ID; ?>" value="<?php echo $value->COMPANY_SERVICE_ID; ?>"><?php echo $value->COMPANY_NAME; ?></option>
                                                            <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        */
                    ?>
                    <tr>
                        <td><strong>Service Name</strong></td>
                        <td style="padding: 10px 20px;">:</td>
                        <td id="service">
                            <!-- form select -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form">
                                        <div class="form-group">
                                            <select class="form-control" id="service_name">
                                                <option id="default">::::::::::: SELECT SERVICE :::::::::::</option>
                                                <?php  
                                                    foreach ($services as $key => $value) { ?>
                                                        <option id="<?php echo $value->SELLING_SERVICE_ID; ?>" value="<?php echo $value->SELLING_SERVICE_ID; ?>"><?php echo $value->SERVICE_NAME; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <br>
            <!-- table container trucking service jakarta -->
            <div class="container-service-jakarta">
                <?php echo form_open('Cost/print_container_jakarta'); ?>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="qty" name="check[]"> Quantity
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="date" name="check[]"> Date
                    </label>
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
                </form>
                <br>
                <table class="table table-striped table-bordered" id="table-container-service-jakarta" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">FROM / TO</th>
                            <th rowspan="2" class="text-center">DESTINATION</th>
                            <th colspan="4" class="text-center">SELLING</th>
                            <th rowspan="2" class="text-center">TYPE</th>
                            <th rowspan="2" class="text-center">START DATE</th>
                            <th rowspan="2" class="text-center">END DATE</th>
                            <th rowspan="2" class="text-center nosort">Action</th>
                        </tr>
                        <tr>
                            <td class="text-center">20'</td>
                            <td class="text-center">40'</td>
                            <td class="text-center">4H'</td>
                            <td class="text-center">45'</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($hasil_jakarta as $key => $data){ 
                        ?>
                        <tr>
                            <td><?php echo $data['FROM_NAME']; ?></td>
                            <td><?php echo $data['TO_NAME']; ?></td>
                            <td style="text-align: left">
                                <?php echo $data['TARIFF_CURRENCY']; ?> <?php echo $data['TARIF_20']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'20'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'20'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: left"><?php echo $data['TARIFF_CURRENCY']; ?> <?php echo $data['TARIF_40']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'40'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'40'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: left"><?php echo $data['TARIFF_CURRENCY']; ?> <?php echo $data['TARIF_4H']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'4H'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'4H'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: left"><?php echo $data['TARIFF_CURRENCY']; ?> <?php echo $data['TARIF_45']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'45'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/'.$data['FROM_LOCATION_ID'].'/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.'45'.'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: center;"><?php echo $data['CONTAINER_TYPE_ID'] . " / " . $data['CONTAINER_CATEGORY_ID']; ?></td>
                            <td style="text-align: right;"><?php echo $data['START_DATE']; ?></td>
                            <td style="text-align: right;"><?php echo $data['END_DATE']; ?></td>
                            <td style="text-align: center;"><?php echo anchor('Cost/container_detail/'.$data['TO_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['FROM_LOCATION_ID'].'/'.$data['COMPANY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'],'Cost Detail', array('class' => 'text-center')); ?> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- table custom service jakarta -->
            <div class="custom-service-jakarta">
                <?php echo form_open('Cost/print_container_custom_jakarta'); ?>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="qty" name="check[]"> Quantity
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="date" name="check[]"> Date
                    </label>
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
                </form>
                <br>
                <table class="table table-striped table-bordered" id="table-custom-service-jakarta" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">LOCATION</th>
                            <th rowspan="2" class="text-center">CUSTOM KIND</th>
                            <th rowspan="2" class="text-center">CUSTOM LINE</th>
                            <th colspan="4" class="text-center">SELLING</th>
                            <th rowspan="2" class="text-center">TYPE</th>
                            <th rowspan="2" class="text-center">START DATE</th>
                            <th rowspan="2" class="text-center">END DATE</th>
                            <th rowspan="2" class="text-center nosort">Action</th>
                        </tr>
                        <tr>
                            <td class="text-center">20'</td>
                            <td class="text-center">40'</td>
                            <td class="text-center">4H'</td>
                            <td class="text-center">45'</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($hasil_custom_jakarta as $key => $data){ 
                        ?>
                        <tr>
                            <td><?php echo $data['CUSTOM_LOCATION']; ?></td>
                            <td><?php echo $data['CUSTOM_KIND']; ?></td>
                            <td style="text-align: center;"><?php echo $data['CUSTOM_LINE']; ?></td>
                            <td style="text-align: right;"><?php echo $data['TARIFF_CURRENCY']; ?> 
                                <?php echo $data['TARIF_20']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_custom_cost/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'20'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container_custom/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'20'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'].'/'.$data['INCREMENT_QTY'].'/'.$data['CALC_TYPE'].'/'.$data['TARIFF_CURRENCY'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: right;"><?php echo $data['TARIFF_CURRENCY']; ?> 
                                <?php echo $data['TARIF_40']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_custom_cost/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'40'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container_custom/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'40'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'].'/'.$data['INCREMENT_QTY'].'/'.$data['CALC_TYPE'].'/'.$data['TARIFF_CURRENCY'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: right;"><?php echo $data['TARIFF_CURRENCY']; ?> 
                                <?php echo $data['TARIF_4H']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_custom_cost/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'4H'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container_custom/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'4H'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'].'/'.$data['INCREMENT_QTY'].'/'.$data['CALC_TYPE'].'/'.$data['TARIFF_CURRENCY'],'Edit', $attributes); ?>    
                            </td>
                            <td style="text-align: right;"><?php echo $data['TARIFF_CURRENCY']; ?> 
                                <?php echo $data['TARIF_45']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_custom_cost/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'45'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'],'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container_custom/'.$data['COMPANY_ID'].'/'.$data['SELLING_SERVICE_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CUSTOM_KIND_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.'45'.'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'].'/'.$data['FROM_QTY'].'/'.$data['TO_QTY'].'/'.$data['START_DATE'].'/'.$data['END_DATE'].'/'.$data['INCREMENT_QTY'].'/'.$data['CALC_TYPE'].'/'.$data['TARIFF_CURRENCY'],'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: center;"><?php echo $data['CONTAINER_TYPE_ID'] . " / " . $data['CONTAINER_CATEGORY_ID']; ?></td>
                            <td><?php echo $data['START_DATE']; ?></td>
                            <td><?php echo $data['END_DATE']; ?></td>
                            <td style="text-align: center;"> <?php echo anchor('Cost/container_custom_detail/'.$data['COMPANY_ID'].'/'.$data['CUSTOM_LOCATION_ID'].'/'.$data['CONTAINER_TYPE_ID'].'/'.$data['CUSTOM_LINE_ID'].'/'.$data['CONTAINER_CATEGORY_ID'],'Cost Detail', array('class' => 'text-center')); ?> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- table location service jakarta -->
            <div class="location-service-jakarta">
                <?php echo form_open('Cost/print_location_jakarta'); ?>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="date" name="check[]"> Date
                    </label>
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
                </form>
                <br>
                <table class="table table-striped table-bordered" id="table-location-service-jakarta" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">FROM / TO</th>
                            <th class="text-center">DESTINATION</th>
                            <th class="text-center">TRUCK</th>
                            <th class="text-center">DISTANCE</th>
                            <th class="text-center">DISTANCE IN LITRE</th>
                            <th class="text-right">SELLING</th>
                            <th class="text-center">START DATE</th>
                            <th class="text-center">END DATE</th>
                            <th class="text-center nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($hasil_location_jakarta as $key => $data){ 
                        ?>
                        <tr>
                            <td><?php echo $data->FROM_NAME; ?></td>
                            <td><?php echo $data->TO_NAME; ?></td>
                            <td><?php echo $data->TRUCK_NAME; ?></td>
                            <td style="text-align: right;"><?php echo $data->DISTANCE . " Km"; ?></td>
                            <td style="text-align: right;"><?php echo $data->DISTANCE_PER_LITRE; ?></td>
                            <td style="text-align: right;">
                                <?php echo currency($data->TARIFF_AMOUNT); ?>
                                 <br>
                                Calc Type : <?php echo $data->CALC . " /" . $data->INCREMENT_QTY; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_location_cost/'.$data->COMPANY_SERVICE_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->FROM_LOCATION_ID.'/'.$data->TO_LOCATION_ID.'/'.$data->TRUCK_ID.'/'.$data->DISTANCE.'/'.$data->DISTANCE_PER_LITRE.'/'.$data->START_DATE.'/'.$data->END_DATE.'/'.$data->INCREMENT_QTY.'/'.$data->CALC_TYPE,'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_location/'.$data->COMPANY_SERVICE_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->FROM_LOCATION_ID.'/'.$data->TO_LOCATION_ID.'/'.$data->TRUCK_ID.'/'.$data->DISTANCE.'/'.$data->DISTANCE_PER_LITRE.'/'.$data->START_DATE.'/'.$data->END_DATE.'/'.$data->INCREMENT_QTY.'/'.$data->CALC_TYPE,'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: center;"><?php echo $data->START_DATE; ?></td>
                            <td style="text-align: center;"><?php echo $data->END_DATE; ?></td>
                            <td style="text-align: center;"><?php echo anchor('Cost/location_detail/'.$data->COMPANY_SERVICE_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->FROM_LOCATION_ID.'/'.$data->TO_LOCATION_ID.'/'.$data->TRUCK_ID.'/'.$data->DISTANCE.'/'.$data->DISTANCE_PER_LITRE.'/'.$data->START_DATE.'/'.$data->END_DATE.'/'.$data->INCREMENT_QTY.'/'.$data->CALC_TYPE,'Cost Detail', array('class' => 'text-center')); ?> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- table weight service jakarta -->
            <div class="weight-service-jakarta">
                <?php echo form_open('Cost/print_weight_jakarta'); ?>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="date" name="check[]"> Date
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="weight" name="check[]"> Weight
                    </label>
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
                </form>
                <br>
                <table class="table table-striped table-bordered" id="table-weight-service-jakarta" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">FROM / TO</th>
                            <th class="text-center">DESTINATION</th>
                            <th class="text-center">FROM WEIGHT</th>
                            <th class="text-center">TO WEIGHT</th>
                            <th class="text-right">SELLING</th>
                            <th class="text-center">START DATE</th>
                            <th class="text-center">END DATE</th>
                            <th class="text-center nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($hasil_weight_jakarta as $key => $data){ 
                        ?>
                        <tr>
                            <td><?php echo $data->FROM_NAME; ?></td>
                            <td><?php echo $data->TO_NAME; ?></td>
                            <td style="text-align: right;"><?php echo $data->FROM_WEIGHT; ?></td>
                            <td style="text-align: right;"><?php echo $data->TO_WEIGHT; ?></td>
                            <td style="text-align: right;">
                                <?php echo currency($data->TARIFF_AMOUNT); ?>
                                 <br>
                                Calc Type : <?php echo $data->CALC . " /" . $data->INCREMENT_QTY; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_weight_cost/'.$data->COMPANY_SERVICE_ID."/".$data->SELLING_SERVICE_ID."/".$data->FROM_LOCATION_ID.'/'.$data->TO_LOCATION_ID.'/'.$data->FROM_WEIGHT.'/'.$data->TO_WEIGHT.'/'.$data->CALC_TYPE.'/'.$data->INCREMENT_QTY.'/'.$data->START_DATE.'/'.$data->END_DATE,'Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_weight/'.$data->COMPANY_SERVICE_ID."/".$data->SELLING_SERVICE_ID."/".$data->FROM_LOCATION_ID.'/'.$data->TO_LOCATION_ID.'/'.$data->FROM_WEIGHT.'/'.$data->TO_WEIGHT.'/'.$data->CALC_TYPE.'/'.$data->INCREMENT_QTY.'/'.$data->START_DATE.'/'.$data->END_DATE,'Edit', $attributes); ?>
                            </td>
                            <td style="text-align: center;"><?php echo $data->START_DATE; ?></td>
                            <td style="text-align: center;"><?php echo $data->END_DATE; ?></td>
                            <td style="text-align: center;"><?php echo anchor('Cost/weight_detail/'.$data->COMPANY_SERVICE_ID."/".$data->SELLING_SERVICE_ID."/".$data->FROM_LOCATION_ID.'/'.$data->TO_LOCATION_ID.'/'.$data->FROM_WEIGHT.'/'.$data->TO_WEIGHT.'/'.$data->CALC_TYPE.'/'.$data->INCREMENT_QTY.'/'.$data->START_DATE.'/'.$data->END_DATE,'Cost Detail', array('class' => 'text-center')); ?> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- table ocean freight service jakarta -->
            <div class="ocean-service-jakarta">
                <?php echo form_open(); ?>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="date" name="check[]"> Date
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="inlineCheckbox1" value="weight" name="check[]"> Qty
                    </label>
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
                </form>
                <br>
                <table class="table table-striped table-bordered" id="table-ocean-service-jakarta" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">FROM / TO</th>
                            <th rowspan="2" class="text-center">DESTINATION</th>
                            <th rowspan="2" class="text-center">CHARGE KIND</th>
                            <th colspan="4" class="text-center">SELLING</th>
                            <th rowspan="2" class="text-center">TYPE</th>
                            <th rowspan="2" class="text-center">START DATE</th>
                            <th rowspan="2" class="text-center">END DATE</th>
                            <th rowspan="2" class="text-center nosort">Action</th>
                        </tr>
                        <tr>
                            <td class="text-center">20'</td>
                            <td class="text-center">40'</td>
                            <td class="text-center">4H'</td>
                            <td class="text-center">45'</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($hasil_ocean_jakarta as $key => $data){ 
                        ?>
                        <tr>
                            <td><?php echo $data['FROM_NAME']; ?></td>
                            <td><?php echo $data['TO_NAME']; ?></td>
                            <td><?php echo $data['CHARGE_NAME']; ?></td>
                            <td style="text-align: right">
                                <?php echo $data['TARIF_20']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/','Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/','Edit', $attributes); ?>
                            </td>
                            <td style="text-align: right"><?php echo $data['TARIF_40']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/','Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/','Edit', $attributes); ?>
                            </td>
                            <td style="text-align: right"><?php echo $data['TARIF_4H']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/','Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/','Edit', $attributes); ?>
                            </td>
                            <td style="text-align: right"><?php echo $data['TARIF_45']; ?>
                                <br>
                                Qty : <?php echo $data['FROM_QTY'] . " - " . $data['TO_QTY']; ?>
                                <br>
                                Calc Type : <?php echo $data['CALC_TYPE'] . " /" . $data['INCREMENT_QTY']; ?>
                                <br>
                                <?php echo anchor_popup('Cost/add_container_cost/','Add Cost', $attributes); ?> | <?php echo anchor_popup('Cost/edit_container/','Edit', $attributes); ?>
                            </td>
                            <td style="text-align: center;"><?php echo $data['CONTAINER_TYPE_ID'] . " / " . $data['CONTAINER_CATEGORY_ID']; ?></td>
                            <td style="text-align: right;"><?php echo $data['START_DATE']; ?></td>
                            <td style="text-align: right;"><?php echo $data['END_DATE']; ?></td>
                            <td style="text-align: center;"><?php echo anchor('Cost/container_detail/','Cost Detail', array('class' => 'text-center')); ?> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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

        var win = null; 
        function NewWindow(mypage,myname,w,h,scroll){ 
            LeftPosition = (screen.width) ? (screen.width-w)/2 : 0; 
            TopPosition = (screen.height) ? (screen.height-h)/2 : 0; 
            settings = 
            'titlebar=no,copyhistory=no,toolbar=no,location=no,directories=no,status=no,menubar=no,height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
            win = window.open(mypage,myname,settings) 
        }

        $(document).ready(function() { 
            // datatables container service
            $('#table-container-service-jakarta').DataTable({
                    responsive: true,
                    'aoColumnDefs': [{
                        'bSortable': false,
                        'aTargets': ['nosort']
                    }],
                    "order": [[ 1, "asc" ]],
                    "iDisplayLength": 5
            });
        });

        var i=1;
        $('#add').click(function(){
          i++;
          // append input text
          $('#dynamic_field').append('<div id="baris'+i+'"><div class="row"><div class="col-md-5"><div class="form-group"><input type="number" name="from_qty[]" value="<?php echo set_value('from_qty[]'); ?>" class="form-control" placeholder="From Qty"></div></div><div class="col-md-5"><div class="form-group"><input type="number" name="to_qty[]" value="<?php echo set_value('to_qty[]'); ?>" class="form-control" placeholder="To Qty"></div></div><div class="col-md-2"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><span class="glyphicon glyphicon-remove"></span></button></div></div></div><div class="row"><div class="col-md-4"><div class="form-group"><select name="tariff_currency[]" class="form-control"><option></option><option value="IDR">IDR</option><option value="USD">USD</option></select></div></div><div class="col-md-8"><div class="form-group"><input type="number" name="tariff_amount[]" value="<?php echo set_value('tariff_amount[]'); ?>" class="form-control" placeholder="Selling Amount"></div></div></div></div>');
        });

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
        });

        $('#table-container-service-surabaya').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 1, "asc" ]]
        });
        
        // datatables custom service
        $('#table-custom-service-jakarta').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 1, "asc" ]]
        });

        $('#table-custom-service-surabaya').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 1, "asc" ]]
        });

        $('#table-location-service-jakarta').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 3, "asc" ], [ 5, "asc" ], [ 6, "asc" ]]
        });

        $('#table-weight-service-jakarta').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }]
        });

        $('#table-ocean-service-jakarta').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }]
        });

        // hide all table
        $(".container-service-jakarta").hide();
        $(".container-service-surabaya").hide();
        $(".custom-service-jakarta").hide();
        $(".custom-service-surabaya").hide();
        $(".location-service-jakarta").hide();
        $(".location-service-surabaya").hide();
        $(".weight-service-jakarta").hide();
        $(".weight-service-surabaya").hide();
        $(".ocean-service-jakarta").hide();
        $(".ocean-service-surabaya").hide();

        // $('#service_name').attr('disabled', true);

        // $('#company_name').change(function() {
        //     if ($("#company_name option:selected").attr('id') == "default") {
        //         $('#service_name').prop('disabled', true);
        //     } else {
        //         $('#service_name').prop('disabled', false);
        //     }
        // });

        // select service to show service name
        $("#service_name").bind('change', function() {
            if ($("#service_name option:selected").attr('id') == "default") {
                $(".container-service-jakarta").hide();
                $(".custom-service-jakarta").hide();
                $(".location-service-jakarta").hide();
                $(".weight-service-jakarta").hide();
                $(".ocean-service-jakarta").hide();
            } else if($("#service_name option:selected").attr('id') == "SS01") {
                $(".container-service-jakarta").show();
                $(".custom-service-jakarta").hide();
                $(".location-service-jakarta").hide();
                $(".weight-service-jakarta").hide();
                $(".ocean-service-jakarta").hide();
            } else if($("#service_name option:selected").attr('id') == "SS02") {
                $(".container-service-jakarta").hide();
                $(".custom-service-jakarta").show();
                $(".location-service-jakarta").hide();
                $(".weight-service-jakarta").hide();
                $(".ocean-service-jakarta").hide();
            } else if($("#service_name option:selected").attr('id') == "SS04") {
                $(".container-service-jakarta").hide();
                $(".custom-service-jakarta").hide();
                $(".location-service-jakarta").show();
                $(".weight-service-jakarta").hide();
                $(".ocean-service-jakarta").hide();
            } else if($("#service_name option:selected").attr('id') == "SS05") {
                $(".container-service-jakarta").hide();
                $(".custom-service-jakarta").hide();
                $(".location-service-jakarta").hide();
                $(".weight-service-jakarta").show();
                $(".ocean-service-jakarta").hide();
            } else if($("#service_name option:selected").attr('id') == "SS03") {
                $(".container-service-jakarta").hide();
                $(".custom-service-jakarta").hide();
                $(".location-service-jakarta").hide();
                $(".weight-service-jakarta").hide();
                $(".ocean-service-jakarta").show();
            } else {
                $(".container-service-jakarta").hide();
                $(".custom-service-jakarta").hide();
                $(".location-service-jakarta").hide();
                $(".ocean-service-jakarta").hide();
            }
        });
    });


</script>

<?php
    $this->load->view('layouts/footer.php');
?>