<thead>
    <tr>
        {foreach $_grid->getColumns() as $column}
            <th>
                <div class="form-group">
                    {$column->renderLabel()}
                    {$column->renderFilter()}
                </div>
            </th>
        {/foreach}
    </tr>
</thead>