{'cmsAdmin/header'}
{if !$auth}
<body class="app flex-row align-items-center">
{/if}

{if $auth}
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">☰</button>
    <ul class="nav navbar-nav ml-auto"></ul>
</header>
{/if}
<div class="app-body">
    {if $auth}
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                {navigation()->setRoot(1000000)->menu()}
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    {/if}
    <main class="main">
        {if $auth}
        <ol class="breadcrumb">
            {navigation()->breadcrumbs()}
        </ol>
        {/if}
        {content()}
    </main>
</div>
{if $auth}
<footer class="app-footer">
    <a href="https://github.com/milejko/mmi-cms">{$domain}</a> &copy; {system_date('Y')}.
    <span class="float-right">Powered by
        <a href="https://github.com/milejko/mmi-cms">MMi CMS</a>
    </span>
</footer>
{/if}
{'cmsAdmin/footer'}