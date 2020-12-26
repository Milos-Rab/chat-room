
        </div>

<script src="./assets/js/jquery-3.2.1.min.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<?php
    if($page==='index/login'){
?>
<script src="./assets/js/loginformvalidate.js"></script>
<?php 
    }else if($page==='chatroom'){
?>
    <script src="./assets/js/toastr.min.js"></script>
    <script src="./assets/js/chatroom.js"></script>
<?php
    }else{
?>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="./assets/js/admin.js"></script>
<?php
    }
?>
<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

      gtag('config', 'UA-23581568-13');
      
	</script>
</body>
</html>

<?php

// if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//     $ip = $_SERVER['HTTP_CLIENT_IP'];
// } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
// } else {
//     $ip = $_SERVER['REMOTE_ADDR'];
// }
// echo $ip;

?>