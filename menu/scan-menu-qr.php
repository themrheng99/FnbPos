<?php

include '../_base.php';

$p->title = 'Scan QR';

include '../_header.php';

?>

<a href="../home.php" class="btn btn-outline-info">Back</a>
<br></br>
  
<div class="container">
    <h2>Scan Qr Code on Menu to Put Order</h2>
    <form method="POST" action="../order/decode-qr.php">
        <div>
            <div>
                <div id="my_camera"></div>
                <br/>
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div>
                <br/>
                <button onClick="take_snapshot()" class="btn btn-success">Click here to Scan</button>
            </div>
        </div>
    </form>
</div>
   
<!-- Configure a few settings and open device camera -->
<script language="JavaScript">
    Webcam.set({
        width: 490,
        height: 390,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
  
    Webcam.attach( '#my_camera' );
  
    // take snapshot using camera
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
        } );
    }
</script>
 
<?php include '../_footer.php';