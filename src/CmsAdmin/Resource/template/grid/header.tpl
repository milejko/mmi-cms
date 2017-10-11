<tr>
    {foreach $_grid->getColumns() as $column}
        <th>
            {$column->renderLabel()}<br />
            {$column->renderFilter()}
        </th>
    {/foreach}
</tr>