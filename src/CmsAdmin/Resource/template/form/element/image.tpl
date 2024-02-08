{$value = $_element->getValue()}
{$file = $_element->getUploadedFile($value)}
<div>
    <div class="pull-left mr-2">
        <img style="width: 90px; height: 90px; background: #eee; border-radius: 5px;" id="{$_element->getId()}_img"
        {if $file}
        src="{thumb($file, 'scalecrop', '90x90')}"
        {else}
        src="/resource/cmsAdmin/images/placeholder.png"
        {/if}>
    </div>
    <div class="mb-2">
        <input accept="image/jpeg,image/gif,image/png" type="file" onchange="
            document.getElementById('{$_element->getId()}_img').src = '/resource/cmsAdmin/images/placeholder.png';
            if (undefined !== this.files[0]) {
                document.getElementById('{$_element->getId()}_name').value = '';
                document.getElementById('{$_element->getId()}_img').src = window.URL.createObjectURL(this.files[0]);
                document.getElementById('{$_element->getId()}_size').innerHTML = (this.files[0].size / 1048576).toFixed(2) + ' MB';
                document.getElementById('{$_element->getId()}_checkbox').checked = false;
                document.getElementById('{$_element->getId()}-errors').innerHTML = '';
            }" {$_htmlOptions} />
        <input type="hidden" id="{$_element->getId()}_name" name="{$_element->getName()}" value="{$value|input}" />
    </div>
    <div>
        <label>{#form.element.image.size.label#}: <span id="{$_element->getId()}_size">{if $file}{php_round($file->size / 1048576, 2)} MB{else}({#form.element.image.missing.label#}){/if}</span></label><br/>
        <button onclick="
            document.getElementById('{$_element->getId()}').value = '';
            document.getElementById('{$_element->getId()}_name').value = '';
            document.getElementById('{$_element->getId()}_img').src = '/resource/cmsAdmin/images/placeholder.png';
            document.getElementById('{$_element->getId()}_size').innerHTML = '(brak pliku)';
            return false;
        ">{#form.element.image.delete.label#}</button>
    </div>
    <div class="clearfix"></div>
</div>
