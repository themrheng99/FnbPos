<?php
include '../_base.php';
$p->title = 'Number of User Chart';

// $p->auth('admin'); <-- disable authorization for brevity

// chart data -------------------------------------------------------------------------------------
if ($p->req('data')) {
    // TODO: prepare data
    $stm = $db->query(
        "SELECT `role`, COUNT(*)
         FROM user
         GROUP BY `role`"
    );
    $data = $stm->fetchAll(PDO::FETCH_NUM);
    
    // output json
    ob_clean();
    header('content-type: application/json');
    echo json_encode($data, JSON_NUMERIC_CHECK);
    exit();
}

// page data --------------------------------------------------------------------------------------



// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<style>
    #chart {
        width: 600px; height: 400px;
    }
</style>

<div id="chart"></div>

<p>
    <button id="toggle">Toggle Orientation</button>
    <button id="download">Download</button>
</p>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(init);

    let dt, opt, cht;
    let style = { bold: true, italic: false, fontSize: 20, color: 'purple' };

    function init() {
        dt = new google.visualization.DataTable();
        dt.addColumn('string', 'Status');
        dt.addColumn('number', 'Count');

        opt = {
            title: 'User Count By Role',
            fontName: 'Calibri',
            fontSize: 14,
            titleTextStyle: { fontSize: 20 },
            chartArea: {
                width: '80%',
                height: '70%',
                top: 60,
                left: 80,
            },
            // TODO: add options
            legend: 'none',
            vAxis: {
                title: 'User Count',
                titleTextStyle: style,
                minValue: 0,
            },
            hAxis: {
                title: 'Role',
                titleTextStyle: style,
            },
            animation: {
                duration: 500,
                startup: true,
            },
            orientation: 'horizontal',
        };

        // column chart
        cht = new google.visualization.ColumnChart($('#chart')[0]);

        let param = {};
        $.getJSON('?data=1', param, function (data) {
            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);
            cht.draw(dt, opt);
        });
    }

    // TODO: toggle button click event
    $('#toggle').on('click', function (e) {
        e.preventDefault();
        
        opt.orientation = opt.orientation == 'horizontal' ? 'vertical' : 'horizontal';
        [opt.vAxis, opt.hAxis] = [opt.hAxis, opt.vAxis];
        cht.draw(dt, opt);
    });

    // TODO: download button click event
    $('#download').on('click', function (e) {
        e.preventDefault();
        
        let a = $('<a>')[0];
        a.href = cht.getImageURI();
        a.download = Date.now() + '.png';
        a.click();
    });
</script>

<?php
include '../_footer.php';