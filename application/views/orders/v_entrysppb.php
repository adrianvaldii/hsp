<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Entry SPPB/NPE <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php echo form_open(); ?>
                <?php if(validation_errors()) { ?>
                    <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo validation_errors(); ?>
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
                <!-- info work order -->
                <div class="row">
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td><strong>Work Order Number</strong></td>
                                <td style="padding: 0px 10px">:</td>
                                <td><?php echo $work_order_number; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Customer</strong></td>
                                <td style="padding: 10px 10px">:</td>
                                <td><?php echo $customer_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Work Order Date</strong></td>
                                <td style="padding: 0px 10px">:</td>
                                <td><?php echo $wo_date; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td><strong>Vessel</strong></td>
                                <td style="padding: 0px 10px">:</td>
                                <td><?php echo $vessel_name . " " . $voyage_number; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Trade</strong></td>
                                <td style="padding: 10px 10px">:</td>
                                <td><?php echo $trade_name; ?></td>
                            </tr>
                            <tr>
                                <td><strong>POL - POD</strong></td>
                                <td style="padding: 0px 10px">:</td>
                                <td><?php echo $pol_name . " - " . $pod_name; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Entry SPPB</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <input type="hidden" name="work_order_number" class="form-control" readonly="true" value="<?php echo $work_order_number; ?>">
                                </div>
                                <div class="form-group">
                                    <label>SPPB Number <span style="color:red">*</span></label>
                                    <input type="text" name="sppb_number" class="form-control" value="<?php echo set_value('sppb_number', $sppb_number); ?>" />
                                </div>
                                <div class="form-group">
                                    <label>SPPB Date <span style="color:red">*</span></label>
                                    <input type="text" name="sppb_date" id="sppb_date" class="form-control" value="<?php echo set_value('sppb_date', $sppb_date); ?>" />
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#sppb_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
