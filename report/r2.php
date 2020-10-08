<?php
include '../_base.php';
$p->title = 'Daily Sales Report';

$p->auth('admin, staff');

// chart data -------------------------------------------------------------------------------------
if ($p->req('data')) {
    // prepare data
    $month = $p->req('month');

    $stmt = $db->prepare(
        "SELECT DATE(datetime), SUM(totalamount)
         FROM `order`
         WHERE DATE_FORMAT(datetime, '%Y-%m') = ?
         GROUP BY DATE(datetime)
         ORDER BY datetime
        "
    );

    $stmt->execute([$month]);
    $data = $stmt->fetchAll(PDO::FETCH_NUM);
    
    // output json
    ob_clean();
    header('content-type: application/json');
    echo json_encode($data, JSON_NUMERIC_CHECK);
    exit();
}

// page data --------------------------------------------------------------------------------------

// get min & max month from database
$m = $db->query(
    "SELECT
        DATE_FORMAT(MIN(datetime), '%Y-%m') AS min,
        DATE_FORMAT(MAX(datetime), '%Y-%m') AS max
     FROM `order`
    "
)->fetch();

$month = $m->max;

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<style>
    #chart {
        width: 800px; height: 400px;
    }
</style>

<a href="report.php" class="btn btn-outline-info">Back</a> 
<br></br>

<form novalidate>
    <?= $h->month('month', null, $m->min, $m->max) ?>
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
        dt.addColumn('date', 'Day');
        dt.addColumn('number', 'Sales (RM)');

        opt = {
            title: 'Daily Sales Report',
            fontName: 'Calibri',
            fontSize: 14,
            titleTextStyle: { fontSize: 20 },
            chartArea: {
                width: '85%',
                height: '70%',
                top: 60,
                left: 80,
            },
            legend: 'none',
            vAxis: {
                title: 'Sales (RM)',
                titleTextStyle: style,
                format: '#,##0.00',
            },
            hAxis: {
                title: 'Date',
                titleTextStyle: style,
                maxTextLines: 1,
                format: 'dd MMMM YYYY',
            },
            colors: ['green'],
            animation: { duration: 500, startup: true },
        };

        cht = new google.visualization.LineChart($('#chart')[0]);

        $('form').submit();
    }

    $('form').on('submit', function (e) {
        e.preventDefault();

        let param = $(this).serialize();
        $.getJSON('?data=1', param, function (data) {
            for (let row of data) {
                row[0] = new Date(row[0]);
            }
            
            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);

            let f = new google.visualization.NumberFormat();
            f.format(dt, 1);
            let f2 = new google.visualization.DateFormat({ pattern: 'dd MMMM YYYY' });
            f2.format(dt, 0);
            
            cht.draw(dt, opt);

            // count total
            sum = 0.00;
            for (let i in data) {
                sum += data[i][1];
            }

            // display total
            $('#totalval').html(sum.toFixed(2));
        });
    });

    $('#month').on('change', function (e) {
        if (this.value < this.min || this.value > this.max) {
            this.value = this.max;
            return;
        }
        $(this).submit();
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