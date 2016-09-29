{'cmsAdmin/header'}
<nav class="navbar">
	<ul>
		<li>
			<a href="{@module=cmsAdmin&controller=index&action=index@}"><span>{$domain|replace:'www.':''}</span></a>
		</li>
		{if $auth && $acl->isAllowed($auth->getRoles(), 'cms:admin:password')}
			<li>
				<a href="{@module=cmsAdmin&controller=index&action=logout@}">{#Wyloguj#}<span>{if $auth} {$auth->getUsername()}</span> ({foreach name=role $auth->getRoles() as $role}{$role}{if !$_roleLast}, {/if}{/foreach}){/if}</a>
			</li>
		{/if}
	</ul>
</nav>
{if $auth}
	<div class="breadcrumbs">
		{navigation()->breadcrumbs()}
		{widget('cmsAdmin', 'index', 'languageWidget')}
	</div>
{/if}
<nav id="main-menu">
	{navigation()->setRoot(1000000)->menu()}
</nav>
<div class="grid">
	{messenger()}
	<nav class="local">
		{$currentDepth = navigation()->getCurrentDepth()}
		{navigation()->setRoot(1000000)->setActiveBranchOnly()->setMinDepth($currentDepth)->setMaxDepth($currentDepth)->menu()}
	</nav>
	{content()}
</div>
<div id="footer">
	{$domain} &copy; {system_date('Y')}. Powered by MMi CMS
</div>
{'cmsAdmin/footer'}