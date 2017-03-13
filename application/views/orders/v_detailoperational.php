<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$date = date('Y-m-d H:i:s');
$this->load->helper('currency_helper');

$attr_form = array(
    'name' => 'deklarasi',
    'target' => '_blank'
 );

?>

<!-- content -->
<div class="container-fluid font_mini">
    <?php echo form_open(); ?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h3>Detail of Operational Cost <a href="<?php echo site_url('Order/view_operational_cost'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
                <hr>
            </div>
        </div>
        <!-- notif -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?php if($this->session->flashdata('success_posting')) { ?>
                    <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success_posting'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- detail -->
        <div class="row">
            <div class="col-md-4 col-md-offset-1">
                <table>
                    <tr>
                        <td><strong>Work Order Number</strong></td>
                        <td style="padding: 0px 10px;">:</td>
                        <td><?php echo $this->uri->segment(3); ?></td>
                    </tr>
                    <tr>
                        <td><strong>PIC Name</strong></td>
                        <td style="padding: 10px 10px;">:</td>
                        <td><?php echo $pic_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Operational Date</strong></td>
                        <td style="padding: 0px 10px;">:</td>
                        <td><?php echo $opr_date; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                               echo anchor('Order/print_operational_cost/'.$this->uri->segment(3), '<span class="glyphicon glyphicon-print"></span> Print', array('class' => 'btn btn-success', 'target' => '_blank'));
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 col-md-offset-1">
                <table>
                    <tr>
                        <td><strong>Customer Name</strong></td>
                        <td style="padding: 0px 10px;">:</td>
                        <td><?php echo $customer_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Approval Status</strong></td>
                        <td style="padding: 10px 10px;">:</td>
                        <td><?php echo ($status == 'A')?'Approved':'Waiting Approval' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Voucher Number</strong></td>
                        <td style="padding: 0px 10px;">:</td>
                        <td><?php echo $voucher_number; ?></td>
                    </tr>
                    <?php
                        /*
                            <tr>
                                <td>
                                    <?php
                                        if ($status == 'A' && ($voucher_number == "" || $voucher_number == NULL)) {
                                            echo anchor('Order/posting_operational_cost/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5), '<span class="glyphicon glyphicon-edit"></span> Posting', array('class' => 'btn btn-primary'));                                
                                        } elseif ($voucher_number != NULL || $voucher_number != "") {
                                            echo '<button type="button" class="btn btn-primary" disabled><span class="glyphicon glyphicon-edit"></span> Posting</button>';
                                        } else {
                                            echo '<button type="button" class="btn btn-primary" disabled><span class="glyphicon glyphicon-edit"></span> Posting</button>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        */
                    ?>
                </table>
            </div>
        </div>
        <br>
        <!-- table -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <table class="table table-striped table-bordered display" id="table-customer" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Work Order Number</th>
                            <th>Container Number</th>
                            <th>Cost Name</th>
                            <th>Cost Type</th>
                            <th>Cost Group</th>
                            <th>Description</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Actual Amount</th>
                            <th>Budget Variance</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="8" style="text-align:right">Total:</th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $no = 1;
                            $loop = 0;
                            foreach ($data_detail as $key => $value) {
                                ?>
                                    <tr>
                                        <!-- input hidden -->
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][cost_type]" value="<?php echo $value->COST_TYPE_ID; ?>" />
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][cost_group]" value="<?php echo $value->COST_GROUP_ID; ?>" />
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][cost_amount]" value="<?php echo $value->COST_AMOUNT; ?>" />
                                        <input type="hidden" name="opr[<?php echo $loop; ?>][cost_actual_amount]" value="<?php echo $value->COST_ACTUAL_AMOUNT; ?>" />

                                        <td class="text-center"><?php echo $no; ?></td>
                                        <td class="text-center"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                        <td class="text-center"><?php echo $value->CONTAINER_NUMBER; ?></td>
                                        <td class="text-left"><?php echo $value->COST_NAME; ?></td>
                                        <td class="text-center"><?php echo $value->COST_TYPE; ?></td>
                                        <td class="text-center"><?php echo $value->COST_GROUP; ?></td>
                                        <td class="text-left"><?php echo $value->MUTATION_DESCRIPTION; ?></td>
                                        <td class="text-center"><?php echo $value->COST_CURRENCY ?></td>
                                        <td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
                                        <td class="text-right"><?php echo currency($value->COST_ACTUAL_AMOUNT); ?></td>
                                        <td class="text-right"><?php echo currency(($value->COST_AMOUNT - $value->COST_ACTUAL_AMOUNT)); ?></td>
                                    </tr>
                                <?php
                                $no++;
                                $loop++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <hr>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Declaration</div>
                <div class="panel-body">
                    <?php echo form_open('Order/print_declaration/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5), $attr_form); ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Work Order Number</th>
                                    <th>Container Number</th>
                                    <th>Cost Name</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $loops = 0;
                                    foreach ($data_nonreim as $key => $value) {
                                        ?>
                                            <tr id="<?php echo $loops; ?>">
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn_add_data btn_data<?php echo $loops; ?>" id="add_data<?php echo $loops; ?>" onClick="addDeclare('<?php echo $loops; ?>')"><span class="glyphicon glyphicon-plus"></span></button>

                                                    <input type="hidden" name="temp_cost_id<?php echo $loops; ?>" value="<?php echo $value->COST_ID; ?>" />
                                                    <input type="hidden" name="temp_container_number<?php echo $loops; ?>" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                    <input type="hidden" name="temp_wo<?php echo $loops; ?>" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />

                                                    <?php 
                                                    /*
                                                        <input type="checkbox" name="check_nonreim[<?php echo $loops; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                                        <input type="checkbox" name="check_nonreim[<?php echo $loops; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                        <input type="checkbox" name="check_nonreim[<?php echo $loops; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                                    */
                                                    ?>
                                                </td>
                                                <td class="text-center"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                                <td class="text-center"><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                <td class="text-center"><?php echo $value->COST_NAME; ?></td>
                                                <td class="text-center"><?php echo $value->COST_CURRENCY; ?></td>
                                                <td class="text-right"><?php echo currency($value->COST_ACTUAL_AMOUNT); ?></td>
                                            </tr>
                                        <?
                                        $loops++;
                                    }
                                ?>
                            </tbody>
                        </table>
                        <div id="tempData"></div>
                        <button type="submit" class="btn btn-success">Print Declaration</button>
                        <!-- <button type="button" class="btn btn-default btn_reset">Reset</button> -->
                    </form>
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

        $('#table-customer').DataTable({
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
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 8, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 8 ).footer() ).html(toRp(total));

                // Total over all pages
                total2 = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal2 = api
                    .column( 9, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 9 ).footer() ).html(toRp(total2));

                // Total over all pages
                total3 = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal3 = api
                    .column( 10, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 10 ).footer() ).html(toRp(total3));
            }
        });

        $('#table-nonreim').DataTable({
            responsive: true
        });
    });

    $('#cost_id').change(function() {
        selectedOption = $('option:selected', this);
        $('input[name=currency]').val( selectedOption.data('currency') );
        $('input[name=cost_type_id]').val( selectedOption.data('type') );
        $('input[name=cost_group_id]').val( selectedOption.data('group') );
        $('input[name=cost_amount]').val( selectedOption.data('amount') );
    });

    var number = 0;

    function addDeclare(id)
    {
        // $('.duit').autoNumeric('init',{vMin: 0, vMax: 9999999999});
        var cost_id = document.forms['deklarasi'].elements['temp_cost_id'+id].value
        var container_number = document.forms['deklarasi'].elements['temp_container_number'+id].value
        var work_order_number = document.forms['deklarasi'].elements['temp_wo'+id].value

        // append data
        $('#tempData').append('<input type="hidden" name="check_nonreim['+number+'][cost_id]" value="'+cost_id+'"><input type="hidden" name="check_nonreim['+number+'][container_number]" value="'+container_number+'"><input type="hidden" name="check_nonreim['+number+'][work_order_number]" value="'+work_order_number+'">');
        
        document.getElementById("add_data"+id).disabled = true;
        
        number++;
    }

    function reset()
    {
        $("#tempData").remove();
        // $('#appen').append('<div id="tempData"></div>');
        $('.btn_add_data').disabled = false;
    }

    $(document).on('click', '.btn_reset', function(){
      $('#tempData').remove();
        $('#appen').append('<div id="tempData"></div>');
        $('.btn_add_data').disabled = false;
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
