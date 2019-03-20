
{headScript()->appendFile('/resource/cmsAdmin/js/acl.js')}


<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.acl.index.header#}</strong>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                {foreach name="roles" $roles as $role}
                                <a class="btn btn-outline-primary m-1 {if $request->roleId && $request->roleId == $role->id} active{$chosenRole = $role}{/if}" href="{@module=cmsAdmin&controller=acl&action=index&roleId={$role->id}@}">{$role->name}</a>
                                {/foreach}
                            </div>
                        </div>
                         <div id="rules">
                            {if $request->roleId}
                                <table class="table table-striped">
                                    <tr>
                                        <th>{#template.acl.index.ordinal#}</th>
                                        <th>{#template.acl.index.resource#}</th>
                                        <th>{#template.acl.index.rule#}</th>
                                        <th>{#template.acl.index.actions#}</th>
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
                                                      <option value="allow"{if 'allow' == $rule->access} selected=""{/if}>{#template.acl.index.rule.allow#}</option>
                                                      <option value="deny"{if 'allow' != $rule->access} selected=""{/if}>{#template.acl.index.rule.deny#}</option>
                                                  </select>
                                              </div>
                                            </td>
                                            <td>
                                                <a id="rule-remove-{$rule->id}" class="remove-rule confirm" title="{#template.acl.index.delete.confirm#}" href="#"><i class="fa fa2 fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                                {if $chosenRole && $chosenRole->name != 'admin' && $chosenRole->name != 'guest'}
                                    <br /><a class="button confirm" title="{#template.acl.index.delete.role#}" href="{@module=cmsAdmin&controller=acl&action=deleteRole&id={$request->roleId}@}"><i class="fa fa2 fa-trash-o"></i> {#template.acl.index.delete.role#}</a>
                                {/if}
                                <br /> <br />
                                <h5>{#template.acl.index.rule.new#}:</h5>
                                {$aclForm}
                            {/if}
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                          <h5>{#template.acl.index.role.new#}</h5>
                          {$roleForm}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>
