<tr>
    <th class="paginator" colspan="{php_count($_grid->getColumns())}">
        Znaleziono: <strong>{$_grid->getState()->getDataCount()}</strong> pozycji, strona:
        <div class="field select">
            <select name="{$_grid->getClass()}[_paginator_]" class="field select">
                {foreach $_paginator->getPages() as $page}
                    <option value="{$page}"{if $page == $_grid->getState()->getPage()} selected{/if}>{$page}</option>
                {/foreach}
            </select>
            <div class="clear"></div>
        </div>
        z {$_paginator->getPagesCount()}
        <a target="_blank" href="{$_paginator->getExportCsvUrl()}">export csv</a>
    </th>
</tr>