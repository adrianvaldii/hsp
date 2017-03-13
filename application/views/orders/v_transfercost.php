<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Transfer Cost (Posting)</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Entry Transfer Cost</div>
                <div class="panel-body">
                    <?php echo form_open('Order/entry_transfer'); ?>
                        <div class="form-group">
                            <label>Transaction Number</label>
                            <input type="text" name="transaction_number" class="form-control" readonly="true">
                        </div>
                        <table class="table table-striped table-bordered" id="tableTransfer">
                            <thead>
                                <tr>
                                    <th>Work Order Number</th>
                                    <th>Cost</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align:right">Total:</th>
                                    <th style="text-align:right"></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                    foreach ($data_transfer as $key => $value) {
                                        ?>
                                            <tr>
                                                <td><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                                <td><?php echo $value->COST_NAME; ?></td>
                                                <td><?php echo $value->COST_GROUP; ?></td>
                                                <td><?php echo $value->COST_DATE; ?></td>
                                                <td><?php echo $value->COST_CURRENCY; ?></td>
                                                <td class="text-right"><?php echo currency($value->COST_RECEIVED_AMOUNT); ?></td>
                                            </tr>
                                        <?php
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
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
         
                    // Total over this page
                    pageTotal = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
         
                    // Update footer
                    $( api.column( 4 ).footer() ).html(toRp(total));
                }
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
