<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$date = date('Y-m-d H:i:s');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Edit Operational Cost <a href="<?php echo site_url('Order/view_operational_cost'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <!-- detail -->
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <table>
                <tr>
                    <td><strong>Work Order Number</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td>
                        <?php echo $this->uri->segment(3); ?>
                        <input type="hidden" name="trx_operational" value="<?php echo $trx_operational; ?>" />
                        <input type="hidden" name="work_order_number" value="<?php echo $work_order_number; ?>" />
                        <input type="hidden" name="pic_id" value="<?php echo $pic_id; ?>" />
                    </td>
                </tr>
                <tr>
                    <td><strong>PIC Name</strong></td>
                    <td style="padding: 10px 10px;">:</td>
                    <td><?php echo $pic_name; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <table>
                <tr>
                    <td><strong>Customer Name</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $customer_name; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <?php echo form_open('Order/edit_operational_cost/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5)); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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

                <?php if(isset($pic_error) && $pic_error == "error") { ?>
                    <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo "You do not have permission to change this Operational Cost!"; ?>
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
        <!-- table -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Work Order Number</th>
                            <th>Container Number</th>
                            <th>Cost Name</th>
                            <th>Cost Type</th>
                            <th>Cost Group</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Mutation Account</th>
                            <th>Actual Amount</th>
                        </tr>      
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $loop = 0;
                            foreach ($data_detail as $key => $value) {
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $no; ?>
                                            <!-- input hidden -->
                                            <input type="hidden" name="opr[<?php echo $loop; ?>][trx_operational]" value="<?php echo $value->TRX_OPERATIONAL; ?>" />
                                            <input type="hidden" name="opr[<?php echo $loop; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                            <input type="hidden" name="opr[<?php echo $loop; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                            <input type="hidden" name="opr[<?php echo $loop; ?>][sequence_id]" value="<?php echo $value->SEQUENCE_ID; ?>" />
                                            <input type="hidden" name="opr[<?php echo $loop; ?>][home_debit]" id="muts<?php echo $loop; ?>" value="<?php echo $value->HOME_DEBIT; ?>" />

                                            <!-- hidden temporary -->
                                            <input type="hidden" name="temp[<?php echo $loop; ?>][trx_operational]" value="<?php echo $value->TRX_OPERATIONAL; ?>" />
                                            <input type="hidden" name="temp[<?php echo $loop; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                            <input type="hidden" name="temp[<?php echo $loop; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                            <input type="hidden" name="temp[<?php echo $loop; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                            <input type="hidden" name="temp[<?php echo $loop; ?>][sequence_id]" value="<?php echo $value->SEQUENCE_ID; ?>" />
                                            <input type="hidden" name="temp[<?php echo $loop; ?>][transaction_id]" id="muts<?php echo $loop; ?>" value="<?php echo $value->TRANSACTION_ID; ?>" />
                                        </td>
                                        <td class="text-center"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                        <td class="text-center"><?php echo $value->CONTAINER_NUMBER; ?></td>
                                        <td class="text-center"><?php echo $value->COST_NAME; ?></td>
                                        <td class="text-center"><?php echo $value->COST_TYPE_ID; ?></td>
                                        <td class="text-center"><?php echo $value->COST_GROUP_ID; ?></td>
                                        <td class="text-center"><?php echo $value->COST_CURRENCY ?></td>
                                        <td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
                                        <td>
                                            <select class="form-control js-example-basic-single js-states mutation" name="opr[<?php echo $loop; ?>][mutation_account]" id="<?php echo $loop; ?>" onChange="changeMutation('<?=$loop;?>')">
                                                <option></option>
                                                <?php 
                                                    foreach ($data_mutation as $key1 => $value1) {
                                                        // $big_font = strtoupper($value1->TRANSACTION_ID);
                                                        // echo $big_font;
                                                        ?>
                                                            <option value="<?php echo $value1->TRANSACTION_ID; ?>" data-amount="<?php echo $value1->HOME_DEBIT; ?>" <?php echo ($value->TRANSACTION_ID === strtoupper($value1->TRANSACTION_ID))?"selected":"adwa"; ?> > <?php echo $value1->TRANSACTION_DATE . " ++ " . $value1->DESCRIPTION_1 . " ++ " . currency($value1->HOME_DEBIT); ?> </option>

                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-right">
                                            <input style="text-align:right" type="text" name="opr[<?php echo $loop; ?>][actual_amount]" class="form-control actual_amount" value="<?php echo $value->COST_ACTUAL_AMOUNT; ?>" id="mut<?php echo $loop; ?>">
                                        </td>
                                    </tr>
                                <?php
                                $no++;
                                $loop++;
                            }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
    <br>
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

    function changeMutation(id)
    {
      var amount = $('#'+id).find(":selected").attr("data-amount");
      $('#mut'+id).val(toRp(amount));
      $('#muts'+id).val(amount);
      // alert(amount);
    }

    $(document).ready(function() {
        $('.actual_amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        
        $('.actual_date').datetimepicker({
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
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 9, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 9 ).footer() ).html(toRp(total));

                // Total over all pages
                total2 = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal2 = api
                    .column( 10, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 10 ).footer() ).html(toRp(total2));
            }
        });
    });

    $('#cost_id').change(function() {
        selectedOption = $('option:selected', this);
        $('input[name=currency]').val( selectedOption.data('currency') );
        $('input[name=cost_type_id]').val( selectedOption.data('type') );
        $('input[name=cost_group_id]').val( selectedOption.data('group') );
        $('input[name=cost_amount]').val( selectedOption.data('amount') );
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
