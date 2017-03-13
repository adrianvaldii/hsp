<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');

foreach ($data_cost as $key => $value) {
    $sum += $value->COST_AMOUNT;
}

// $this->load->helper('comman_helper');
// pr($sum);
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Transfer Cost <a href="<?php echo site_url('Order/transfer_cost'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <!-- entry cost to tale hsp and create voucher -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Entry Transfer Cost</div>
                        <div class="panel-body">
                            <?php echo form_open(); ?>
                                

                                <?php if($this->session->flashdata('success')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('failed')) { ?>
                                    <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('failed'); ?>
                                    </div>
                                <?php } ?>
                                <table>
                                    <tr>
                                        <td><strong>PIC</strong></td>
                                        <td style="padding: 0px 10px">:</td>
                                        <td><?php echo $pic_name; ?></td>
                                    </tr>
                                </table>
                                <br>
                                <div class="form-group">
                                    <label>Transaction Number</label>
                                    <input type="text" name="transaction_number" class="form-control" readonly="true" value="<?php echo set_value('transaction_number', $transaction_number); ?>">
                                    <input type="hidden" name="receiver" value="<?php echo $pic_id; ?>" />
                                    <input type="hidden" name="receiver_name" value="<?php echo $pic_name; ?>" />
                                    <input type="hidden" name="work_order_number" value="<?php echo $work_order_number; ?>" />
                                </div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Work Order Number</th>
                                            <th>Container Number</th>
                                            <th>Cost</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Currency</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align:right">Total:</th>
                                            <th style="text-align:right"><?php echo currency($sum); ?></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $loop_code = 0;
                                            foreach ($data_cost as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $value->WORK_ORDER_NUMBER; ?>
                                                            <!-- input hidden -->
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][cost_type]" value="<?php echo $value->COST_TYPE_ID; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][cost_group]" value="<?php echo $value->COST_GROUP_ID; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][currency]" value="<?php echo $value->COST_CURRENCY; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][cost_amount]" value="<?php echo $value->COST_AMOUNT; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][pic_id]" value="<?php echo $value->USER_ID_RECEIVED; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][sequence_id]" value="<?php echo $value->SEQUENCE_ID; ?>" />
                                                            <input type="hidden" name="cash[<?php echo $loop_code; ?>][cost_kind]" value="<?php echo $value->COST_KIND; ?>" />
                                                        </td>
                                                        <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                        <td><?php echo $value->COST_NAME; ?></td>
                                                        <td><?php echo $value->COST_TYPE; ?></td>
                                                        <td><?php echo $value->TRANSFER_DATE_ACTUAL; ?></td>
                                                        <td><?php echo $value->COST_CURRENCY; ?></td>
                                                        <td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
                                                    </tr>
                                                <?php
                                                $loop_code++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                    if (count($data_cost) < 1) {
                                        ?>
                                            <button disabled type="submit" name="save_cash" class="btn btn-primary">Save</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="submit" name="save_cash" class="btn btn-primary">Save</button>
                                        <?php
                                    }
                                ?>
                                <?php echo anchor('Order/transfer_cost', '<span class="glyphicon glyphicon-chevron-left"></span> Back', array('class' => 'btn btn-default')) ?>
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
        $('#sppb_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        // autocomplete hoarding
        $("#pic_name").autocomplete({
          source: "<?php echo site_url('Order/search_nik'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=pic_id]').val(data.item.pic_id);
          }
        });

         $('#tableTransfer').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
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
                }
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
