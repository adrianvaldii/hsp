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
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Detail Quotation</h3>
            <hr>
        </div>
    </div>
    <div class="row" style="margin-left:20px; margin-top:20px;">
        <div class="col-md-6">
            <table>
                <?php
                    /*  
                    foreach ($details as $value) {
                        ?>
                            <tr>
                                <td><strong>Quotation Number</strong></td>
                                <td style="padding: 0 20px;">:</td>
                                <td class="text-capitalize"><?php echo $value->QUOTATION_NUMBER; ?></td>
                            </tr>
                        <?php
                    }
                    */
                ?>
                <?php
                    /*  
                    foreach ($details as $value) {
                        ?>
                            <tr>
                                <td><strong>Document Number</strong></td>
                                <td style="padding: 20px 20px;">:</td>
                                <td class="text-capitalize"><?php echo $value->QUOTATION_DOCUMENT_NUMBER; ?></td>
                            </tr>
                        <?php
                    }
                    */
                ?>
                <tr>
                    <td><strong>Quotation Number</strong></td>
                    <td style="padding: 0 10px;">:</td>
                    <td class="text-capitalize"> <?php echo $quotation_document_number; ?> </td>
                </tr>
                <tr>
                    <td><strong>Customer</strong></td>
                    <td style="padding: 10px 10px;">:</td>
                    <td class="text-capitalize"> <?php echo $company_name; ?> </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table>
                <?php
                    /*  
                    foreach ($details as $value) {
                        ?>
                            <tr>
                                <td><strong>Quotation Number</strong></td>
                                <td style="padding: 0 20px;">:</td>
                                <td class="text-capitalize"><?php echo $value->QUOTATION_NUMBER; ?></td>
                            </tr>
                        <?php
                    }
                    */
                ?>
                <?php
                    /*  
                    foreach ($details as $value) {
                        ?>
                            <tr>
                                <td><strong>Document Number</strong></td>
                                <td style="padding: 20px 20px;">:</td>
                                <td class="text-capitalize"><?php echo $value->QUOTATION_DOCUMENT_NUMBER; ?></td>
                            </tr>
                        <?php
                    }
                    */
                ?>
                <tr>
                    <td><strong>Periode</strong></td>
                    <td style="padding: 0 10px;">:</td>
                    <td class="text-capitalize"> <?php echo $start_date . " - " . $end_date; ?> </td>
                </tr>
                <tr>
                    <td><strong>Revision Number</strong></td>
                    <td style="padding: 10px 10px;">:</td>
                    <td class="text-capitalize"> <?php echo $revision; ?> </td>
                </tr>
                <tr>
                    <td>
                        <?php echo form_open('Quotation/print_quotation/'.$quotation_number); ?>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox1" value="qty" name="check[]"> Quantity
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox1" value="remarks" name="check[]"> Remarks
                            </label>
                            <br>
                            <!-- <input type="submit" name="indo" value="Print Indonesia" class="btn btn-success"> -->
                            <?php 
                                // if ($approval_status == 'A') {
                                //     echo '<button type="submit" name="bahasa" value="indo" class="btn btn-success">Print Indonesia</button>';
                                //     echo "    ";
                                //     echo '<button type="submit" name="bahasa" value="inggris" class="btn btn-success">Print English</button>';
                                // } else {
                                //     echo '<button class="btn btn-success" disabled>Print Indonesia</button>';
                                //     echo "    ";
                                //     echo '<button class="btn btn-success" disabled>Print English</button>';
                                // }
                            echo '<button type="submit" name="bahasa" value="indo" class="btn btn-success">Print Indonesia</button>';
                                    echo "    ";
                                    echo '<button type="submit" name="bahasa" value="inggris" class="btn btn-success">Print English</button>';
                            ?>
                        </form>
                        <!-- <br> -->
                        <?php
                            /* 
                            if ($approval_status == 'A') {
                                echo anchor('Quotation/print_quotation_indonesia/'.$quotation_number, 'Print Indonesia', array('title' => 'Cetak Indonesia', 'class' => 'btn btn-success'));
                                echo "    ";
                                echo anchor('Quotation/print_quotation_inggris/'.$quotation_number, 'Print English', array('title' => 'Print English', 'class' => 'btn btn-success'));
                            } else {
                                echo '<button class="btn btn-success" disabled>Print Indonesia</button>';
                                echo "    ";
                                echo '<button class="btn btn-success" disabled>Print English</button>';
                            } */
                        ?>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <!-- trucking service -->
    <?php  
        if ($count_trucking > 0) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Trailler Trucking Service</h4>
                        <table class="table table-striped table-bordered" id="tabel-trucking" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>From / To</th>
                                    <th>Container</th>
                                    <th>Qty</th>
                                    <th>Selling Price</th>
                                    <th>Offering Price</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no_trucking = 1;
                                foreach ($data_trucking as $key => $value) {
                                    ?>
                                                <tr>
                                                    <td> <?php echo $no_trucking; ?> </td>
                                                    <td> <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?> </td>
                                                    <td> <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?> </td>
                                                    <td> <?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?> </td>
                                                    <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_STANDART_RATE); ?> </td>
                                                    <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_OFFERING_RATE); ?> </td>
                                                    <td> <?php echo $value->START_DATE; ?> </td>
                                                    <td> <?php echo $value->END_DATE; ?> </td>
                                                    <td style="text-align: center;"><?php echo anchor_popup('Quotation/detail_trucking_cost/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$quotation_number,'Cost Detail', $attributes); ?> </td>
                                                </tr>
                                    <?php
                                    $no_trucking++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
        }
    ?>
    <!-- customs service -->
    <?php  
        if ($count_customs > 0) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Container Customs Clearance Service</h4>
                        <table class="table table-striped table-bordered" id="tabel-customs" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Customs Location</th>
                                    <th>Container</th>
                                    <th>Customs Type</th>
                                    <th>Qty</th>
                                    <th>Selling Price</th>
                                    <th>Offering Price</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no_customs = 1;
                                    foreach ($data_customs as $key => $value) {
                                        ?>
                                            <tr>
                                                <td> <?php echo $no_customs; ?> </td>
                                                <td style="text-align:center"> <?php echo $value->CUSTOM_LOCATION_ID; ?> </td>
                                                <td> <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?> </td>
                                                <td> <?php echo $value->CUSTOM_LINE_ID . " - " . $value->CUSTOM_KIND_ID; ?> </td>
                                                <td> <?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?> </td>
                                                <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_STANDART_RATE); ?> </td>
                                                <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_OFFERING_RATE); ?> </td>
                                                <td> <?php echo $value->START_DATE; ?> </td>
                                                <td> <?php echo $value->END_DATE; ?> </td>
                                                <td style="text-align: center;"><?php echo anchor_popup('Quotation/detail_customs_cost/'.$value->CUSTOM_LOCATION_ID.'/'.$value->CUSTOM_LINE_ID.'/'.$value->CUSTOM_KIND_ID.'/'.$value->CONTAINER_TYPE_ID.'/'.$value->CONTAINER_SIZE_ID.'/'.$value->CONTAINER_CATEGORY_ID.'/'.$quotation_number,'Cost Detail', $attributes); ?> </td>
                                            </tr>
                                        <?php
                                        $no_customs++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
        }
    ?>
    <!-- location trucking -->
    <?php 
        if ($count_location > 0) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Non Trailler Trucking Service</h4>
                        <table class="table table-striped table-bordered" id="tabel-location" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>From / To</th>
                                    <th>Truck</th>
                                    <th>Selling Price</th>
                                    <th>Offering Price</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no_location = 1;
                                    foreach ($data_location as $key => $value) {
                                        ?>
                                            <tr>
                                                <td> <?php echo $no_location; ?> </td>
                                                <td> <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?> </td>
                                                <td> <?php echo $value->TRUCK_NAME; ?> </td>
                                                <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_STANDART_RATE); ?> </td>
                                                <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_OFFERING_RATE); ?> </td>
                                                <td> <?php echo $value->START_DATE; ?> </td>
                                                <td> <?php echo $value->END_DATE; ?> </td>
                                                <td style="text-align: center;"><?php echo anchor_popup('Quotation/detail_location_cost/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->TRUCK_KIND_ID.'/'.$quotation_number,'Cost Detail', $attributes); ?> </td>
                                            </tr>
                                        <?php
                                        $no_location++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
        }
    ?>
    <!-- weight service -->
    <?php
        if ($count_weight > 0) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Weight Measurement Service</h4>
                        <table class="table table-striped table-bordered" id="tabel-weight" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>From / To</th>
                                    <th>Weight</th>
                                    <th>Measurement Unit</th>
                                    <th>Selling Price</th>
                                    <th>Offering Price</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no_weight = 1;
                                    foreach ($data_weight as $key => $value) {
                                        ?>
                                            <tr>
                                                <td> <?php echo $no_weight; ?> </td>
                                                <td> <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?> </td>
                                                <td> <?php echo $value->FROM_WEIGHT . " - " . $value->TO_WEIGHT; ?> </td>
                                                <td> <?php echo $value->MEASUREMENT_UNIT; ?> </td>
                                                <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_STANDART_RATE); ?> </td>
                                                <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_OFFERING_RATE); ?> </td>
                                                <td> <?php echo $value->START_DATE; ?> </td>
                                                <td> <?php echo $value->END_DATE; ?> </td>
                                                <td style="text-align: center;"><?php echo anchor_popup('Quotation/detail_weight_cost/'.$value->FROM_LOCATION_ID.'/'.$value->TO_LOCATION_ID.'/'.$value->FROM_WEIGHT.'/'.$value->TO_WEIGHT.'/'.$quotation_number,'Cost Detail', $attributes); ?> </td>
                                            </tr>
                                        <?php
                                        $no_weight++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
        }
    ?>
    <!-- ocean freight -->
    <?php
        if ($count_ocean_freight) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Freight Service</h4>
                        <table class="table table-striped table-bordered" id="tabel-ocean" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>From / To</th>
                                    <th>Container</th>
                                    <th>Charge ID</th>
                                    <th>Qty</th>
                                    <th>Selling Price</th>
                                    <th>Offering Price</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no_trucking = 1;
                                foreach ($data_ocean_freight as $key => $value) {
                                    ?>
                                                <tr>
                                                    <td> <?php echo $no_trucking; ?> </td>
                                                    <td> <?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?> </td>
                                                    <td> <?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?> </td>
                                                    <td> <?php echo $value->CHARGE_ID; ?> </td>
                                                    <td> <?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?> </td>
                                                    <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_STANDART_RATE); ?> </td>
                                                    <td style="text-align:right"> <?php echo $value->SELLING_CURRENCY . " " . currency($value->SELLING_OFFERING_RATE); ?> </td>
                                                    <td> <?php echo $value->START_DATE; ?> </td>
                                                    <td> <?php echo $value->END_DATE; ?> </td>
                                                </tr>
                                    <?php
                                    $no_trucking++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
        }
    ?>
</div>
<!-- end of content -->

<!-- js -->
<?php
    $this->load->view('layouts/js.php');  
?>

<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#tabel-trucking').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tabel-customs').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tabel-location').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tabel-weight').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });

        $('#tabel-ocean').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "iDisplayLength": 5
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
