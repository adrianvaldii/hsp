<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$date = date('Y-m-d H:i:s');
$date2 = date('Y-m-d');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Entry Cash Process <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Data Cost</div>
                        <div class="panel-body">
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
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Work Order Number</th>
                                            <th>Container No.</th>
                                            <th>Cost Name</th>
                                            <th>Cost Type</th>
                                            <th>Cost Group</th>
                                            <th>Currency</th>
                                            <th>Amount</th>
                                            <th>Cash Received Date</th>
                                            <th>Cash Received PIC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 0;
                                            foreach ($data_cash_request as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                                        <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                        <td><?php echo $value->COST_NAME; ?></td>
                                                        <td><?php echo $value->COST_TYPE; ?></td>
                                                        <td><?php echo $value->COST_GROUP; ?></td>
                                                        <td><?php echo $value->COST_CURRENCY; ?></td>
                                                        <td class="text-right"><?php echo currency($value->COST_REQUEST_AMOUNT); ?></td>
                                                        <td><input type="text" name="cash[<?php echo $no; ?>][cash_received_date]" id="rec_date" class="form-control datec" value="<?php echo $value->TRANSFER_DATE_ACTUAL; ?>" /></td>
                                                        <td>
                                                            <?php 
                                                            /*
                                                                <input type="text" name="cash[<?php echo $no; ?>][pic_name]" id="nik<?php echo $no; ?>" class="form-control nikk" value="<?php echo $value->NAME; ?>" onClick="search_nik(<?php echo $no; ?>)" />
                                                                
                                                                <input type="hidden" name="cash[<?php echo $no; ?>][pic_id]" id="nik_isi<?php echo $no; ?>" class="form-control nikk_isi" value="<?php echo $value->USER_ID_RECEIVED; ?>" />
                                                            */
                                                            ?>

                                                            <select class="form-control" name="cash[<?php echo $no; ?>][pic_id]">
                                                                <option <?php if($value->USER_ID_RECEIVED == "" || $value->USER_ID_RECEIVED == NULL){ echo 'selected'; } ?> ></option>
                                                                <?php
                                                                    foreach ($data_nik as $key1 => $value1) {
                                                                        $name_fix = rtrim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $value1->Nm_lengkap));
                                                                        ?>
                                                                            <option <?php if($value->USER_ID_RECEIVED == $value1->Nik){ echo 'selected'; } ?> value="<?php echo $value1->Nik; ?>" ><?php echo $name_fix; ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            
                                                            <input type="hidden" name="cash[<?php echo $no; ?>][cost_id]" value="<?php echo $value->COST_ID; ?>" class="form-control" />
                                                            <input type="hidden" name="cash[<?php echo $no; ?>][container_number]" value="<?php echo $value->CONTAINER_NUMBER; ?>" class="form-control" />
                                                            <input type="hidden" name="cash[<?php echo $no; ?>][cost_amount]" value="<?php echo $value->COST_REQUEST_AMOUNT; ?>" class="form-control" />
                                                            <input type="hidden" name="cash[<?php echo $no; ?>][sequence_id]" value="<?php echo $value->SEQUENCE_ID; ?>" class="form-control" />
                                                        </td>
                                                    </tr>
                                                <?php
                                                $no++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">Save</button>
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

    function search_nik(id)
    {
        // autocomplete hoarding
        $('#nik'+id+'').autocomplete({
          source: "<?php echo site_url('Order/search_nik'); ?>",
          minLength:1,
          select:function(event, data){
            $('#nik_isi'+id+'').val(data.item.pic_id);
          }
        });
    }

    $(document).ready(function() {
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        
        $('.datec').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        $('#tableReim').DataTable({
            responsive: true,
            'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': ['nosort']
            }]
        });

        // // autocomplete hoarding
        // $(".nikk").autocomplete({
        //   source: "<?php echo site_url('Order/search_nik'); ?>",
        //   minLength:1,
        //   select:function(event, data){
        //     $('.nikk_isi').val(data.item.pic_id);
        //   }
        // });
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
