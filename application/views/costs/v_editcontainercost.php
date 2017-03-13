<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Edit Container Trucking Cost Rate</h3>
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
                                    <td class="text-capitalize"><?php echo $value->FROM_LOCATION; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Destination</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->TO_LOCATION; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Qty</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->FROM_QTY . " - " . $value->TO_QTY; ?></td>
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
                                    <td class="text-capitalize"><?php echo $value->TYPE_COST; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Container Size</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->CONTAINER_SIZE_ID . " FEET"; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cost</strong></td>
                                    <td style="padding: 0 20px;">:</td>
                                    <td class="text-capitalize"><?php echo $value->COST_NAME; ?></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Form Edit Container Cost Rate
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

                            <?php if($this->session->flashdata('failed_edit_container_cost')) { ?>
                                <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('failed_edit_container_cost'); ?>
                                </div>
                            <?php } ?>
                         
                            <?php if($this->session->flashdata('success_edit_container_cost')) { ?>
                                <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('success_edit_container_cost'); ?>
                                </div>
                            <?php } ?>

                            <?php echo form_open(); ?>
                                <?php  
                                    foreach ($details as $key => $value) {
                                        ?>
                                            <div class="form-group">
                                                <label>Cost Amount</label>
                                                <input type="text" name="cost_amount" value="<?php echo $value->COST_AMOUNT; ?>" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group date" data-provide="datepicker">
                                                        <label>Start Date</label>
                                                        <input type="text" class="form-control" name="start_date" id="start_date" value="<?php echo $value->START_DATE; ?>" readonly="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group date" data-provide="datepicker">
                                                        <label>End Date</label>
                                                        <input type="text" class="form-control" name="end_date" id="end_date" value="<?php echo $value->END_DATE; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline btn-primary">Save</button>
                                            </div>
                                        <?php
                                    }
                                ?>
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
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
