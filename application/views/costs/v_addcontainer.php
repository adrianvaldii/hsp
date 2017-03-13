<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Entry Container Selling Rate</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Container Selling Rate
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(validation_errors()) { ?>
                                <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo validation_errors(); ?>
                                </div>
                            <?php } ?>

                            <?php if(isset($qty_error) && $qty_error == "error") { ?>
                                <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo "Quantity not valid!" ?>
                                </div>
                            <?php } ?>

                            <?php if(isset($data_exist) && $data_exist == "exist") { ?>
                                <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo "Data already exists!" ?>
                                </div>
                            <?php } ?>

                            <?php if(isset($date_error) && $date_error == "error") { ?>
                                <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo "Date error!" ?>
                                </div>
                            <?php } ?>

                            <?php if($this->session->flashdata('failed_container_trucking')) { ?>
                                <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('failed_container_trucking'); ?>
                                </div>
                            <?php } ?>
                         
                            <?php if($this->session->flashdata('success_container_trucking')) { ?>
                                <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('success_container_trucking'); ?>
                                </div>
                            <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company ID</label>
                                        <input type="text" name="company_id" class="form-control" value="<?php echo $code_cmpy; ?>" readonly="true">
                                    </div>
                                    <div class="form-group">
                                        <label>Selling Service</label>
                                        <input type="text" value="<?php echo $selling_service->SERVICE_NAME; ?>" readonly="true" class="form-control">
                                        <input type="hidden" name="selling_service_id" value="<?php echo $selling_service->SELLING_SERVICE_ID; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Container Size</label>
                                        <select name="container_size_id" class="form-control">
                                            <option <?php echo set_select('container_size_id', '', TRUE); ?>></option>
                                            <?php  
                                                foreach ($container_size as $key => $value) {?>
                                                    <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_size_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Container Type</label>
                                        <select name="container_type_id" class="form-control">
                                            <option <?php echo set_select('container_type_id', '', TRUE); ?>></option>
                                            <?php  
                                                foreach ($container_type as $key => $value) {?>
                                                    <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_type_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Container Category</label>
                                        <select name="container_category_id" class="form-control">
                                            <option <?php echo set_select('container_category_id', '', TRUE); ?>></option>
                                            <?php  
                                                foreach ($container_category as $key => $value) {?>
                                                    <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('container_category_id', $value->GENERAL_ID, FALSE); ?>><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="submit" name="submit" value="Save" class="btn btn-outline btn-primary">
                                        <input type="reset" name="reset" class="btn btn-link">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Calculation Type</label>
                                        <select name="calc_type" class="form-control">
                                            <option <?php echo set_select('calc_type', '', TRUE); ?>></option>
                                            <?php  
                                                foreach ($calc_type as $key => $value) {?>
                                                    <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('calc_type', $value->GENERAL_ID, FALSE); ?> ><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Increment Qty</label>
                                        <input type="number" class="form-control" name="increment_qty" value="<?php echo set_value('increment_qty'); ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>From / To</label>
                                                <input type="text" class="form-control" name="temp_from_location" id="from_location" value="<?php echo set_value('temp_from_location'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Code</label>
                                                <input type="text" name="from_location_id" value="<?php echo set_value('from_location_id'); ?>" class="form-control" readonly="true">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Destination</label>
                                                <input type="text" class="form-control" name="temp_to_location" value="<?php echo set_value('temp_to_location'); ?>" id="to_location">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Code</label>
                                                <input type="text" name="to_location_id" value="<?php echo set_value('to_location_id'); ?>" class="form-control" readonly="true">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group date" data-provide="datepicker">
                                                <label>Start Date</label>
                                                <input type="text" class="form-control" value="<?php echo set_value('start_date'); ?>" name="start_date" id="start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group date" data-provide="datepicker">
                                                <label>End Date</label>
                                                <input type="text" class="form-control" name="end_date" value="<?php echo set_value('end_date'); ?>" id="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dynamic_field">
                                        <label>Selling Rate</label>
                                        <div class="main-selling">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="number" name="from_qty[]" value="<?php echo set_value('from_qty[]'); ?>" class="form-control" placeholder="From Qty">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="number" name="to_qty[]" value="<?php echo set_value('to_qty[]'); ?>" class="form-control" placeholder="To Qty">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="button" name="add[]" id="add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="tariff_currency[]" class="form-control">
                                                            <option <?php echo set_select('tariff_currency[]', '', TRUE); ?>></option>
                                                            <option value="IDR" <?php echo set_select('tariff_currency[]', 'IDR', FALSE); ?>>IDR</option>
                                                            <option value="USD" <?php echo set_select('tariff_currency[]', 'USD', FALSE); ?>>USD</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="number" name="tariff_amount[]" value="<?php echo set_value('tariff_amount[]'); ?>" class="form-control money" placeholder="Selling Amount">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<script>
    function set()
    {
        var money_floor = document.getElementById("temp-money-floor").value;
        document.getElementById("money-floor").value = money_floor;
    }

    $(document).ready(function() {
        
        $('#table-service').DataTable({
                responsive: true
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

        // autocomplete from location
        $("#from_location").autocomplete({
          source: "<?php echo site_url('Cost/search_from_location'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=from_location_id]').val(data.item.location_id);
          }
        });

        // autocomplete from location
        $("#to_location").autocomplete({
          source: "<?php echo site_url('Cost/search_from_location'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=to_location_id]').val(data.item.location_id);
          }
        });

        var i=1;
        $('#add').click(function(){
          i++;
          // append input text
          $('#dynamic_field').append('<div id="baris'+i+'"><div class="row"><div class="col-md-5"><div class="form-group"><input type="number" name="from_qty[]" value="<?php echo set_value('from_qty[]'); ?>" class="form-control" placeholder="From Qty"></div></div><div class="col-md-5"><div class="form-group"><input type="number" name="to_qty[]" value="<?php echo set_value('to_qty[]'); ?>" class="form-control" placeholder="To Qty"></div></div><div class="col-md-2"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><span class="glyphicon glyphicon-remove"></span></button></div></div></div><div class="row"><div class="col-md-4"><div class="form-group"><select name="tariff_currency[]" class="form-control"><option></option><option value="IDR">IDR</option><option value="USD">USD</option></select></div></div><div class="col-md-8"><div class="form-group"><input type="number" name="tariff_amount[]" value="<?php echo set_value('tariff_amount[]'); ?>" class="form-control money" placeholder="Selling Amount"></div></div></div></div>');
        });
        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
        });

    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
