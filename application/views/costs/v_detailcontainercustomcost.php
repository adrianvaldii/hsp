<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

// load helper currency
$this->load->helper('currency_helper');

// echo "<pre>";
// print_r($hasil);
// echo "</pre>";

// die();

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
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Detail Cost
                </div>
                <div class="panel-body">
                    <div class="detail-name">
                        <div class="row">
                            <div class="col-md-5">
                                <table>
                                    <tr>
                                        <td><strong>Company Name</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td class="text-capitalize"><?php echo $company_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Service Name</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td class="text-capitalize"><?php echo $service_name; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td><strong>Custom Location</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td class="text-capitalize"><?php echo $custom_location; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Custom Line</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td class="text-capitalize"><?php echo $custom_line; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-3">
                                <table>
                                    <tr>
                                        <td><strong>Container Type</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td class="text-capitalize"><?php echo $container_type; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Container Category</strong></td>
                                        <td style="padding: 20px 20px;">:</td>
                                        <td class="text-capitalize"><?php echo $container_category; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="size_20">
                        <h4><strong>Container Size 20</strong></h4>
                        <table class="table table-striped table-bordered" id="table-container-custom-cost-20">
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
                                    foreach($custom_cost_detail_20 as $data){ 
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $data->COST_NAME; ?>
                                            <br>
                                            <?php echo anchor_popup('Cost/edit_customs_cost/'.$data->COMPANY_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->COST_ID.'/'.'20'.'/'.$data->CONTAINER_TYPE_ID.'/'.$data->CONTAINER_CATEGORY_ID.'/'.$data->FROM_QTY.'/'.$data->TO_QTY.'/'.$data->CUSTOM_LOCATION_ID.'/'.$data->CUSTOM_LINE_ID.'/'.$data->CUSTOM_KIND_ID.'/'.$data->START_DATE.'/'.$data->END_DATE,'Edit Cost', $attributes); ?>
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
                    <hr>
                    <div class="size_40">
                        <h4><strong>Container Size 40</strong></h4>
                        <table class="table table-striped table-bordered" id="table-container-custom-cost-40">
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
                                    foreach($custom_cost_detail_40 as $data){ 
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $data->COST_NAME; ?>
                                            <br>
                                            <?php echo anchor_popup('Cost/edit_customs_cost/'.$data->COMPANY_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->COST_ID.'/'.'20'.'/'.$data->CONTAINER_TYPE_ID.'/'.$data->CONTAINER_CATEGORY_ID.'/'.$data->FROM_QTY.'/'.$data->TO_QTY.'/'.$data->CUSTOM_LOCATION_ID.'/'.$data->CUSTOM_LINE_ID.'/'.$data->CUSTOM_KIND_ID.'/'.$data->START_DATE.'/'.$data->END_DATE,'Edit Cost', $attributes); ?>
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
                    <hr>
                    <div class="size_4h">
                        <h4><strong>Container Size 4H</strong></h4>
                        <table class="table table-striped table-bordered" id="table-container-custom-cost-4h">
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
                                    foreach($custom_cost_detail_4h as $data){ 
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $data->COST_NAME; ?>
                                            <br>
                                            <?php echo anchor_popup('Cost/edit_customs_cost/'.$data->COMPANY_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->COST_ID.'/'.'20'.'/'.$data->CONTAINER_TYPE_ID.'/'.$data->CONTAINER_CATEGORY_ID.'/'.$data->FROM_QTY.'/'.$data->TO_QTY.'/'.$data->CUSTOM_LOCATION_ID.'/'.$data->CUSTOM_LINE_ID.'/'.$data->CUSTOM_KIND_ID.'/'.$data->START_DATE.'/'.$data->END_DATE,'Edit Cost', $attributes); ?>
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
                    <hr>
                    <div class="size_45">
                        <h4><strong>Container Size 45</strong></h4>
                        <table class="table table-striped table-bordered" id="table-container-custom-cost-45">
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
                                    foreach($custom_cost_detail_45 as $data){ 
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $data->COST_NAME; ?>
                                            <br>
                                            <?php echo anchor_popup('Cost/edit_customs_cost/'.$data->COMPANY_ID.'/'.$data->SELLING_SERVICE_ID.'/'.$data->COST_ID.'/'.'20'.'/'.$data->CONTAINER_TYPE_ID.'/'.$data->CONTAINER_CATEGORY_ID.'/'.$data->FROM_QTY.'/'.$data->TO_QTY.'/'.$data->CUSTOM_LOCATION_ID.'/'.$data->CUSTOM_LINE_ID.'/'.$data->CUSTOM_KIND_ID.'/'.$data->START_DATE.'/'.$data->END_DATE,'Edit Cost', $attributes); ?>
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
                    <hr>
                    <a href="<?php echo site_url('Cost/index'); ?>" class="btn btn-outline btn-primary"><span class="glyphicon glyphicon-menu-left"></span> Back</a>
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
        $('#table-container-custom-cost-20').DataTable({
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
        $('#table-container-custom-cost-40').DataTable({
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
        $('#table-container-custom-cost-4h').DataTable({
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
        $('#table-container-custom-cost-45').DataTable({
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