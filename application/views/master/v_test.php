<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Master Terms and Conditions</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                                foreach ($term as $key => $value) {
                                    ?>
                                        <div class="form-group">
                                            <label>Template Description</label>
                                            <input type="text" name="template_description" class="form-control" value="<?php echo $value->TEMPLATE_DESCRIPTION; ?>">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Syarat dan Kondisi</div>
                                                    <div class="panel-body">
                                                        <?php echo $value->TEMPLATE_TEXT1; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Term and Condition</div>
                                                    <div class="panel-body">
                                                        <?php echo $value->TEMPLATE_TEXT2; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                            ?>
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
<script>
    tinymce.init({ 
          selector:'textarea',
          theme: 'modern',
          plugins: [
            'advlist autolink lists charmap',
            'searchreplace wordcount',
            'save table contextmenu directionality',
            'paste'
          ],
          toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
          toolbar2: 'print preview media | forecolor backcolor emoticons',
          image_advtab: true,
          height: 300,
          resize: false
    });

    $(document).ready(function() {
        $('#table-service').DataTable({
                responsive: true
        });
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
