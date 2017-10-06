{if $languages}
    <div class="languages">
        {foreach $languages as $language}
            <a{if $request->lang == $language} class="active"{/if} title="{$language}" href="{@module=cms&controller=admin&action=language&locale={$language}@}">{$language|uppercase}</a>
        {/foreach}
    </div>
{/if}