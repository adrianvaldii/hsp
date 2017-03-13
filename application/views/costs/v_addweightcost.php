<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Entry Service Bulk Cost Rate</h3>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <table>
                        <?php  
                            foreach ($details as $key => $value) {
                                ?>
                                <tr>
                                    <td><strong>From / To</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->FROM_NAME; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Destination</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->TO_NAME; ?></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </table>
                </div>
                <div class="col-md-4">
                    <table>
                        <?php  
                            foreach ($details as $key => $value) {
                                ?>
                                <tr>
                                    <td><strong>Weight</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->FROM_WEIGHT . " - " . $value->TO_WEIGHT . " MT"; ?></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Service Bulk Cost Rate
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

                            <?php if($this->session->flashdata('failed_entry_weight_cost')) { ?>
                                <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('failed_entry_weight_cost'); ?>
                                </div>
                            <?php } ?>
                         
                            <?php if($this->session->flashdata('success_entry_weight_cost')) { ?>
                                <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('success_entry_weight_cost'); ?>
                                </div>
                            <?php } ?>

                            <?php echo form_open(); ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Cost</label>
                                        <select name="cost_id" class="form-control">
                                            <option <?php echo set_select('cost_id', '', TRUE); ?> ></option>
                                            <?php  
                                                foreach ($cost as $key => $value) {?>
                                                    <option value="<?php echo $value->COST_ID; ?>" <?php echo set_select('cost_id', $value->COST_ID, FALSE); ?> ><?php echo $value->COST_NAME; ?></option>
                                                <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row">
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Increment Qty</label>
                                                <input type="number" class="form-control" name="increment_qty" value="<?php echo set_value('increment_qty'); ?>">
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
                                                        <input type="number" name="from_weight[]" value="<?php echo set_value('from_weight[]'); ?>" class="form-control" placeholder="From Weight">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="number" name="to_weight[]" value="<?php echo set_value('to_weight[]'); ?>" class="form-control" placeholder="To Weight">
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
                                                        <select name="cost_currency[]" class="form-control">
                                                            <option <?php echo set_select('cost_currency[]', '', TRUE); ?>></option>
                                                            <option value="IDR" <?php echo set_select('cost_currency[]', 'IDR', FALSE); ?>>IDR</option>
                                                            <option value="USD" <?php echo set_select('cost_currency[]', 'USD', FALSE); ?>>USD</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="number" name="cost_amount[]" value="<?php echo set_value('cost_amount[]'); ?>" class="form-control" placeholder="Selling Amount">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" value="Save" class="btn btn-outline btn-primary">
                                        <input type="reset" name="reset" class="btn btn-link">
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
          $('#dynamic_field').append('<div id="baris'+i+'"><div class="row"><div class="col-md-5"><div class="form-group"><input type="number" name="from_weight[]" value="<?php echo set_value('from_weight[]'); ?>" class="form-control" placeholder="From Weight"></div></div><div class="col-md-5"><div class="form-group"><input type="number" name="to_weight[]" value="<?php echo set_value('to_weight[]'); ?>" class="form-control" placeholder="To Weight"></div></div><div class="col-md-2"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><span class="glyphicon glyphicon-remove"></span></button></div></div></div><div class="row"><div class="col-md-4"><div class="form-group"><select name="tariff_currency[]" class="form-control"><option></option><option value="IDR">IDR</option><option value="USD">USD</option></select></div></div><div class="col-md-8"><div class="form-group"><input type="number" name="tariff_amount[]" value="<?php echo set_value('tariff_amount[]'); ?>" class="form-control" placeholder="Selling Amount"></div></div></div></div>');
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
