<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$this->load->helper('currency_helper');

$attr_form = array(
                'name' => 'floor_trucking'
             );
?>

<!-- content -->
<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h4>Floor and Marketplace Price Customs Clearance</h4>
            <hr>
        </div>
    </div>
    <div class="row">
        <?php echo form_open(); ?>
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Selling</div>
                    <div class="panel-body">
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('failed_floor_customs')) { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('failed_floor_customs'); ?>
                            </div>
                        <?php } ?>
                        
                        <?php if($this->session->flashdata('success_floor_customs')) { ?>
                            <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('success_floor_customs'); ?>
                            </div>
                        <?php } ?>

                        <table class="table table-striped table-bordered display" id="tabel-trucking" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Customs Location</th>
                                    <th>Container</th>
                                    <th>Selling Price</th>
                                    <th>Currency</th>
                                    <th>Floor Price</th>
                                    <th>Marketplace Price</th>
                                    <th>Start Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($result_customs as $key => $value) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php echo $value['FROM_NAME']; ?>

                                                    <!-- input hidden -->
                                                    <input type="hidden" name="company_id[]" value="<?php echo $value['COMPANY_ID']; ?>">
                                                    <input type="hidden" name="selling_service[]" value="<?php echo $value['SELLING_SERVICE_ID']; ?>">
                                                    <input type="hidden" name="company[]" value="<?php echo $value['COMPANY_ID']; ?>">
                                                    <input type="hidden" name="customs_location[]" value="<?php echo $value['CUSTOM_LOCATION_ID']; ?>">
                                                    <input type="hidden" name="customs_line[]" value="<?php echo $value['CUSTOM_LINE_ID']; ?>">
                                                    <input type="hidden" name="customs_kind[]" value="<?php echo $value['CUSTOM_KIND_ID']; ?>">
                                                    <input type="hidden" name="container_size[]" value="<?php echo $value['CONTAINER_SIZE_ID']; ?>">
                                                    <input type="hidden" name="container_type[]" value="<?php echo $value['CONTAINER_TYPE_ID']; ?>">
                                                    <input type="hidden" name="container_category[]" value="<?php echo $value['CONTAINER_CATEGORY_ID']; ?>">
                                                </td>
                                                <td><?php echo $value['CONTAINER_SIZE_ID'] . " - " . $value['CONTAINER_TYPE_ID'] . " - " . $value['CONTAINER_CATEGORY_ID'] . " - " . $value['CUSTOM_LINE_ID'] . " - " . $value['CUSTOM_KIND_ID']; ?></td>
                                                <td style="text-align: right"><?php echo currency($value['TARIFF_AMOUNT']); ?></td>
                                                <td>
                                                    <select name="currency[]" class="form-control">
                                                        <option <?php if ($value['TARIFF_CURRENCY'] == "" ) echo 'selected' ; ?> ></option>
                                                        <option <?php if ($value['TARIFF_CURRENCY'] == "IDR" ) echo 'selected' ; ?> value="IDR">IDR</option>
                                                        <option <?php if ($value['TARIFF_CURRENCY'] == "USD" ) echo 'selected' ; ?> value="USD">USD</option>
                                                    </select>
                                                </td>
                                                <td><input style="text-align: right" class="form-control" type="number" name="floor_price[]" value="<?php echo $value['FLOOR_PRICE']; ?>"></td>
                                                <td><input style="text-align: right" class="form-control" type="number" name="market_price[]" value="<?php echo $value['MARKET_PRICE']; ?>"></td>
                                                <td><input style="text-align: center" type="text" name="start_date[]" class="form-control" id="start_date" placeholder="Format (yyyy-mm-dd)" value="<?php echo $value['START_DATE']; ?>"> <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Date format (year-month-day)"></span></td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <hr>
                        <button class="btn btn-primary">Save</button>
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

<!-- script datatables -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#tabel-trucking').DataTable({
                responsive: true,
                "iDisplayLength": 5
        });

         $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
