<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

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
			<div class="panel panel-default">
				<div class="panel-heading">Create Agreement</div>
				<div class="panel-body">
					<div class="row">
						<?php echo form_open(); ?>
							<div class="row">
								<div class="col-md-6 col-md-offset-3">
									<?php if(validation_errors()) { ?>
					                    <div class="alert alert-danger">
					                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					                    <?php echo validation_errors(); ?>
					                    </div>
					                <?php } ?>

					                <?php if(isset($quot_exists) && $quot_exists == "exists") { ?>
		                                <div class="alert alert-warning">
		                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                                <?php echo "This Quotation already created agreement!" ?>
		                                </div>
		                            <?php } ?>
									
									<?php if($this->session->flashdata('success')) { ?>
					                    <div class="alert alert-success">
					                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					                    <?php echo $this->session->flashdata('success'); ?>
					                    </div>
					                <?php } ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Agreement Number<span style="color:red">*</span></label>
									<input type="text" name="agreement_document_number" class="form-control" value="<?php echo $agreement_document_number; ?>" readonly="true">
									<input type="hidden" name="agreement_number" class="form-control" value="<?php echo $agreement_number; ?>">
								</div>
								<div class="form-group">
									<label>Remarks</label>
									<textarea class="form-control" name="remarks">
										<?php
											echo set_value('remarks');
										?>
									</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Start Date<span style="color:red">*</span></label>
									<input type="text" name="start_date" class="form-control" id="start_date" value="<?php echo date('Y-m-d'); ?>">
								</div>
								<div class="form-group">
									<label>End Date<span style="color:red">*</span></label>
									<input type="text" name="end_date" class="form-control" id="end_date" value="<?php echo set_value('end_date'); ?>">
								</div>
								<button class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>
					<br>
					<!-- detail quotation -->
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">Detail Agreement</div>
									<div class="panel-body">
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
									                    <td><strong>Document Number</strong></td>
									                    <td style="padding:0 10px;">:</td>
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
							</div>
						</div>
					</div>
					<!-- end of detail quotation -->
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
