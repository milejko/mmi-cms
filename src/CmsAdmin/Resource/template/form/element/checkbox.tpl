<label {if $_element->getId()}id="{$_element->getId()}-container"{/if} class="control control-checkbox">
    <span>{$_element->getLabel()}</span>
    <input type="checkbox" {$_multiOptions} />
    <div class="control_indicator"></div>
</label>
