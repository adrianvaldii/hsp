<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');

// $this->load->helper('comman_helper');
// pr($sum);
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Entry Operational Cost <a href="<?php echo site_url('Order/entry_operational_cost'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- entry cost to tale hsp and create voucher -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Entry Transfer Cost</div>
                        <div class="panel-body">
                            <?php echo form_open(); ?>
                                <?php 
                                    /*
                                        <?php if(validation_errors()) { ?>
                                            <div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <?php echo validation_errors(); ?>
                                            </div>
                                        <?php } ?>
                                    */
                                ?>

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
                                <div class="row">
                                    <div class="col-md-3">
                                        <table>
                                            <tr>
                                                <td><strong>Work Order Number</strong></td>
                                                <td style="padding: 0px 10px">:</td>
                                                <td>
                                                    <?php echo $work_order_number; ?>
                                                    <input type="hidden" name="work_order_number" value="<?php echo $work_order_number; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>PIC</strong></td>
                                                <td style="padding: 10px 10px">:</td>
                                                <td>
                                                    <?php echo $pic_name; ?>
                                                    <input type="hidden" name="pic_id" value="<?php echo $pic_id; ?>" />        
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        <table>
                                            <tr>
                                                <td><strong>Customer Name</strong></td>
                                                <td style="padding: 0px 10px">:</td>
                                                <td><?php echo $customer_name; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Container Number</th>
                                            <th>Cost Name</th>
                                            <th>Cost Group</th>
                                            <th>Cost Type</th>
                                            <th>Cost Kind</th>
                                            <th>Currency</th>
                                            <th>Amount</th>
                                            <th>Mutation Account</th>
                                            <th>Actual Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $loop = 1;
                                            foreach ($data_cash_request as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $value->CONTAINER_NUMBER; ?>
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][work_order_number]" value="<?php echo $value->WORK_ORDER_NUMBER; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_kind]" value="<?php echo $value->COST_KIND; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][sequence_id]" value="<?php echo $value->SEQUENCE_ID; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_type_id]" value="<?php echo $value->COST_TYPE_ID; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_group_id]" value="<?php echo $value->COST_GROUP_ID; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_currency]" value="<?php echo $value->COST_CURRENCY; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][cost_amount]" value="<?php echo $value->COST_RECEIVED_AMOUNT; ?>" />
                                                            <input type="hidden" name="opr[<?php echo $loop; ?>][home_debit]" id="muts<?php echo $loop; ?>" />
                                                        </td>
                                                        <td><?php echo $value->COST_NAME; ?></td>
                                                        <td><?php echo $value->COST_GROUP; ?></td>
                                                        <td><?php echo $value->COST_TYPE; ?></td>
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
                                                        <td><?php echo currency($value->COST_RECEIVED_AMOUNT); ?></td>
                                                        <td>
                                                            <select required class="form-control js-example-basic-single js-states mutation text-center" name="opr[<?php echo $loop; ?>][mutation_account]" id="<?php echo $loop; ?>" onChange="changeMutation('<?=$loop;?>')">
                                                                <option></option>
                                                                <?php 
                                                                    foreach ($data_mutation as $key => $value) {
                                                                        ?>
                                                                            <option value="<?php echo $value->TRANSACTION_ID; ?>" data-amount="<?php echo $value->HOME_DEBIT; ?>" ><?php echo $value->TRANSACTION_DATE . " ++ " . $value->DESCRIPTION_1 . " ++ " . currency($value->HOME_DEBIT); ?></option>

                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-right">
                                                            <input required style="text-align:right" type="text" name="opr[<?php echo $loop; ?>][actual_amount]" class="form-control actual_amount" value="<?php echo $value->COST_ACTUAL_AMOUNT; ?>" id="mut<?php echo $loop; ?>">
                                                        </td>
                                                    </tr>
                                                <?php
                                                $loop++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                    if (count($data_cash_request) < 1) {
                                        ?>
                                            <button disabled type="submit" name="save_cash" class="btn btn-primary">Save</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="submit" name="save_cash" class="btn btn-primary">Save</button>
                                        <?php
                                    }
                                ?>
                                
                                <?php echo anchor('Order/entry_operational_cost', '<span class="glyphicon glyphicon-chevron-left"></span> Back', array('class' => 'btn btn-default')) ?>
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
        $('.actual_amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});

        $(".js-example-basic-single").select2({
            theme: "bootstrap",
            dropdownCssClass : 'mutation'
          });

        $('.actual_date').datetimepicker({
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
    });

    function changeMutation(id)
    {
      var amount = $('#'+id).find(":selected").attr("data-amount");
      $('#mut'+id).val(toRp(amount));
      $('#muts'+id).val(amount);
      // alert(amount);
    }
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
