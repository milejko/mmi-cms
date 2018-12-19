<div class="form-control-feedback ne-error-list" {if $_element->getId()}id="{$_element->getId()}-errors"{/if}>
    {$_errors = $_element->getErrors()}
    {if $_errors}
    <ul>
        {foreach $_errors as $_error}
        <li><i class="fa fa-times" aria-hidden="true"></i>{_($_error)}</li>
        {/foreach}
    </ul>
    {/if}
</div>