<?php
include '_base.php';
$p->title = 'waiting page';

include '_header.php';
?>
<p class="info"><?= $p->temp('info') ?></p>
<p>you will be waiting more 30 seconds if u click to login page before 30 seconds!😁</p>
<?php
include '_footer.php';