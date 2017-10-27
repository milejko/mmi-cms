{'cmsAdmin/header'}
{if !$auth}
    <body class="app flex-row align-items-center">
    {else}
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">☰</button>
            <ul class="nav navbar-nav ml-auto"></ul>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="d-md-down-none">{$auth->getUsername()}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>{$auth->getName()}</strong>
                        </div>
                        <a class="dropdown-item" href="{@module=cmsAdmin@}"><i class="fa fa-user"></i> Profil</a>
                        <a class="dropdown-item" href="{@module=cmsAdmin&controller=index&action=password@}"><i class="fa fa-unlock-alt"></i> Zmiana hasła</a>
                        <a class="dropdown-item" href="{@module=cmsAdmin&controller=index&action=logout@}"><i class="fa fa-lock"></i> Wyloguj</a>
                    </div>
                </li>
            </ul>
        </header>
    {/if}
    <div class="app-body">
        {adminMessenger()}
        {if $auth}
            <div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        {adminNavigation()->setRoot('admin-menu')->menu()}
                    </ul>
                </nav>
                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>
        {/if}
        <main class="main">
            {if $auth}
                <ol class="breadcrumb">
                    {adminNavigation()->breadcrumbs()}
                </ol>
            {/if}
            {content()}
        </main>
    </div>
    {if $auth}
        <footer class="app-footer">
            <a href="https://github.com/milejko/mmi-cms">{$domain}</a> &copy; {system_date('Y')}
            <span class="float-right">Powered by
                <a href="https://github.com/milejko/mmi-cms">MMi CMS</a>
            </span>
        </footer>
    {/if}
    {'cmsAdmin/footer'}