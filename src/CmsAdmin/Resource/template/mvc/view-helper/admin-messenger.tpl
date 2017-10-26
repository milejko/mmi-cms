<ul id="messenger" class="messenger">
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
        {/if}
        <li class="notice">
            <i class="fa fa-2 {$icon}"></i>
            <div class="alert {$class}" role="alert">
                dupa
                {$_messenger->prepareTranslatedMessage($message)}
                <a class="close-alert" href="#"></a>
            </div>
        </li>
    {/foreach}
</ul>
