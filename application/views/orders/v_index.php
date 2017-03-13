<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Work Order Data <a href="<?php echo site_url('Order/entry_wo') ?>" class="btn btn-success">New Work Order</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            <table class="table table-bordered display stripe" id="table-customer" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">WO Number</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Trade</th>
                        <th class="text-center">Services</th>
                        <!-- <th class="text-center">Vessel</th> -->
                        <th class="text-center">Qty</th>
                        <th class="text-center">PIB/PEB</th>
                        <th class="text-center">Trucking</th>
                        <th class="text-center">SPPB/NPE</th>
                        <th class="text-center">Cash Request</th>
                        <th class="text-center">Cash Process</th>
                        <th class="text-center">Invoice Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($result_wo as $key => $value) {
                            $tampung = $value['DATE_RECEIVED'] . ' - ' . $value['NAME_RECEIVED'];
                            $string = strip_tags($tampung);
                            $hasil = substr($string, 0, 18)."...";

                            // REQUEST
                            $tampung2 = $value['DATE_REQUEST'] . ' - ' . $value['NAME_REQUEST'];
                            $string2 = strip_tags($tampung2);
                            $hasil2 = substr($string2, 0, 18)."...";
                            
                            $date_temp = strtotime($value['WORK_ORDER_DATE']);
                            $date_fix = date('d-m-y', $date_temp);
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                            if ($value['INVOICE_NUMBER'] != "") {
                                                echo $value['WORK_ORDER_NUMBER'];
                                                // echo anchor('Order/view_wo/'.$value['WORK_ORDER_NUMBER'].'/'.$value['AGREEMENT_NUMBER'], $value['WORK_ORDER_NUMBER'], array('class' => 'text-center'));
                                            } else {
                                                echo anchor('Order/edit_wo/'.$value['WORK_ORDER_NUMBER'].'/'.$value['AGREEMENT_NUMBER'], $value['WORK_ORDER_NUMBER'], array('class' => 'text-center'));
                                            }

                                            // echo anchor('Order/edit_wo/'.$value['WORK_ORDER_NUMBER'].'/'.$value['AGREEMENT_NUMBER'], $value['WORK_ORDER_NUMBER'], array('class' => 'text-center')); 
                                        ?>
                                    </td>
                                    <td><?php echo substr($value['CUSTOMER_NAME'], 0, 26); ?></td>
                                    <td class="text-center"><?php echo $date_fix; ?></td>
                                    <td class="text-center"><?php echo $value['TRADE']; ?></td>
                                    <td class="text-center"><?php echo $value['SERVICES']; ?></td>
                                    <?php /* <td><?php echo $value['VESSEL_NAME']; ?></td> */ ?>
                                    <td class="text-center">
                                        <?php
                                            if ($value['TOTAL_20'] > 0) {
                                                echo $value['TOTAL_20'] . " x " . "20";
                                                echo "<br>";
                                            }
                                            if ($value['TOTAL_40'] > 0) {
                                                echo $value['TOTAL_40'] . " x " . "40";
                                                echo "<br>";
                                            }
                                            if ($value['TOTAL_4H'] > 0) {
                                                echo $value['TOTAL_4H'] . " x " . "4H";
                                                echo "<br>";
                                            }
                                            if ($value['TOTAL_45'] > 0) {
                                                echo $value['TOTAL_45'] . " x " . "45";
                                                echo "<br>";
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            if ($value['REGISTER_NUMBER_PIB_PEB'] == "") {
                                                echo anchor('Order/entry_customs/'.$value['WORK_ORDER_NUMBER'], 'Entry', array('class' => 'text-center'));
                                            } elseif ($value['INVOICE_NUMBER'] != "") {
                                                echo $value['REGISTER_NUMBER_PIB_PEB'];
                                            } else {
                                                echo anchor('Order/entry_customs/'.$value['WORK_ORDER_NUMBER'], $value['REGISTER_NUMBER_PIB_PEB'], array('class' => 'text-center'));
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            echo anchor('Order/view_detail_trucking/'.$value['WORK_ORDER_NUMBER'], 'Detail', array('class' => 'text-center'));
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            if ($value['REGISTER_NUMBER_SPPB_SPEB'] == "") {
                                                echo anchor('Order/entry_sppb/'.$value['WORK_ORDER_NUMBER'], 'Entry', array('class' => 'text-center'));
                                            } elseif ($value['INVOICE_NUMBER'] != "") {
                                                echo $value['REGISTER_NUMBER_SPPB_SPEB'];
                                            } else {
                                                echo anchor('Order/entry_sppb/'.$value['WORK_ORDER_NUMBER'], $value['REGISTER_NUMBER_SPPB_SPEB'], array('class' => 'text-center'));
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            // if ($value['DATE_REQUEST'] == "") {
                                            //     echo anchor('Order/entry_cash_request/'.$value['WORK_ORDER_NUMBER'], 'Detail', array('class' => 'text-center'));
                                            // } elseif ($value['INVOICE_NUMBER'] != "") {
                                            //     echo $hasil2;
                                            // } else {
                                            //     echo anchor('Order/entry_cash_request/'.$value['WORK_ORDER_NUMBER'], $hasil2, array('class' => 'text-center'));
                                            // }

                                            echo anchor('Order/entry_cash_request/'.$value['WORK_ORDER_NUMBER'], 'Detail', array('class' => 'text-center'));
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            // if ($value['DATE_RECEIVED'] == "") {
                                            //     echo anchor('Order/entry_cash_received/'.$value['WORK_ORDER_NUMBER'], 'Entry', array('class' => 'text-center'));
                                            // } elseif ($value['INVOICE_NUMBER'] != "") {
                                            //     echo $hasil;
                                            // } else {
                                            //     echo anchor('Order/entry_cash_received/'.$value['WORK_ORDER_NUMBER'], $hasil, array('class' => 'text-center'));
                                            // }

                                            echo anchor('Order/entry_cash_received/'.$value['WORK_ORDER_NUMBER'], 'Detail', array('class' => 'text-center'));
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            echo $value['INVOICE_NUMBER'];
                                        ?>
                                    </td>
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
    $(document).ready(function() {
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

        $('#table-customer').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 0, "desc" ]],
                'stripeClasses':['stripe1','stripe2']
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
