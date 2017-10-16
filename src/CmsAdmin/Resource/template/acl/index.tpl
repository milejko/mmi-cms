
{headScript()->appendFile('/resource/cmsAdmin/js/acl.js')}


<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Uprawnienia ról#}</strong>
                    </div>
                    <div class="card-body">

                            {foreach name="roles" $roles as $role}
                                <button class="btn btn-outline-primary m-2"{if $request->roleId && $request->roleId == $role->id}class="current"{$chosenRole = $role}{/if}>
                                    <a href="{@module=cmsAdmin&controller=acl&action=index&roleId={$role->id}@}">{$role->name}</a>
                                </button>
                            {/foreach}

                        <div id="rules">
                            {if $request->roleId}
                                <table class="table table-striped">
                                    <tr>
                                        <th>{#Lp#}.</th>
                                        <th>{#zasób#}</th>
                                        <th>{#polityka#}</th>
                                        <th>{#operacje#}</th>
                                    </tr>
                                    {$i=1}

                                    {foreach $rules as $rule}
                                        <tr id="rule-row-{$rule->id}">
                                            <td>
                                                {$i}{$i++}
                                            </td>
                                            <td>
                                              <div class="form-group">
                                                  <select class="rule-select form-control" id="rule-resource-{$rule->id}">
                                                      {foreach $options as $key => $option}
                                                          <option value="{php_strtolower($key)}" {if php_strtolower($key) == $rule->getMvcParams()}selected=""{/if}>{$option}</option>
                                                      {/foreach}
                                                  </select>
                                              </div>
                                            </td>
                                            <td>
                                              <div class="form-group">
                                                  <select class="rule-select form-control policy {if ($rule->access == 'allow')}allow{else}deny{/if}" id="rule-policy-{$rule->id}">
                                                      <option value="allow"{if 'allow' == $rule->access} selected=""{/if}>{#dozwolone#}</option>
                                                      <option value="deny"{if 'allow' != $rule->access} selected=""{/if}>{#zabronione#}</option>
                                                  </select>
                                              </div>
                                            </td>
                                            <td>
                                                <a id="rule-remove-{$rule->id}" class="remove-rule confirm" title="{#Czy na pewno chcesz usunąć tę regułę#}" href="#"><i class="icon-remove-circle"></i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                                {if $chosenRole && $chosenRole->name != 'admin' && $chosenRole->name != 'guest'}
                                    <br /><a class="button confirm" title="Usunąć rolę" href="{@module=cmsAdmin&controller=acl&action=deleteRole&id={$request->roleId}@}"><i class="icon-trash"></i> usuń rolę</a>
                                {/if}
                                <br /> <br />
                                <h5>{#Nowa reguła#}:</h5>
                                {$aclForm}
                            {/if}
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                          <h5>{#Nowa rola#}</h5>
                          {$roleForm}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>
