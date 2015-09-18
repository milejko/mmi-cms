{if ($loginForm)}
	{$loginForm}
{else}
	<a href="{url(['controller' =>'login', 'action' => 'logout'], 'pl', true)}">logout</a>
{/if}