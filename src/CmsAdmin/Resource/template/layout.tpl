{'cmsAdmin/header'}
{adminMessenger()}
{if !$auth}
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
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAABGdBTUEAALGPC/xhBQAAAwBQTFRFISsxIiwyIy0zJC40JS81JzE3KDE3KDI4KTM5KzU6KzU7LTc8LTc9MDk/MDo/MjtAMjtBND1CND1DNT5DNT5EN0BGOEFGOUJHOkNIPEVKPUZLPkdMR09USFBVSFFWTFRZTVVaUVleU1tfVFxhVl1iV15jWF9kWGBkWWFlWmJmW2JnX2Zqa3F1a3J2bHJ2bnV5cXh8c3l9c3p9e4GEfIKFfIKGfoOHfoSHg4iMhImNhIqNhYqOhouPiI6RiY6SjZKVjpOWkJWYkZaZlpqdlpuemJyfm6CjnaGkoKSnoaaopKmrpqqsqq6xr7O1sLO2tLe5tLi6t7q8t7u9u7/AvL/BvsHDwMPEwMPFwcTFwsXGwsXHw8bHxcfJx8nLycvNys3O2Nrb2tzd293e3N3e3d7f3t/g4OLi4eLj4uPk7Ozt7O3u7e7u7u/v7u/w8fLy8vPz9PX19vf39/j4+Pj4+Pj5+fn6+vr7+/z8/Pz8/f39/v7+////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAmG1rogAAAAlwSFlzAAAOwgAADsIBFShKgAAAABl0RVh0U29mdHdhcmUAcGFpbnQubmV0IDQuMC4xNzNun2MAAAIwSURBVFhH7dfpWxJRGMZhRSoqzKzMLRJLzTJTUssg13K3tBojt3JpUVPUsojpn48ZfxLnMMN5qY91f5o5z/sMczHXdRjK/jMINUQiDSFOSnZtbO3Hz6z02th1lkpR99J22sfsF3Usi7Xs0sVuC4FQ0wHFnIMmIpFT76nl+XCaUOIRJUWcUCDwiY7iYzmxWZSKJkps1k9D009sNkNDM0NstkBDs0Bs5nOBJLHZX9/BPA3NPLHZBA3NBLFZDw1ND7HZDRqam8Rmoe9UFN/OEAu8paN4RygxSEcxSCjRmLedncg0EookaeV5TSRzh9Zv9m0iIYtejkUgVbtHEXu1BGLtR1RdX9tZLkHbDuWsnVYWpQLO9lc9fXhcP5yuzp5GA24kUfVw0044B+HOp7OzT+6GnePH9uaDKufA5Nz9N+5P6rNKFlyVz521dDJ2lgU/FQMpZ9KxHc9d4kIi932kBipY9BRUHv/R0kisoyM2sqQ8ECvIsJdRhooaZdjDVeWj/HypYbzQMCMGw4wX2mDCYIPxAvUMGNVT0HWRG3VR0I2TG41T0K2SG61S0H0mN9qmoLlEbGZfpKJqJRbw3h66SQW6qaiGSAWGqKimSAWmqKh8Xiu8eL9qLJIKLFJRrZAKrFBRJUgF3C23QPm95QwDRaWXO2kUqumz9hnzsf+q139DcgWbE3NbnneS2ZqLNxfbUfOEb/VNWusprpNJrVuTvW3nCUsQuhLJuvzH//v+DWVlvwACf8mpO20WxgAAAABJRU5ErkJggg==" class="img-avatar" alt="">
                        <span class="d-md-down-none">{$auth->getUsername()}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>{$auth->getUsername()}</strong>
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