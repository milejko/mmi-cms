<h3>{#Komentarze#}</h3>
{if $commentForm}
    {$commentForm}
{else}
    {#Musisz być zalogowany, by dodawać komentarze#}.
{/if}
<ul class="comments">
    {foreach $comments as $entry}
        <li>
            {if $entry->title}
                <h4>{$entry->title}</h4>
            {/if}
            {if $entry->stars}
                <div class="stars-display">
                    <p style="width:{$entry->stars}px"></p>
                </div>
            {/if}
            <p>
                {$entry->text}
            </p>
            <span>{#Dodano#}: {$entry->dateAdd}, {#Autor#}: {$entry->signature}{if $entry->ip}, {#Adres IP#}: {$entry->ip|truncate:5:''}.*{/if}</span>
        </li>
    {/foreach}
</ul>