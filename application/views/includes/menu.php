<?php
$user_data = $this->session->userdata();
?>
<div class="col-lg-12 col-md-12 col-sm-12">
    <nav class="navbar navbar-expand-lg navbar-expand-md navbar-expand-sm navbar-dark bg-dark">
        <a class="navbar-brand text-white">Ресурс</a>        
        <div class="collapse-in navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="navbar-nav mr-auto">
                <?php if ($user_data['role_id'] == 1): ?>
                    <li class="nav-item"><a class="nav-link" href="/user/show_users">Пользователи</a></li>
                    <li class="nav-item"><a class="nav-link" href="/objects">Объекты</a></li>
                    <li class="nav-item">
                        <a href="/work/show_work_table" class="nav-link">Таблица<span class="caret"></span></a>
                        <!--  <div class="dropdown-menu" role="menu"></div>-->
                    </li>
                    <!--
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Статистика<span class="caret"></span></a>
                        <div class="dropdown-menu" role="menu"></div>
                    </li>
                    -->
                    <!--<li class="nav-item"><a class="nav-link" href="#"></a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                        <div class="dropdown-menu" role="menu"></div>
                    </li>-->
                <?php endif; ?>

            </ul>
        </div>
        <a class="btn btn-danger float-right" href="/login/logout">x</a>
    </nav>
</div>    

