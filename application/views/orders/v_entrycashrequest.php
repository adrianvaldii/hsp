<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$date = date('Y-m-d H:i:s');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <!-- menu -->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <nav class="navbar navbar-default" id="my_nav">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="myNavbar">
                        <ul class="nav navbar-nav">
                            <li><a href="#section1">Data Cost</a></li>
                            <li><a href="#section2">Cost DO</a></li>
                            <li><a href="#section3">Additional Services</a></li>
                            <li><a href="#section4">Additional Cost</a></li>
                            <li><a href="#section5">Additional Cost Temporary</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- header title -->
    <div class="row">
        <div class="col-md-12">
            <h3>Cash Request Data <a class="btn btn-success" href="<?php echo site_url('Order/entry_additional/'.$this->uri->segment(3)); ?>">Entry Additional Cost</a> <a class="btn btn-success" href="<?php echo site_url('Order/entry_selling_additional/'.$this->uri->segment(3)); ?>">Entry Additional Services</a> <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row" id="section1">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Data Cash And Transfer Request</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table style="margin-left: 50px;">
                                        <tr>
                                            <td><strong>Work Order Number</strong></td>
                                            <td style="padding-left: 10px; padding-right: 10px;">:</td>
                                            <td><?php echo $this->uri->segment(3); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trade</strong></td>
                                            <td style="padding-left: 10px; padding-right: 10px;">:</td>
                                            <td><?php echo $trade_name; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table>
                                        <tr>
                                            <td><strong>Vessel</strong></td>
                                            <td style="padding-left: 10px; padding-right: 10px;">:</td>
                                            <td><?php echo $vessel_name . " - " . $voyage_number; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Customer Name</strong></td>
                                            <td style="padding-left: 10px; padding-right: 10px;">:</td>
                                            <td><?php echo $customer_name; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-striped table-bordered" id="tableReim">
                                <thead>
                                    <tr>
                                        <th>Container No.</th>
                                        <th>Cost Name</th>
                                        <th>Cost Type</th>
                                        <th>Cost Group</th>
                                        <th>Cost Kind</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>Actual Amount</th>
                                        <th>Status Transfer</th>
                                        <th>Status Close</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" style="text-align:center">Total:</th>
                                        <th style="text-align:right"></th>
                                        <th style="text-align:right"></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                        foreach ($data_cash_request as $key => $value) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                    <td><?php echo $value->COST_NAME; ?></td>
                                                    <td><?php echo $value->COST_TYPE; ?></td>
                                                    <td><?php echo $value->COST_GROUP; ?></td>
                                                    <td>
                                                        <?php
                                                            if ($value->COST_KIND == 'A') {
                                                                echo "ADDITIONAL COST";
                                                            } else {
                                                                echo "STANDART COST";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $value->COST_CURRENCY; ?></td>
                                                    <td class="text-right"><?php echo currency($value->COST_REQUEST_AMOUNT); ?></td>
                                                    <td class="text-right"><?php echo currency($value->COST_ACTUAL_AMOUNT); ?></td>
                                                    <td class="text-center">
                                                        <?php echo ($value->IS_TRANSFERED == 'Y')?'TRANSFERED':'WAITING' ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo ($value->IS_DONE == 'Y')?'CLOSED':'WAITING' ?>
                                                    </td>
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
            <!-- Cost DO -->
            <?php echo form_open('Order/edit_cash_do/'.$this->uri->segment(3)) ?>
                <div class="row" id="section2">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Cash Request DO</div>
                            <div class="panel-body">
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('failed_cash_do')) { ?>
                                    <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('failed_cash_do'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success_cash_do')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success_cash_do'); ?>
                                    </div>
                                <?php } ?>

                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Container Number</th>
                                            <th class="text-center">Cost Name</th>
                                            <th class="text-center">Cost Type</th>
                                            <th class="text-center">Cost Group</th>
                                            <th class="text-center">Cost Kind</th>
                                            <th class="text-center">Currency</th>
                                            <th class="text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $do = 0;
                                            foreach ($data_cash_do as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo $value->CONTAINER_NUMBER; ?>
                                                            <!-- hidden -->
                                                            <input type="hidden" name="do[<?php echo $do; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                            <input type="hidden" name="do[<?php echo $do; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                                            <input type="hidden" name="do[<?php echo $do; ?>][cost_type_id]" value="<?php echo $value->COST_TYPE_ID; ?>" />
                                                            <input type="hidden" name="do[<?php echo $do; ?>][cost_group_id]" value="<?php echo $value->COST_GROUP_ID; ?>" />
                                                            <input type="hidden" name="do[<?php echo $do; ?>][company_id]" value="<?php echo $value->COMPANY_ID; ?>" />
                                                            <input type="hidden" name="do[<?php echo $do; ?>][cost_kind]" value="<?php echo $value->COST_KIND; ?>" />
                                                        </td>
                                                        <td class="text-center"><?php echo $value->COST_NAME; ?></td>
                                                        <td class="text-center"><?php echo $value->COST_TYPE; ?></td>
                                                        <td class="text-center"><?php echo $value->COST_GROUP; ?></td>
                                                        <td class="text-center">
                                                            <?php
                                                                if ($value->COST_KIND == 'A') {
                                                                    echo "ADDITIONAL COST";
                                                                } else {
                                                                    echo "STANDART COST";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td class="text-center"><?php echo $value->COST_CURRENCY; ?></td>
                                                        <td class="text-center">
                                                            <input type="text" style="text-align:right" name="do[<?php echo $do; ?>][amount]" class="form-control duit" value="<?php echo $value->COST_REQUEST_AMOUNT; ?>">
                                                        </td>
                                                    </tr>
                                                <?php
                                                $do++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                    if (count($data_cash_do) < 1) {
                                        ?>
                                            <button type="submit" disabled class="btn btn-primary">Save</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        <?php
                                    }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- additional selling -->
            <form>
                <div class="row" id="section3">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Data Additional Selling</div>
                            <div class="panel-body">
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('failed_additional_selling')) { ?>
                                    <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('failed_additional_selling'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success_additional_selling')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success_additional_selling'); ?>
                                    </div>
                                <?php } ?>

                                <table class="table table-striped table-bordered" id="table-selling">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Additional Selling</th>
                                            <th class="text-center">Container Number</th>
                                            <th class="text-center">Container Detail</th>
                                            <th class="text-center">Selling Service Name</th>
                                            <th class="text-center">Currency</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($data_selling_additional as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $value->ADDITIONAL_SELLING; ?></td>
                                                        <td class="text-center"><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                        <td class="text-center"><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?></td>
                                                        <td class="text-center"><?php echo $value->SERVICE_NAME; ?></td>
                                                        <td class="text-center"><?php echo $value->TARIFF_CURRENCY; ?></td>
                                                        <td class="text-right"><?php echo currency($value->TARIFF_AMOUNT); ?></td>
                                                        <td class="text-center"><?php echo ($value->STATUS == 'A')?'Approved':(($value->STATUS == 'R')?'Rejected':'Waiting Approval'); ?></td>
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
            </form>
            <!-- additional cost -->
            <?php echo form_open('Order/edit_additional/'.$this->uri->segment(3)); ?>
                <div class="row" id="section4">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Data Additional Cost</div>
                            <div class="panel-body">
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

                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Additional Number</th>
                                            <th>Container No.</th>
                                            <th>Cost Name</th>
                                            <th>Cost Type</th>
                                            <th>Cost Group</th>
                                            <th>Currency</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $loop_code = 0;
                                            foreach ($data_additional as $key => $value) {
                                                ?>
                                                    <tr id="baris2<?php echo $loop_code; ?>">
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-danger btn_remove2" data-id="<?php echo $loop_code; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                                            <!-- input hidden -->
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][additional_number]" value="<?php echo $value->ADDITIONAL_NUMBER; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][cost_kind]" value="<?php echo $value->COST_KIND; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][cost_currency]" value="<?php echo $value->COST_CURRENCY; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][cost_type_id]" value="<?php echo $value->COST_TYPE_ID; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][cost_group_id]" value="<?php echo $value->COST_GROUP_ID; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][cost_request_amount]" value="<?php echo $value->COST_REQUEST_AMOUNT; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][request_date]" value="<?php echo $value->REQUEST_DATE; ?>" />
                                                            <input type="hidden" name="cost_appr[<?php echo $loop_code; ?>][user_id_request]" value="<?php echo $value->USER_ID_REQUEST; ?>" />
                                                        </td>
                                                        <td><?php echo $value->ADDITIONAL_NUMBER; ?></td>
                                                        <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                        <td><?php echo $value->COST_NAME; ?></td>
                                                        <td><?php echo $value->COST_TYPE; ?></td>
                                                        <td><?php echo $value->COST_GROUP; ?></td>
                                                        <td><?php echo $value->COST_CURRENCY; ?></td>
                                                        <td class="text-right"><?php echo currency($value->COST_REQUEST_AMOUNT); ?></td>
                                                    </tr>
                                                <?php
                                                $loop_code++;
                                            }
                                        ?>
                                        <!-- <tr>
                                            <td></td>
                                            <td>adawd</td>
                                        </tr> -->
                                    </tbody>
                                </table>
                                <?php
                                    if (count($data_additional) < 1) {
                                        ?>
                                            <button type="submit" disabled class="btn btn-primary">Save</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        <?php
                                    }
                                ?>
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
    // convert rupiah
    function toRp(angka){
        var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
        var rev2    = '';
        for(var i = 0; i < rev.length; i++){
            rev2  += rev[i];
            if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                rev2 += ',';
            }
        }
        return rev2.split('').reverse().join('');
    }

    $(document).ready(function() {

        $('#table-selling').DataTable({
            responsive: true,
        });
        $('.duit').autoNumeric('init',{vMin: 0, vMax: 9999999999});
        $(".js-example-basic-single").select2();
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        
        $('#rec_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        // autocomplete hoarding
        $("#pic_name").autocomplete({
          source: "<?php echo site_url('Order/search_nik'); ?>",
          minLength:2,
          select:function(event, data){
            $('input[name=pic_id]').val(data.item.pic_id);
          }
        });

        $('#tableReim').DataTable({
            responsive: true,
            'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': ['nosort']
            }],
            "order": [[ 3, "asc" ]],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                total = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 6 ).footer() ).html(toRp(total));

                // Total over all pages
                total2 = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal2 = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 7 ).footer() ).html(toRp(total2));
            }
        });

        $(document).on('click', '.btn_remove', function(){
          var button_id = $(this).attr("id");
          $('#baris'+button_id+'').remove();
        });

        $(document).on('click', '.btn_remove2', function(){
          var button_id2 = $(this).attr("data-id");
          $('#baris2'+button_id2+'').remove();
        });
    });

    $('#cost_id').change(function() {
        selectedOption = $('option:selected', this);
        $('input[name=currency]').val( selectedOption.data('currency') );
        $('input[name=cost_type_id]').val( selectedOption.data('type') );
        $('input[name=cost_group_id]').val( selectedOption.data('group') );
        $('input[name=cost_amount]').val( selectedOption.data('amount') );
    });

    $(document).on('click', '.btn_remove2', function(){
          var button_id2 = $(this).attr("id");
          $('#baris2'+button_id2+'').remove();
        });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
