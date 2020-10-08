<?php
include '../_base.php';
$p->title = 'Chart';

// $p->auth('admin'); <-- disable authorization for brevity

// chart data -------------------------------------------------------------------------------------
if ($p->req('data')) {
    // TODO: prepare data

    $role = $p->req('role');

    $stm = $db->prepare(
        "SELECT IF(`status` = '1', 'active', 'inactive'), COUNT(*)
         FROM user
         WHERE `role` = ? OR ? = 2
         GROUP BY `status`"
    );
    $stm->execute([$role, $role]);
    $data = $stm->fetchAll(PDO::FETCH_NUM);

    // output json
    ob_clean();
    header('content-type: application/json');
    echo json_encode($data, JSON_NUMERIC_CHECK);
    exit();
}

// page data --------------------------------------------------------------------------------------

// TODO: update
$role = $db->query(
    "SELECT DISTINCT `role`, CONCAT('Role : ', `role`)
     FROM user
     ORDER BY `role`"
)->fetchAll(PDO::FETCH_KEY_PAIR);

$role = [2 => 'All'] + $role;

// $role = '';

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<style>
    #chart {
        width: 400px; height: 300px;
    }
</style>

<form>
    <!-- TODO: update -->
    <?= $h->select('role', null, $role, false, " onchange='$(this).submit()' ") ?>
    <button id="download">Download</button>
</form>

<br>

<div id="chart"></div>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(init);

    let dt, opt, cht;

    function init() {
        dt = new google.visualization.DataTable();
        dt.addColumn('string', 'Status');
        dt.addColumn('number', 'Count');

        opt = {
            title: 'Status of Users in Percentage(%)',
            fontName: 'Calibri',
            fontSize: 14,
            titleTextStyle: { fontSize: 20 },
            chartArea: {
                width: '90%',
                height: '90%',
                top: 50,
                left: 50,
            },
        };

        cht = new google.visualization.PieChart($('#chart')[0]);

        // TODO: remove
        $('form').submit();
    }

    // TODO: form submit event
    $('form').on('submit', function (e) {
        e.preventDefault();

        let param = $(this).serialize();
        $.getJSON('?data=1', param, function (data) {
            dt.removeRows(0, dt.getNumberOfRows());
            dt.addRows(data);
            cht.draw(dt, opt);
        });
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