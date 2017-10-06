<div class="content-box">
    <div class="content-box-header">
        <h3>{#Import plików#}</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content clearfix">
        {$form}
        {if $files}
            <ul class="auto-download" data-url="{$downloadUrl}">
            {foreach $files as $name => $userName}
                <li data-name="{$name}"><span>0</span>% - {$userName}</li>
            {/foreach}
            </ul>
        {/if}
    </div>
</div>
<script>
    $(document).ready(function () {
        $('ul.auto-download > li').each(function () {
            var obj = $(this);
            $.get(request.baseUrl + '/?module=cms&controller=connector&action=importFile&name=' + obj.attr('data-name') + '&url=' + obj.parent('ul').attr('data-url')).always(function () {
                obj.children('span').html('100');
            });
        });
    });
</script>