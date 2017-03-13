<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$this->load->helper('currency_helper');

?>

<!-- content -->
<div class="container-fluid font_mini">
    <?php echo form_open(); ?>
        <div class="row">
            <div class="col-md-12">
                <h3>Detail Operational Cost</h3>
                <hr>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-md-offset-1">
                                <table>
                                    <tr>
                                        <td><strong>Work Order Number</strong></td>
                                        <td style="padding: 0 10px;">:</td>
                                        <td class="text-capitalize"> <?php echo $work_order_number; ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Work Order Date</strong></td>
                                        <td style="padding: 10px 10px;">:</td>
                                        <td class="text-capitalize"> <?php echo $work_order_date; ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong>PIC Name</strong></td>
                                        <td style="padding: 0px 10px;">:</td>
                                        <td class="text-capialize"> 
                                            <?php echo $pic_name; ?> 
                                            <input type="hidden" name="pic_name" value="<?php echo $pic_name; ?>">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-5 col-md-offset-1">
                                <table>
                                    <tr>
                                        <td><strong>Vessel / Voyage Number</strong></td>
                                        <td style="padding: 0 10px;">:</td>
                                        <td class="text-capitalize"> <?php echo $vessel_name . " - " . $voyage_number; ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trade</strong></td>
                                        <td style="padding: 10px 10px;">:</td>
                                        <td class="text-capitalize"> <?php echo $trade_name; ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer</strong></td>
                                        <td style="padding: 0px 10px;">:</td>
                                        <td class="text-capitalize"> <?php echo $customer_name; ?> </td>
                                    </tr>
                                </table>
                            </div>
                            <br>
                            <div class="row" style="padding-top: 70px;">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Action</div>
                                            <div class="panel-body">
                                                <div>
                                                    <strong>Approval</strong>
                                                    <select name="status" class="form-control" style="margin-top: 5px;margin-bottom:5px">
                                                        <option></option>
                                                        <option <?php if ($approval_status == "A" ) echo 'selected' ; ?> value="A">Approve</option>
                                                        <option <?php if ($approval_status == "R" ) echo 'selected' ; ?> value="R">Reject</option>
                                                    </select>
                                                    <?php
                                                        if ($approval_status == "A") {
                                                            ?>
                                                                <button disabled class="btn btn-success">Submit</button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                                <button class="btn btn-success">Submit</button>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Approved / Rejected By :</div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p><strong>Level 1</strong></p>
                                                        <table>
                                                            <tr>
                                                                <td>Name</td>
                                                                <td style="padding: 0 10px">:</td>
                                                                <td><?php echo $level1_approval_name; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date</td>
                                                                <td style="padding: 0 10px">:</td>
                                                                <td><?php echo $level1_approval_date; ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Level 2</strong></p>
                                                        <table>
                                                            <tr>
                                                                <td>Name</td>
                                                                <td style="padding: 0 10px">:</td>
                                                                <td><?php echo $level2_approval_name; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date</td>
                                                                <td style="padding: 0 10px">:</td>
                                                                <td><?php echo $level2_approval_date; ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Level 3</strong></p>
                                                        <table>
                                                            <tr>
                                                                <td>Name</td>
                                                                <td style="padding: 0 10px">:</td>
                                                                <td><?php echo $level3_approval_name; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date</td>
                                                                <td style="padding: 0 10px">:</td>
                                                                <td><?php echo $level3_approval_date; ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Operational Cost Data</div>
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">From / To</th>
                                                    <th class="text-center">Container Number</th>
                                                    <th class="text-center">Container Detail</th>
                                                    <th class="text-center">Cost Name</th>
                                                    <th class="text-center">Sequence ID</th>
                                                    <th class="text-center">Mutation Description</th>
                                                    <th class="text-center">Currency</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Actual Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($data_operational as $key => $value) {
                                                        ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?></td>
                                                                <td class="text-center"><?php echo $value->CONTAINER_NUMBER ?></td>
                                                                <td class="text-center"><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?></td>
                                                                <td class="text-center"><?php echo $value->COST_NAME ?></td>
                                                                <td class="text-center"><?php echo $value->SEQUENCE_ID ?></td>
                                                                <td><?php echo $value->MUTATION_DESCRIPTION ?></td>
                                                                <td class="text-center"><?php echo $value->COST_CURRENCY ?></td>
                                                                <td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
                                                                <td class="text-right"><?php echo currency($value->COST_ACTUAL_AMOUNT); ?></td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
