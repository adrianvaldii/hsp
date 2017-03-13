<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Master General ID</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-striped table-bordered" id="table-service">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Classification ID</th>
                        <th>General ID</th>
                        <th>General Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($general_id as $data){ 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data->CLASSIFICATION_ID; ?></td>
                        <td><?php echo $data->GENERAL_ID; ?></td>
                        <td><?php echo $data->GENERAL_DESCRIPTION; ?></td>
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
                }],
                "order": [[ 1, "asc" ]]
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>