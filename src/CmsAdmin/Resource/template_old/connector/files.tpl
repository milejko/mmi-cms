<div class="content-box">
    <div class="content-box-header">
        <h3>{#Import plików#}</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content clearfix">
        {$form}
        {if $files}
            <ul class="auto-download">
            {foreach $files as $file}
                <li data-name="{$file.name}"><span>0</span>% {$file.object}/{$file.objectId}/{$file.original}</li>
            {/foreach}
            </ul>
        {/if}
    </div>
</div>
<script>
    $(document).ready(function () {
        $('ul.auto-download > li').each(function () {
            var obj = $(this);
            $.get(request.baseUrl + '/?module=cms&controller=connector&action=importFile&name=' + obj.attr('data-name')).always(function () {
                obj.children('span').html('100');
            });
        });
    });
</script>