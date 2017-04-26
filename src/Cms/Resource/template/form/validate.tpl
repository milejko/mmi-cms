{if !$errors|isEmpty}
    <div>
        <span class="marker"></span>
        <ul>
            <li class="point first"></li>
                {foreach $errors as $error}
                <li class="notice error"><i class="icon-remove-sign icon-large"></i>{$error}</li>
                {/foreach}
            <li class="close last"></li>
        </ul>
    </div>
{/if}