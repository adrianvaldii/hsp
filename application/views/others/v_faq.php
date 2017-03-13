<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');

?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');

    #accordion {
        font-family: 'Source Sans Pro', sans-serif;
    }
</style>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3 class="text-center">Frequently Asked Questions</h3>
            <hr>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Bagaimana cara melihat WO (Work Order) yang sudah dibuatkan voucher permintaan biaya ?</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <strong>Klik Menu Operation – View all Transaction</strong>
                            <br>
                            Akan terlihat tabel data-data WO yang sudah dibuatkan voucher.
                            <br>
                            <hr>
                            <strong>Bagaimana cara melihat lebih Detail ?</strong>
                            <br>
                            Klik Detail di kolom Action.
                            <br>
                            <hr>
                            <strong>Bagaimana cara mencari nomor WO yang dimaksud ?</strong>
                            <br>
                            Arahkan mouse ke “Search” ketik nomor WO, bisa full nomor WO  atau sebagian nomor saja. Fungsi search ini sangat luas, selain nomor WO, dapat juga mencari berdasarkan Nama Customer, nomor Voucher, dst.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">Bagaimana cara melihat WO yang belum di buat pertanggung jawaban ?</a>
                        </h4>
                    </div>
                    <div id="collapseTen" class="panel-collapse collapse">
                        <div class="panel-body">
                            <strong>Klik Menu – Finance – Cost Tranfered</strong>
                            <br>
                            Akan terlihat nama PIC, pilih salah satu PIC dan klik detail maka akan terlihat data detail WO yang belum di buat pertanggung jawaban.
                            <br>
                            <hr>
                            <strong>Bagaiman cara mencari nomor WO yang dimaksud ?</strong>
                            <br>
                            Arahkan mouse ke “Search” ketik nomor WO, bisa full nomor WO  atau sebagian nomor saja. Fungsi search ini sangat luas, selain nomor WO, dapat juga mencari berdasarkan Nama Customer, nomor Voucher, dst.
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
    
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
