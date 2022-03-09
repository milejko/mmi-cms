<div class="form-control-feedback ne-error-list" {if $_element->getId()}id="{$_element->getId()}-errors"{/if}>
    {$_errors = $_element->getErrors()}
    {if $_errors}
        <ul>
            {foreach $_errors as $_error}
                <li>
                    <i class="fa fa-times" aria-hidden="true"></i>
                    {if php_is_array($_error)}
                        {_($_error[0], $_error[1])}
                    {else}
                        {_($_error)}
                    {/if}
                </li>
            {/foreach}
        </ul>
    {/if}
</div>
