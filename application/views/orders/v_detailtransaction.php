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
        <div class="col-md-10 col-md-offset-1">
            <h3>Detail Transaction <a href="<?php echo site_url('Order/view_all_transaction'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <!-- detail -->
    <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <table>
                <tr>
                    <td><strong>Transaction Number</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $transaction_number; ?></td>
                </tr>
                <tr>
                    <td><strong>Transaction Date</strong></td>
                    <td style="padding: 10px 10px;">:</td>
                    <td><?php echo $transaction_date; ?></td>
                </tr>
                <tr>
                    <td><strong>Entry by</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $entry_by; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-4 col-md-offset-1">
            <table>
                <tr>
                    <td><strong>eVoucher Code (Out)</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $voucher_out; ?></td>
                </tr>
                <tr>
                    <td><strong>eVoucher Code (In)</strong></td>
                    <td style="padding: 10px 10px;">:</td>
                    <td><?php echo $voucher_in; ?></td>
                </tr>
                <tr>
                    <td><strong>Receiver</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $pic_name; ?></td>
                </tr>
                <tr>
                    <td style="padding: 0px 10px;">
                        <?php
                            echo anchor('Order/print_voucher_detail/'.$transaction_number, '<span class="glyphicon glyphicon-print"></span> Print', array('class' => 'btn btn-success', 'target' => '_blank'))
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <!-- table -->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-striped table-bordered" id="tableReim">
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
                    </tr>      
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="7" style="text-align:right">Total:</th>
                        <th style="text-align:right"></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_detail as $key => $value) {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no?></td>
                                    <td class="text-center"><?php echo $value->WORK_ORDER_NUMBER; ?></td>
                                    <td class="text-center"><?php echo $value->CONTAINER_NUMBER; ?></td>
                                    <td class="text-center"><?php echo $value->COST_NAME; ?></td>
                                    <td class="text-center"><?php echo $value->COST_TYPE; ?></td>
                                    <td class="text-center"><?php echo $value->COST_GROUP; ?></td>
                                    <td class="text-center"><?php echo $value->COST_CURRENCY ?></td>
                                    <td class="text-right"><?php echo currency($value->COST_AMOUNT); ?></td>
                                </tr>
                            <?php
                            $no++;
                        }
                    ?>
                </tbody>
            </table>
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
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 7 ).footer() ).html(toRp(total));
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
