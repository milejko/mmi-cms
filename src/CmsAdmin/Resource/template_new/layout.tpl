{'cmsAdmin/header'}
{if $auth}
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<header class="app-header navbar">

    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">☰</button>
</header>
<div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.html"><i class="icon-speedometer"></i> Dashboard <span class="badge badge-primary">NEW</span></a>
                </li>

            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    <main class="main">

    </main>
    <footer class="app-footer">
        <a href="https://github.com/milejko/mmi-cms">{$domain}</a> &copy; {system_date('Y')}.
        <span class="float-right">Powered by <a href="https://github.com/milejko/mmi-cms">MMi CMS</a>
        </span>
    </footer>
</div>
{/if}
{if !$auth}
<body class="app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card p-4">
                    <div class="card-body">
                        <h1>Zaloguj się</h1>
                        <p class="text-muted"><span>{$domain|replace:'www.':''}</span></p>
                        <div class="input-group mb-3">
                                <span class="input-group-addon"><i class="icon-user"></i>
                                </span>
                            <input type="text" class="form-control" placeholder="nazwa użytkownika">
                        </div>
                        <div class="input-group mb-4">
                                <span class="input-group-addon"><i class="icon-lock"></i>
                                </span>
                            <input type="password" class="form-control" placeholder="hasło">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary px-4">zaloguj się</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
{'cmsAdmin/footer'}