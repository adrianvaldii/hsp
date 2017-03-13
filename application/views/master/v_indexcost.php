<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Master Cost <a href="<?php echo site_url('Master/entry_cost'); ?>" class="btn btn-success">Entry Cost</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Cost ID</th>
                        <th class="text-center">Cost Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Cost Kind</th>
                        <th class="text-center">Cost Type</th>
                        <th class="text-center">Cost Group</th>
                        <th class="text-center">GL Account</th>
                        <th class="text-center">Cost Share</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($data_cost as $key => $value){ 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $value->COST_ID; ?></td>
                        <td class="text-left"><?php echo $value->COST_NAME; ?></td>
                        <td class="text-center"><?php echo $value->DESCRIPTION; ?></td>
                        <td class="text-center"><?php echo ($value->COST_KIND == "S")?"STANDARD COST":"ADDITIONAL COST"; ?></td>
                        <td class="text-center"><?php echo $value->COST_TPS; ?></td>
                        <td class="text-center"><?php echo $value->COST_GPS; ?></td>
                        <td class="text-center"><?php echo $value->GL_ACCOUNT; ?></td>
                        <td class="text-center"><?php echo ($value->COST_SHARE == 'N')?"NO": "YES"; ?></td>
                        <td class="text-center"><?php echo anchor('Master/edit_cost/'.$value->COST_ID, 'Edit', array('class' => 'text-center')); ?></td>
                    </tr>
                    <?php } ?>
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
<script>
    $(document).ready(function() {
        $('#table-service').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }]
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>