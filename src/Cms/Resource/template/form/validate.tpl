{if !$errors|isEmpty}
    <div>
        <span class="marker"></span>
        <ul style="list-style: none;">
            <li class="point first"></li>
                {foreach $errors as $error}
                <li class="notice error"><i class="fa fa-times" aria-hidden="true"></i>{_($error)}</li>
                {/foreach}
            <li class="close last"></li>
        </ul>
    </div>
{/if}