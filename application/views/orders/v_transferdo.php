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
            <h3>Transfer DO <a href="<?php echo site_url('Order/view_all_transaction'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search PIC</div>
                        <div class="panel-body">
                            <?php echo form_open('Order/entry_transfer_do'); ?>
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="form-group">
                                    <label>PIC Name <span style="color:red">*</span></label>
                                    <input type="text" name="pic_name" class="form-control" id="pic_name">
                                    <input type="hidden" name="pic_id" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Date <span style="color:red">*</span></label>
                                    <input type="text" name="date" class="form-control" id="date">
                                </div>
                                <?php
                                    /*
                                        <div class="form-group">
                                            <label>Work Order Number <span style="color:red">*</span></label>
                                            <select class="form-control js-example-basic-single js-states" name="work_order_number">
                                                <option <?php echo set_select('work_order_number', '', TRUE); ?> ></option>
                                                <?php 
                                                    foreach ($data_wo as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value->WORK_ORDER_NUMBER; ?>" <?php echo set_select('work_order_number', $value->WORK_ORDER_NUMBER, FALSE); ?> ><?php echo $value->WORK_ORDER_NUMBER; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    */
                                ?>
                                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
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
        $(".js-example-basic-single").select2({
            theme: "bootstrap"
          });
        
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
