<head>
    <link href="/resources/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/resources/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/resources/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <script src="/resources/vendor/jquery/jquery.min.js"></script>
    <script src="/resources/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="/resources/vendor/bootstrap/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="login-page"> 
<style>
    .vertical-center {
        min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
        min-height: 100vh; /* These two lines are counted as one :-)       */

        display: flex;
        align-items: center;
    }
</style>
<main class="vertical-center">
    <div class="login-block offset-lg-4 col-lg-4 offset-md-3 col-md-6 col-sm-12 ">
        <?php if(!empty($error_messages)):?>
            <div class="alert alert-danger"><?=$error_messages?></div>
        <?php endif;?>
        <form action="/login/check_login" method="post">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user ti-user"></i></span>
                    <input type="text" class="form-control" placeholder="логин" name="user_name">
                </div>
            </div>

            <hr class="hr-xs">

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock ti-unlock"></i></span>
                    <input type="password" class="form-control" placeholder="пароль" name="user_password">
                </div>
            </div>

            <button class="btn btn-primary btn-block" type="submit">ВОЙТИ</button>

        </form>
    </div>
<!--
    <div class="login-links">
        <p class="text-center"><a class="txt-brand" href="user-login.html"><font color=#29aafe>Регистрация</font></a></p>
    </div> -->

</main>
</body>