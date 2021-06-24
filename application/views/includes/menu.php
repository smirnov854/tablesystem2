<?php
$user_data = $this->session->userdata();
?>
<div>
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
            <a class="btn btn-danger" href="/login/logout">x</a>
        </div>
    </nav>
</div>    

