<div >
    <div class="pull-left mr-2">
        <img style="width: 90px; height: 90px; background: #eee; border-radius: 5px;" id="{$_element->getId()}_img" 
        {if $_element->getUploadedFile()}
        src="{thumb($_element->getUploadedFile(), 'scalecrop', '90x90')}"
        {else}
        
        {/if}>
    </div>
    <div class="mb-2">
        <input accept="image/jpeg,image/gif,image/png" type="file" onchange="
            document.getElementById('{$_element->getId()}_img').src='/resource/topicAdmin/placeholder.jpg';
            if (undefined !== this.files[0]) {
                document.getElementById('{$_element->getId()}_img').src = window.URL.createObjectURL(this.files[0]);
                document.getElementById('{$_element->getId()}_size').innerHTML = (this.files[0].size / 1048576).toFixed(2) + ' MB';
                document.getElementById('{$_element->getId()}-errors').innerHTML = '';
            }" {$_htmlOptions} />
    </div>
    <div>
        <label>{#form.element.image.size.label#}: <span id="{$_element->getId()}_size">{if $_element->getUploadedFile()}{php_round($_element->getUploadedFile()->size / 1048576, 2)} MB{else}({#form.element.image.missing.label#}){/if}</span></label><br />
        <input type="checkbox" id="{$_element->getId()}_checkbox" name="{$_element->getDeleteCheckboxName()}">
        <label for="{$_element->getId()}_checkbox">{#form.element.image.delete.label#}</label>
    </div>
    <div class="clearfix"></div>
</div>
