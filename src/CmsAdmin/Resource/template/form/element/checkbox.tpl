<label {if $_element->getId()}id="{$_element->getId()}-container"{/if} class="control control-checkbox">
    <span>{_($_element->getLabel())}</span>
    <input type="checkbox" {$_htmlOptions} />
    <div class="control_indicator"></div>
</label>
