{if ($loginForm)}
    {$loginForm}
{else}
    <a href="{url(['controller' =>'login', 'action' => 'logout'], 'pl', true)}">{#template.user.login.logout#}</a>
{/if}