<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h3>Change Container Number</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form Entry</div>
                        <div class="panel-body">
                            <?php echo form_open('Order/entry_container'); ?>
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

                                <div class="row">
                                    <div class="col-md-12">
                                        <p><strong>Work Order Number</strong> : <?php echo $work_order_number; ?></p>
                                        <input type="hidden" name="work_order_number" value="<?php echo $work_order_number; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Container Number (OLD)</th>
                                                    <th>Container Number (New)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $no = 1;
                                                    $loop = 0;
                                                    foreach ($all_wo as $key => $value) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td>
                                                                    <?php echo $value->CONTAINER_NUMBER; ?>
                                                                    <!-- input hidden -->
                                                                    <input type="hidden" name="container[][]" value="<?php echo $work_order_number; ?>">
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        $no++;
                                                        $loop++;
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                                </form>
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
      $(".js-example-basic-single").select2({
        theme: "bootstrap"
      });
      $('#amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
      $('#invoice_amount').autoNumeric('init',{vMin: 0, vMax: 9999999999});
    });

    $('#work_order_number').on('change',function () {
        var year = $(this).val();
        // $.ajax({
        //     url: 'url for get data',
        //     type: 'POST',
        //     data: {year: year},
        //     success: function (a) {
        //         data = JSON.parse(a);
        //         #get data and print data with each loop
        //     }
        // });
        // alert(year);
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
