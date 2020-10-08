<?php
include '../_base.php';
$p->title = 'Annual Sales Report';

$p->auth('admin, staff');

// chart data -------------------------------------------------------------------------------------
if ($p->req('data')) {
    //prepare data
    $year = $p->req('year') ?? date('Y');

    $stmt = $db->prepare(
        "SELECT monthname(datetime), sum(totalamount)
        FROM `order`
        WHERE year(datetime) = ? OR ? = 0
        GROUP BY month(datetime)"
    );
    $stmt->execute([$year, $year]);
    $data = $stmt->fetchAll(PDO::FETCH_NUM);

    // output json
    ob_clean();
    header('content-type: application/json');
    echo json_encode($data, JSON_NUMERIC_CHECK);
    exit();
}

$year = '';

include '../_header.php';
?>

<style>
    #chart {
        width: 700px; height: 500px;
    }
</style>
<a href="report.php" class="btn btn-outline-info">Back</a> 
<br></br>

<form>
    <!-- user input -->
    <?= $h->number('year', 2020, 1990, 2100, 1, "pattern='[0-9]'") ?>
    <button>Submit</button>
</form>

<button id="download">Download</button>

<br>
<div id="chart"></div>
<p style='font-size: 16pt'>Total Sales: RM <b><span id='totalval'>-</span></b></p>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(init);

    let dt, opt, cht;
    let style = { bold: true, italic: false, fontSize: 20 };

    function init() {
        dt = new google.visualization.DataTable();
        dt.addColumn('string', 'Month');
        dt.addColumn('number', 'Sales (RM)');

        opt = {
            title: 'Annual Sales Report',
            fontName: 'Calibri',
            fontSize: 14,
            titleTextStyle: { fontSize: 20 },
            chartArea: {
                width: '85%',
                height: '70%',
                top: 60,
                left: 80,
            },
            vAxis: {
                title: 'Amount Sales (RM)',
                titleTextStyle: style,
                gridlines: { multiple: 1 },
                format: '#,##0.00',
            },

            animation: { duration: 500, startup: true },
            isStacked: false,
            focusTarget: 'datum',
            colors: ['green'],
            legend: 'none',
        };

        cht = new google.visualization.ColumnChart($('#chart')[0]);

        $('form').submit();
    }

    // TODO: form submit event
    $('form').on('submit', function (e) {
        e.preventDefault();

        let param = $(this).serialize();
        $.getJSON('?data=1', param, function (data) {
            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);
            let f = new google.visualization.NumberFormat();
            f.format(dt, 1);

            cht.draw(dt, opt);

            // count total
            sum = 0.00;
            for (let i in data) {
                sum += data[i][1];
            }

            //display total
            $('#totalval').html(sum.toFixed(2));
        });
    });

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