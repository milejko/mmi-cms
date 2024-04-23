{$value = $_element->getValue()}
{$file = $_element->getUploadedFile($value)}

<div>
    <div class="pull-left mr-3">
        <img style="width: 100px; height: 100px; border: 1px solid #c2cfd6; background: #fff;" id="{$_element->getId()}-img"
        {if $file}
            {if 'image' == $file->class}
                src="{thumb($file, 'scalecrop', '100x100')}"
            {elseif $file->mimeType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file->mimeType == 'application/vnd.ms-excel'}
                src="/resource/cmsAdmin/images/upload/excel.svg" class="p-4" alt="Spreadsheet"
            {elseif $file->class == 'text' || $file->mimeType == 'application/msword' || $file->mimeType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'}
                src="/resource/cmsAdmin/images/upload/text.svg" class="p-4" alt="Document"
            {elseif $file->mimeType == 'application/vnd.ms-powerpoint' || $file->mimeType == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'}
                src="/resource/cmsAdmin/images/upload/powerpoint.svg" class="p-4" alt="Presentation"
            {elseif $file->mimeType == 'application/xml' || $file->mimeType == 'application/rtf' || $file->mimeType == 'application/pdf'}
                src="/resource/cmsAdmin/images/upload/application.svg" class="p-4" alt="Text"
            {elseif $file->class == 'audio'}
                src="/resource/cmsAdmin/images/upload/audio.svg" class="p-4" alt="Audio"
            {elseif $file->class == 'video'}
                src="/resource/cmsAdmin/images/upload/video.svg" class="p-4" alt="Video"
            {else}
                src="/resource/cmsAdmin/images/upload/file.svg" class="p-4" alt="Other file"
            {/if}
        {/if}
        {if !$file}
            src="/resource/cmsAdmin/images/arrow-up.png" class="p-4" 
        {/if}>
    </div>
    <div class="mb-2" style="height: 28px;">
        <input accept="{$_element->getAcceptMimeType()}" type="file" onchange="
            var imgElement = document.getElementById('{$_element->getId()}-img');
            imgElement.classList.add('p-4');
            imgElement.src = '/resource/cmsAdmin/images/arrow-up.png';
            if (undefined !== this.files[0]) {
                document.getElementById('{$_element->getId()}-name').value = '';
                if ('image' == this.files[0].type.substr(0, 5)) {
                    imgElement.classList.remove('p-4');
                    imgElement.src = window.URL.createObjectURL(this.files[0]);
                } else if ('text' == this.files[0].type.substr(0, 4) || 'application/msword' == this.files[0].type || 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' == this.files[0].type) {
                    imgElement.src = '/resource/cmsAdmin/images/upload/text.svg';
                } else if ('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' == this.files[0].type || 'application/vnd.ms-excel' == this.files[0].type) {
                    imgElement.src = '/resource/cmsAdmin/images/upload/excel.svg';
                } else if ('application/vnd.ms-powerpoint' == this.files[0].type || 'application/vnd.openxmlformats-officedocument.presentationml.presentation' == this.files[0].type) {
                    imgElement.src = '/resource/cmsAdmin/images/upload/powerpoint.svg';
                } else if ('application/xml' == this.files[0].type || 'application/rtf' == this.files[0].type || 'application/pdf' == this.files[0].type) {
                    imgElement.src = '/resource/cmsAdmin/images/upload/application.svg';
                } else if ('video' == this.files[0].type.substr(0, 5)) {
                    imgElement.src = '/resource/cmsAdmin/images/upload/video.svg';
                } else if ('audio' == this.files[0].type.substr(0, 5)) {
                    imgElement.src = '/resource/cmsAdmin/images/upload/audio.svg';
                } else {
                    imgElement.src = '/resource/cmsAdmin/images/upload/file.svg';
                }
                document.getElementById('{$_element->getId()}-size').innerHTML =  this.files[0].name + ' (' + (this.files[0].size / 1048576).toFixed(2) + ' MB)';
                document.getElementById('{$_element->getId()}-errors').innerHTML = '';
                document.getElementById('{$_element->getId()}-delete').classList.add('btn-danger');

            }" {$_htmlOptions} />
        <input type="hidden" id="{$_element->getId()}-name" name="{$_element->getName()}" value="{$value|input}" />
    </div>
    <div>
        <label><span id="{$_element->getId()}-size">{if $file}{$file->original} ({php_round($file->size / 1048576, 2)} MB){else}({#form.element.image.missing.label#}){/if}</span></label><br/>
        <button style="color: #fff;" id="{$_element->getId()}-delete" class="btn{if $file} btn-danger{/if}" onclick="
            document.getElementById('{$_element->getId()}').value = '';
            document.getElementById('{$_element->getId()}-name').value = '';
            document.getElementById('{$_element->getId()}-img').src = '/resource/cmsAdmin/images/arrow-up.png';
            document.getElementById('{$_element->getId()}-img').classList.add('p-4');
            document.getElementById('{$_element->getId()}-size').innerHTML = '({#form.element.image.missing.label#})';
            this.classList.remove('btn-danger');
            return false;
        ">{#form.element.image.delete.label#}</button>
    </div>
    <div class="clearfix"></div>
</div>
