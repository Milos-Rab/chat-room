<?php
    include './template/header.php';
?>

<div class="container-login100">
    <div class="wrap-login100 p-t-35 p-b-20">
        <form class="login100-form validate-form">
            <h2 class="m-t-20 m-b-20" style="text-align:center;">Log in</h2>
            <div class="wrap-input100 validate-input m-t-15 m-b-35" data-validate="Enter username">
                <input class="input100" type="text" name="username">
                <span class="focus-input100" data-placeholder="User Name"></span>
            </div>
            <div class="wrap-input100 validate-input m-b-50" data-validate="Enter Age">
                <input class="input100" type="number" min="1" max="150" name="pass">
                <span class="focus-input100" data-placeholder="Age"></span>
            </div>
            <div class="wrap-input100 m-b-50" style="border:none;">
                <label class="radio-inline"><input type="radio" name="optradio" checked>Male</label>
                <label class="radio-inline"><input type="radio" name="optradio">Female</label>
            </div>
            <div class="container-login100-form-btn">
            <button class="login100-form-btn">Login</button>
            </div>
        </form>
    </div>
</div>

<?php
    include './template/footer.php';
?>

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