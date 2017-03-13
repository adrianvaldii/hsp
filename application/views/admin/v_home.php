<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Dashboard</h1>
            <hr>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="col-md-6">
                <div id="chartContainer"></div>
            </div>
            <div class="col-md-6">
                <div id="chartWO"></div>
            </div>
        </div>
    </div> -->
</div>
<!-- end of content -->

<!-- js -->
<?php
    $this->load->view('layouts/js.php');  
?>

<script type="text/javascript">
    $(document).ready(function() {

        var d = new Date();
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";
        var n = month[d.getMonth()];

        var year = d.getFullYear();

        var data_customers = <?php echo json_encode($data_customers); ?>;
        var data_wo = <?php echo json_encode($data_wo); ?>;

        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
                text: "Work Order Customers at "+n+", "+year
            },
            animationEnabled: true,
            legend: {
                verticalAlign: "center",
                horizontalAlign: "left",
                fontSize: 15,
                fontFamily: "Helvetica"
            },
            theme: "theme2",
            data: [
            {
                type: "pie",
                indexLabelFontFamily: "Garamond",
                indexLabelFontSize: 10,
                indexLabel: "{label} ({y})",
                startAngle: -20,
                showInLegend: false,
                toolTipContent: "{legendText}: {y}",
                dataPoints: data_customers
            }
            ]
        });

        var chart_wo = new CanvasJS.Chart("chartWO", {
            title: {
                text: "Work Order in "+year
            },
            animationEnabled: true,
            legend: {
                verticalAlign: "center",
                horizontalAlign: "left",
                fontSize: 15,
                fontFamily: "Helvetica"
            },
            theme: "theme2",
            data: [
            {
                type: "doughnut",
                indexLabelFontFamily: "Garamond",
                indexLabelFontSize: 10,
                indexLabel: "{label} ({y})",
                startAngle: -20,
                showInLegend: false,
                toolTipContent: "{legendText}: {y}",
                dataPoints: data_wo
            }
            ]
        });

        chart.render();
        chart_wo.render();
    });
</script>

<?php
    $this->load->view('layouts/footer.php');
?>