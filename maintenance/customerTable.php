<?php
include '../_base.php';
$p->title = 'Customer data';

//$p->auth('Admin','Staff');

if($p->get){
    $stm = $db->query('SELECT * FROM customer WHERE NOT id = 1');
    $arr = $stm->fetchAll();
}

if($p->post){
    $username = $p->get('username');

    $stm = $db->prepare('SELECT * FROM customer WHERE username LIKE ? AND NOT id = ?');
    $stm->execute(["%$username%", 1]);
    $arr = $stm->fetchAll();
}

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<p class="info"><?= $p->temp('info') ?></p>

<input type="search" id="username" autofocus>
<button type="reset">Reset</button>
<div id="target">
<p><?= count($arr) ?> record(s)</p>

<!-- TODO(2): form and buttons -->

<table class="table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Role</th>
            <th>Edit status</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($arr as $user) {
                echo "
                    <tr data-row-check>
                        <td>$user->id</td>
                        <td>$user->username</td>
                        <td>$user->email</td>
                        <td>{$STATUS[$user->status]}</td>
                        <td>$user->role</td>
                        <td>
                            <button data-get='profileCustomerStatus.php?id=$user->id'>Status</button>
                            <button data-get='profileCustomer.php?id=$user->id'>Profile</button>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>
</div>

<script>
    let username;

    $('#username').on('input',function (e) {
        username = $(this).val().trim();
        let = param = $.param({username});
        let url = `/customerTable.php?${param} #target`;
        $('#target').load(url, highlight);
    });

    function highlight() {
        if(!username) return;

        let re = new RegExp(escapeRegExp(username), 'gi');

        $('td:nth-child(2)').each(function() {
            let h = $(this).html().replace(re, '<mark>$&</mark>');
            $(this).html(h);
        })
    }
</script>

<?php
include '../_footer.php';