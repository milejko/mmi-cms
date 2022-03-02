{headScript()->appendFile('/resource/cmsAdmin/js/select-color.js')}
{headLink()->appendStylesheet('/resource/cmsAdmin/css/select-color.css')}
<div class="select-color" data-for-id="{$_element->getId()}">
    {$i = 0}
    {foreach $_element->getMultioptions() as $key => $option}
    <div class="color{if $key == $_element->getValue()} selected{/if}" data-color-class="{$key}">
        <div class="color-label" {if $key}style="background: {$option}"{/if}>
        {if !$key}{#form.selectColor.empty.label#}{else}{$i++}{$i}{/if}
        </div>
    </div>
    {/foreach}
</div>
<input type="hidden" {$_htmlOptions} value="{$_element->getValue()}">
