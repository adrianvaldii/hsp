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
<div class="container-fluid font_mini">
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
                                                <td><strong>Container Type</strong></td>
                                                <td style="padding: 0 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->CONTAINER_TYPE; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
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
                                    <?php  
                                        foreach ($details as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong>Destination</strong></td>
                                                <td style="padding: 20px 20px;">:</td>
                                                <td class="text-capitalize"><?php echo $value->TO_NAME; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <tr>
                                        <td><strong>Floor Price</strong></td>
                                        <td style="padding: 0 20px;">:</td>
                                        <td><?php echo $tariff_currency . " " . currency($floor_price); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Market Price</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td><?php echo $tariff_currency . " " . currency($market_price); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Container Size 20</a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="size_20">
                                        <table class="table table-striped table-bordered" id="table-container-cost-20">
                                            <thead>
                                                <tr>
                                                    <th>Cost Name</th>
                                                    <th>Cost Type</th>
                                                    <th>Cost Group</th>
                                                    <th>From Qty</th>
                                                    <th>To Qty</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Cost Amount</th>
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
                                                    foreach($cost_detail_20 as $key => $data){ 
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $data->COST_NAME; ?>
                                                        </td>
                                                        <td><?php echo $data->COST_TYPE; ?></td>
                                                        <td><?php echo $data->COST_GROUP; ?></td>
                                                        <td><?php echo $data->FROM_QTY; ?></td>
                                                        <td><?php echo $data->TO_QTY; ?></td>
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
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Container Size 40</a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="size_40">
                                        <table class="table table-striped table-bordered" id="table-container-cost-40">
                                            <thead>
                                                <tr>
                                                    <th>Cost Name</th>
                                                    <th>Cost Type</th>
                                                    <th>Cost Group</th>
                                                    <th>From Qty</th>
                                                    <th>To Qty</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Cost Amount</th>
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
                                                    foreach($cost_detail_40 as $data){ 
                                                    ?>
                                                   <tr>
                                                        <td>
                                                            <?php echo $data->COST_NAME; ?>
                                                        </td>
                                                        <td><?php echo $data->COST_TYPE; ?></td>
                                                        <td><?php echo $data->COST_GROUP; ?></td>
                                                        <td><?php echo $data->FROM_QTY; ?></td>
                                                        <td><?php echo $data->TO_QTY; ?></td>
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
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Container Size 4H</a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="size_4h">
                                        <table class="table table-striped table-bordered" id="table-container-cost-4h">
                                            <thead>
                                                <tr>
                                                    <th>Cost Name</th>
                                                    <th>Cost Type</th>
                                                    <th>Cost Group</th>
                                                    <th>From Qty</th>
                                                    <th>To Qty</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Cost Amount</th>
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
                                                    foreach($cost_detail_4h as $data){ 
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $data->COST_NAME; ?>
                                                        </td>
                                                        <td><?php echo $data->COST_TYPE; ?></td>
                                                        <td><?php echo $data->COST_GROUP; ?></td>
                                                        <td><?php echo $data->FROM_QTY; ?></td>
                                                        <td><?php echo $data->TO_QTY; ?></td>
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
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Container Size 45</a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="size_45">
                                        <table class="table table-striped table-bordered" id="table-container-cost-45">
                                            <thead>
                                                <tr>
                                                    <th>Cost Name</th>
                                                    <th>Cost Type</th>
                                                    <th>Cost Group</th>
                                                    <th>From Qty</th>
                                                    <th>To Qty</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Cost Amount</th>
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
                                                    foreach($cost_detail_45 as $data){ 
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $data->COST_NAME; ?>
                                                        </td>
                                                        <td><?php echo $data->COST_TYPE; ?></td>
                                                        <td><?php echo $data->COST_GROUP; ?></td>
                                                        <td><?php echo $data->FROM_QTY; ?></td>
                                                        <td><?php echo $data->TO_QTY; ?></td>
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
        
        // datatables size 40
        $('#table-container-cost-40').DataTable({
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
        // datatables size 4H
        $('#table-container-cost-4h').DataTable({
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
        // datatables size 45
        $('#table-container-cost-45').DataTable({
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


</script>

<?php
    $this->load->view('layouts/footer.php');
?>