<ul id="messenger" class="messenger animation-show">
    {foreach $_messenger->getMessages() as $message}
        {$class = 'warning'}
        {$icon = 'warning-sign'}
        {if $message.type}
            {* message type przyjmuje wartości error, notice i warning *}
            {$class = $message['type']}
            {$icon = ($message['type'] == 'error') ? 'remove-sign' : 'ok'}
            {if $message.type == 'error'}
                {$class = 'alert alert-danger'}
                {$icon = 'fa-times'}
            {/if}
            {if $message.type == 'warning'}
                {$class = 'alert alert-warning'}
                {$icon = 'fa-check'}
            {/if}
            {if $message.type == 'notice'}
                {$class = 'alert alert-info'}
                {$icon = 'fa-info'}
            {/if}
            {if $message.type == 'success'}
                {$class = 'alert alert-success'}
                {$icon = 'fa-info'}
            {/if}
        {/if}
        <li class="notice">
            <div class="alert {$class} {$message.type}" role="alert">
                <i class="fa {$icon}"></i>
                <span>{$_messenger->prepareTranslatedMessage($message)}</span>
                <a class="close-alert" href="#"></a>
            </div>
        </li>
    {/foreach}
</ul>
