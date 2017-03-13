<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');

// load helper currency
$this->load->helper('currency_helper');

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 950)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);

?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Cost Detail</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Detail Cost
                </div>
                <div class="panel-body">
                    <div class="detail-name">
                        <div class="row">
                            <div class="col-md-6">
                                <table>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Company Name</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->COMPANY_NAME; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Service Name</strong></td>
                                                <td style="padding: 20px 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->SERVICE_NAME; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>From / To</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->FROM_NAME; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <tr>
                                        <td><strong>Floor Price</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td><?php echo $tariff_currency . " " . currency($floor_price); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Destination</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->TO_NAME; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Distance</strong></td>
                                                <td style="padding: 20px 20px;">:</td>
                                                <td><?php echo $value->DISTANCE . " Km"; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <tr>
                                        <td><strong>Market Price</strong></td>
                                        <td style="padding: 0 20px;">:</td>
                                        <td><?php echo $tariff_currency . " " . currency($market_price); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="size_20">
                        <table class="table table-striped table-bordered" id="table-container-cost-20">
                            <thead>
                                <tr>
                                    <th>Cost Name</th>
                                    <th>Cost Type</th>
                                    <th>Cost Group</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Cost Amount</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total:</th>
                                    <th style="text-align:right"></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    foreach($cost_detail as $key => $data){ 
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $data->COST_NAME; ?>
                                        </td>
                                        <td><?php echo $data->COST_TYPE; ?></td>
                                        <td><?php echo $data->COST_GROUP; ?></td>
                                        <td><?php echo $data->START_DATE; ?></td>
                                        <td><?php echo $data->END_DATE; ?></td>
                                        <td style="text-align: right;">
                                            <?php echo currency($data->COST_AMOUNT); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
    $(document).ready(function() {
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
        // datatables size 20
        $('#table-container-cost-20').DataTable({
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
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
         
                    // Total over this page
                    pageTotal = api
                        .column( 5, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
         
                    // Update footer
                    $( api.column( 5 ).footer() ).html(toRp(total));
                }
        });
    });


</script>

<?php
    $this->load->view('layouts/footer.php');
?>