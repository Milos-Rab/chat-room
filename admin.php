<?php
    session_start();
    include 'mysql.php';
    include 'mongodb.php';

    $page = "index/login";

    include './template/header.php';
?>

<div class="container-login100">
    <div class="wrap-login100 p-t-35 p-b-20">
        <form class="login100-form validate-form" method="post" action="./admin.php">
            <h3 class="m-t-20 m-b-40" style="text-align:center;">Welcome to Chat Room</h3>
            <div class="wrap-input100 validate-input m-t-15 m-b-35" data-validate="Enter user ID">
                <input class="input100" type="text" name="username">
                <span class="focus-input100" data-placeholder="User ID"></span>
            </div>
            <div class="wrap-input100 validate-input m-b-50" data-validate="Enter Password">
                <input class="input100" type="password" min="1" max="150" name="password">
                <span class="focus-input100" data-placeholder="Password"></span>
            </div>
            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>

<?php
    include './template/footer.php';
?>
