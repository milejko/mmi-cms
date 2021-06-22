{'cmsAdmin/header'}
{adminMessenger()}
{if !$auth->hasIdentity()}
    <body class="app flex-row align-items-center login-screen-bg">
    {else}
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">☰</button>
            <ul class="nav navbar-nav ml-auto"></ul>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="header-url nav-link" href="#">{$domain}</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto"></ul>
            <ul class="nav navbar-nav d-md-down-none mr-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-user"></i>
                        <span class="d-md-down-none">{$auth->getUsername()}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>{$auth->getUsername()}</strong>
                        </div>
                        <a class="dropdown-item" href="{@module=cmsAdmin@}"><i class="fa fa-user"></i> {#template.layout.menu.profile#}</a>
                        <a class="dropdown-item" href="{@module=cmsAdmin&controller=index&action=password@}"><i class="fa fa-unlock-alt"></i> {#template.layout.menu.password#}</a>
                        <a class="dropdown-item" href="{@module=cmsAdmin&controller=index&action=logout@}"><i class="fa fa-lock"></i> {#template.layout.menu.logout#}</a>
                    </div>
                </li>
            </ul>
        </header>
    {/if}
    <div class="app-body">
        {if $auth->hasIdentity()}
            <div class="sidebar">
                {widget('cmsAdmin', 'index', 'scopeMenu')}
                <nav class="sidebar-nav">
                    <ul class="nav">
                        {adminNavigation()->setRoot('admin-menu')->menu()}
                    </ul>
                </nav>
                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>
        {/if}
        <main class="main">
            {if $auth->hasIdentity()}
                <ol class="breadcrumb">
                    {adminNavigation()->breadcrumbs()}
                </ol>
            {/if}
            {content()}
        </main>
    </div>
    {if $auth->hasIdentity()}
        <footer class="app-footer">
            <a href="{$baseUrl}/">{$domain}</a> &copy; {system_date('Y')}
            <span class="float-right">Powered by
                <a href="https://github.com/milejko/mmi-cms">MMi CMS</a>
            </span>
        </footer>
    {/if}
    {'cmsAdmin/footer'}