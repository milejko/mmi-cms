<label {if $_element->getId()}id="{$_element->getId()}-container"{/if} class="control control-checkbox">
    <input type="checkbox" {$_multiOptions} />
    <div class="control_indicator"></div>
    <span>{$_element->getLabel()}</span>
</label>
