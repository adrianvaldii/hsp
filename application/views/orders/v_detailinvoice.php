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
            <h3>Detail Invoice <a href="<?php echo site_url('Order/view_invoice'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <!-- detail -->
    <div class="row">
        <div class="col-md-4 col-md-offset-1">
            <table>
                <tr>
                    <td><strong>Invoice Number</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $invoice_number; ?></td>
                </tr>
                <tr>
                    <td><strong>Customer</strong></td>
                    <td style="padding: 10px 10px;">:</td>
                    <td><?php echo $customer_name; ?></td>
                </tr>
                <tr>
                    <td><strong>Company</strong></td>
                    <td style="padding: 0px 10px;">:</td>
                    <td><?php echo $company_name; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-3 col-md-offset-1">
            <table>
                <tr>
                    <td>
                        <?php
                            echo anchor('Order/print_invoice/'.$this->uri->segment(3), '<span class="glyphicon glyphicon-print"></span> Print', array('class' => 'btn btn-success'));
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
                        <th class="text-center">No.</th>
                        <th class="text-center">Work Order Number</th>
                        <th class="text-center">Container Number</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Kind</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Amount</th>
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
                        foreach ($data_det_inv as $value) {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td class="text-center"><?php echo $value['WORK_ORDER_NUMBER']; ?></td>
                                    <td class="text-center"><?php echo $value['CONTAINER_NUMBER']; ?></td>
                                    <td class="text-left"><?php echo $value['CHARGES_NAME']; ?></td>
                                    <td class="text-left"><?php echo $value['CHARGES_TYPE']; ?></td>
                                    <td class="text-left"><?php echo $value['CHARGES_GROUP']; ?></td>
                                    <td class="text-center"><?php echo $value['CHARGES_CURRENCY']; ?></td>
                                    <td class="text-right"><?php echo currency($value['CHARGES_AMOUNT']); ?></td>
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
        $('#tableReim').DataTable({
            responsive: true,
            "order": [[ 1, "asc" ]],
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
