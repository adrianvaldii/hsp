<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Entry Customs Cost Rate</h3>
            <hr>
            <div class="detail-name">
                <?php  
                    if ($check_detail <= 0) {
                        ?>
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Data customs does not exist! Please add it first.
                            </div>
                        <?php
                    } else {
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <table>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Custom Location</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->CUSTOM_LOCATION; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Custom Line</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->CUSTOM_LINE; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Container Type</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->CONTAINER_TYPE; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Container Size</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->CONTAINER_SIZE_ID . " FEET"; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Container Cost Rate
                </div>
                <div class="panel-body">
                    <?php 
                        if ($check_detail > 0) {
                            ?>
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

                                    <?php if($this->session->flashdata('failed_customs_cost')) { ?>
                                        <div class="alert alert-warning">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('failed_customs_cost'); ?>
                                        </div>
                                    <?php } ?>
                                 
                                    <?php if($this->session->flashdata('success_customs_cost')) { ?>
                                        <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('success_customs_cost'); ?>
                                        </div>
                                    <?php } ?>

                                    <?php echo form_open(); ?>
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
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>Start Date</label>
                                                    <input type="text" class="form-control" name="start_date" id="start_date" value="<?php echo set_value('start_date'); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>End Date</label>
                                                    <input type="text" class="form-control" name="end_date" id="end_date" value="<?php echo set_value('end_date'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>Calculation Type</label>
                                                    <select name="calc_type" class="form-control">
                                                    <option <?php echo set_select('calc_type', '', TRUE); ?> ></option>
                                                    <?php  
                                                        foreach ($calc_type as $key => $value) {?>
                                                            <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('calc_type', $value->GENERAL_ID, FALSE); ?> ><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                        <?php }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>Increment Qty</label>
                                                    <input type="number" class="form-control" name="increment_qty" value="<?php echo set_value('increment_qty'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dynamic_field">
                                            <label>Cost Rate</label>
                                            <button type="button" name="add[]" id="add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
                                            <br>
                                            <br>
                                            <div class="main-selling">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="number" name="from_qty[]" class="form-control" placeholder="From Qty" value="<?php echo set_value('from_qty[]'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="number" name="to_qty[]" value="<?php echo set_value('to_qty[]'); ?>" class="form-control" placeholder="To Qty">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select name="cost_currency[]" class="form-control">
                                                                <option <?php echo set_select('cost_currency[]', '', TRUE); ?> ></option>
                                                                <option value="IDR" <?php echo set_select('cost_currency[]', 'IDR', FALSE); ?> >IDR</option>
                                                                <option value="USD" <?php echo set_select('cost_currency[]', 'USD', FALSE); ?> >USD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="number" name="cost_amount[]" class="form-control" placeholder="Selling Amount" value="<?php echo set_value('cost_amount[]'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
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
                                 
                                    <?php if($this->session->flashdata('failed_customs_cost')) { ?>
                                        <div class="alert alert-warning">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('failed_customs_cost'); ?>
                                        </div>
                                    <?php } ?>
                                 
                                    <?php if($this->session->flashdata('success_customs_cost')) { ?>
                                        <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('success_customs_cost'); ?>
                                        </div>
                                    <?php } ?>

                                    <?php echo form_open(); ?>
                                        <div class="form-group">
                                            <label>Cost</label>
                                            <select name="cost_id" class="form-control" disabled="true">
                                                <option <?php echo set_select('cost_id', '', TRUE); ?> ></option>
                                                <?php  
                                                    foreach ($cost as $key => $value) {?>
                                                        <option value="<?php echo $value->COST_ID; ?>" <?php echo set_select('cost_id', $value->COST_ID, FALSE); ?> ><?php echo $value->COST_NAME; ?></option>
                                                    <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Cost Type</label>
                                            <select name="cost_type_id" class="form-control" disabled="true">
                                                <option <?php echo set_select('cost_type_id', '', TRUE); ?> ></option>
                                                <?php  
                                                    foreach ($cost_type as $key => $value) {?>
                                                        <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('cost_type_id', $value->GENERAL_ID, FALSE); ?> ><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Cost Group</label>
                                            <select name="cost_group_id" class="form-control" disabled="true">
                                                <option <?php echo set_select('cost_group_id', '', TRUE); ?> ></option>
                                                <?php  
                                                    foreach ($cost_group as $key => $value) {?>
                                                        <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('cost_group_id', $value->GENERAL_ID, FALSE); ?> ><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>Start Date</label>
                                                    <input type="text" class="form-control" name="start_date" id="start_date" value="<?php echo set_value('start_date'); ?>" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>End Date</label>
                                                    <input type="text" class="form-control" name="end_date" id="end_date" value="<?php echo set_value('end_date'); ?>" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>Calculation Type</label>
                                                    <select name="calc_type" class="form-control" disabled="true">
                                                    <option <?php echo set_select('calc_type', '', TRUE); ?> ></option>
                                                    <?php  
                                                        foreach ($calc_type as $key => $value) {?>
                                                            <option value="<?php echo $value->GENERAL_ID; ?>" <?php echo set_select('calc_type', $value->GENERAL_ID, FALSE); ?> ><?php echo $value->GENERAL_DESCRIPTION; ?></option>
                                                        <?php }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group date" data-provide="datepicker">
                                                    <label>Increment Qty</label>
                                                    <input type="number" class="form-control" name="increment_qty" value="<?php echo set_value('increment_qty'); ?>" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline btn-primary" disabled="true">Save</button>
                                        </div>
                                        <div id="dynamic_field">
                                            <label>Cost Rate</label>
                                            <button type="button" name="add[]" id="add" class="btn btn-success" disabled="true"><span class="glyphicon glyphicon-plus"></span></button>
                                            <br>
                                            <br>
                                            <div class="main-selling">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="number" name="from_qty[]" class="form-control" placeholder="From Qty" value="<?php echo set_value('from_qty[]'); ?>" disabled="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="number" name="to_qty[]" value="<?php echo set_value('to_qty[]'); ?>" class="form-control" placeholder="To Qty" disabled="true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select name="cost_currency[]" class="form-control" disabled="true">
                                                                <option <?php echo set_select('cost_currency[]', '', TRUE); ?> ></option>
                                                                <option value="IDR" <?php echo set_select('cost_currency[]', 'IDR', FALSE); ?> >IDR</option>
                                                                <option value="USD" <?php echo set_select('cost_currency[]', 'USD', FALSE); ?> >USD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="number" name="cost_amount[]" class="form-control" placeholder="Selling Amount" value="<?php echo set_value('cost_amount[]'); ?>" disabled="true">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
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
    <?php echo $jsArray; ?>  
    function changeValue(id){  
        document.getElementById('cost_type_id').value = costName[id].cost_type;  
        document.getElementById('cost_group_id').value = costName[id].cost_group;  
    };
</script>

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
          $('#dynamic_field').append('<div id="baris'+i+'"><div class="row"><button style="margin-left:15px;" type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><span class="glyphicon glyphicon-remove"></span></button><br><br><div class="col-md-6"><div class="form-group"><input type="number" name="from_qty[]" class="form-control" placeholder="From Qty" value="<?php echo set_value('from_qty[]'); ?>"></div></div><div class="col-md-6"><div class="form-group"><input type="number" name="to_qty[]" class="form-control" placeholder="To Qty" value="<?php echo set_value('to_qty[]'); ?>"></div></div></div><div class="row"><div class="col-md-4"><div class="form-group"><select name="cost_currency[]" class="form-control"><option <?php echo set_select('cost_currency[]', '', TRUE); ?> ></option><option value="IDR" <?php echo set_select('cost_currency[]', 'IDR', FALSE); ?> >IDR</option><option value="USD" <?php echo set_select('cost_currency[]', 'USD', FALSE); ?> >USD</option></select></div></div><div class="col-md-8"><div class="form-group"><input type="number" name="cost_amount[]" class="form-control" placeholder="Selling Amount" value="<?php echo set_value('cost_amount[]'); ?>"></div></div></div></div>');
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
