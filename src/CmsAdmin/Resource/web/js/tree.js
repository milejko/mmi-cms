/**
 * Obsługa drzewka stron CMS
 */

var request = request || {};
//konfiguracja
var CATEGORYCONF = CATEGORYCONF || {};
//klucz do stanu drzewka
CATEGORYCONF.stateKey = 'cms-category-jstree';
//po jakim czasie ukryć message
CATEGORYCONF.msgDelay = 2500;
//czy przeładować
CATEGORYCONF.reload = false;
//czy otwarto menu kontekstowe
CATEGORYCONF.contextMenu = false;
//translate
CATEGORYCONF.languages = {
    'en': {
        'loading': 'Loading...',
        'new': 'New page',
        'create': 'Create subpage',
        'create.error': 'Unable to create the page',
        'delete': 'Delete',
        'delete_confirm': 'Really delete a whole page? This operation cannot be undone!',
        'delete_error': 'Unable to delete the page',
        'copy': 'Copy',
        'copy_error': 'Unable to copy the page',
        'preview': 'Preview',
        'edit': 'Edit',
        'rename_error': 'Unable to rename the page',
        'move_error': 'Unable to move the page'
    },
    'pl': {
        'loading': 'Ładowanie...',
        'new': 'Nowa strona',
        'create': 'Utwórz podstronę',
        'create.error': 'Nie udało się utworzyć strony',
        'delete': 'Usuń',
        'delete_confirm': 'Na pewno usunąć stronę? Ta operacja jest nieodwracalna!',
        'delete_error': 'Nie udało się usunąć strony',
        'copy': 'Kopiuj',
        'copy_error': 'Nie udało się skopiować strony',
        'preview': 'Podgląd',
        'edit': 'Edytuj',
        'rename_error': 'Nie udało się zmienić nazwy strony',
        'move_error': 'Nie udało się przenieść strony'
    }
};
CATEGORYCONF.translate = {};

//zarządzanie stronami
$(document).ready(function () {
    //ładowanie tłumaczenia
    CATEGORYCONF.translate = (undefined != CATEGORYCONF.languages[request.locale]) ? CATEGORYCONF.languages[request.locale] : CATEGORYCONF.languages.en;
    //przeniesienie messengera
    $('ul#messenger').appendTo('#categoryMessageContainer').show();

    //odpalenie drzewka
    $('#jstree').jstree({
        'core': {
            'data': {
                'url': $('#jstree').attr('data-url'),
                'data': function (node) {
                    return { 'parentId': node.id };
                }
            },
            'themes': {
                'name': 'default',
                'variant': 'small',
                'responsive': false,
                'stripes': true
            },
            'strings': {
                'New node': CATEGORYCONF.translate.new,
                'Loading ...': CATEGORYCONF.translate.loading
            },
            'multiple': false,
            'expand_selected_onload': true,
            'force_text': true,
            'check_callback': function (op) {
                if (op === 'delete_node') {
                    return confirm(CATEGORYCONF.translate.delete_confirm);
                }
                return true;
            }
        },
        'state': {
            'key': CATEGORYCONF.stateKey,
            'filter': function (state) {
                if (request.originalId) {
                    state.core.selected = [request.originalId];
                }
                return state;
            }
        },
        'unique': {
            'duplicate': function (name, counter) {
                return name + ' ' + counter;
            }
        },
        'types': {
            '#': { 'valid_children': ["root"] },
            'root': {
                'valid_children': ["default", "leaf"],
                'icon': '/resource/cmsAdmin/images/tree.png'
            },
            'default': { 'valid_children': ["default", "leaf"] },
            'leaf': { 'valid_children': ["default", "leaf"] }
        },
        'contextmenu': {
            'items': function (node) {
                if (node.state.disabled) {
                    return;
                }
                CATEGORYCONF.contextMenu = true;
                var tmp = $.jstree.defaults.contextmenu.items();
                var menu = {};
                delete tmp.ccp;
                delete tmp.rename;
                if (this.get_type(node) !== "root") {
                    menu.preview = {
                        "separator_before": false,
                        "separator_after": false,
                        "label": CATEGORYCONF.translate.preview,
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var node = inst.get_node(data.reference);
                            CATEGORYCONF.loadPreviewUrl(node.id);

                        }
                    };
                };
                tmp.create.label = CATEGORYCONF.translate.create;
                //kopia artykułu z menu kontekstowego
                tmp.copy = {
                    "label": CATEGORYCONF.translate.copy,
                    "action": function (data) {
                        var inst = $.jstree.reference(data.reference);
                        var node = inst.get_node(data.reference);
                        CATEGORYCONF.hideMessage();
                        $.post('/cmsAdmin/category/copy', { 'id': node.id })
                            .done(function (d) {
                                if (d.status) {
                                    CATEGORYCONF.reload = true;
                                    inst.set_id(node, d.id);
                                    $('#jstree').jstree('deselect_all');
                                    $('#jstree').jstree('select_node', d.id);
                                }
                                inst.refresh();
                                CATEGORYCONF.showMessage(d);
                            })
                            .fail(function () {
                                inst.refresh();
                                CATEGORYCONF.showMessage({ 'error': CATEGORYCONF.translate.copy_error });
                            });
                    }
                };
                if (this.get_type(node) === "root") {
                    tmp.create.label = CATEGORYCONF.translate.create;
                    tmp.create.separator_after = false;
                } else {
                    tmp.edit = {
                        "separator_before": true,
                        "separator_after": false,
                        "label": CATEGORYCONF.translate.edit,
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var node = inst.get_node(data.reference);
                            CATEGORYCONF.loadEditUrl(node.id);
                        }
                    };
                }
                menu.edit = tmp.edit;
                menu.create = tmp.create;
                if (this.get_type(node) !== "root") {
                    menu.copy = tmp.copy;
                }
                if (this.get_type(node) == "leaf") {
                    tmp.remove.label = CATEGORYCONF.translate.delete;
                    menu.remove = tmp.remove;
                }
                return menu;
            }
        },
        'plugins': ["state", "unique", "types", "contextmenu", "dnd", "wholerow"]
    })
        .on('delete_node.jstree', function (e, data) {
            CATEGORYCONF.hideMessage();
            $.post('/cmsAdmin/category/delete', { 'id': data.node.id })
                .done(function (d) {
                    if (d.status) {
                        CATEGORYCONF.reload = true;
                        $('#jstree').jstree('deselect_all');
                        $('#jstree').jstree('select_node', data.parent);
                    } else {
                        data.instance.refresh();
                    }
                    CATEGORYCONF.showMessage(d);
                })
                .fail(function () {
                    data.instance.refresh();
                    CATEGORYCONF.showMessage({ 'error': CATEGORYCONF.translate.delete_error });
                });
        })
        .on('create_node.jstree', function (e, data) {
            CATEGORYCONF.hideMessage();
            $.post('/cmsAdmin/category/create', { 'parentId': data.node.parent, 'order': data.position, 'name': data.node.text })
                .done(function (d) {
                    if (d.status) {
                        data.instance.set_id(data.node, d.id);
                        data.instance.set_icon(data.node, d.icon);
                        if (d.disabled) {
                            data.instance.disable_node(data.node);
                        }
                        $('#jstree').jstree('deselect_all');
                        $('#jstree').jstree('select_node', d.id);
                    } else {
                        data.instance.refresh();
                    }
                    CATEGORYCONF.showMessage(d);
                })
                .fail(function () {
                    data.instance.refresh();
                    CATEGORYCONF.showMessage({ 'error': CATEGORYCONF.translate.create_error });
                });
        })
        .on('rename_node.jstree', function (e, data) {
            CATEGORYCONF.hideMessage();
            $.post('/cmsAdmin/category/rename', { 'id': data.node.id, 'name': data.text })
                .done(function (d) {
                    if (d.status) {
                        CATEGORYCONF.reload = true;
                        data.node.text = d.name;
                        $('#jstree').jstree('deselect_all');
                        $('#jstree').jstree('select_node', d.id);
                    } else {
                        data.instance.refresh();
                    }
                    CATEGORYCONF.showMessage(d);
                })
                .fail(function () {
                    data.instance.refresh();
                    CATEGORYCONF.showMessage({ 'error': CATEGORYCONF.translate.rename_error });
                });
        })
        .on('move_node.jstree', function (e, data) {
            CATEGORYCONF.hideMessage();
            var params = { 'id': data.node.id, 'parentId': data.parent, 'oldParentId': data.old_parent, 'order': data.position, 'oldOrder': data.old_position };
            $.post('/cmsAdmin/category/move', params)
                .done(function (d) {
                    if (d.status) {
                        CATEGORYCONF.reload = true;
                        $('#jstree').jstree('deselect_all');
                        $('#jstree').jstree('select_node', d.id);
                    } else {
                        data.instance.refresh();
                    }
                    CATEGORYCONF.showMessage(d);
                })
                .fail(function () {
                    data.instance.refresh();
                    CATEGORYCONF.showMessage({ 'error': CATEGORYCONF.translate.move_error });
                });
        })
        .on('changed.jstree', function (e, data) {
            if (!data || !data.selected || !data.selected.length || !(0 in data.selected)) {
                return;
            }
            //jeśli nie jest to zaznaczenie, wychodzimy
            if (data.action !== "select_node") {
                return;
            }
            //jeśli aktualny url nie pochodzi z drzewka, wychodzimy
            if (window.location.search.indexOf("from=tree") === -1 && window.location.search.indexOf("id=") !== -1 && !CATEGORYCONF.reload) {
                if (parseFloat(request.originalId) === parseFloat(data.selected[0])) {
                    return;
                }
            }
        })
        .on('state_ready.jstree', function (e, data) {
            //jeśli aktualny url nie pochodzi z drzewka
            if (window.location.search.indexOf("from=tree") === -1) {
                resExp = window.location.search.match(/originalId=(\d+)/);
                if (resExp !== null && parseFloat(resExp[1])) {
                    $('#jstree').jstree('deselect_all');
                    var selRes = $('#jstree').jstree('select_node', resExp[1]);
                    if (selRes === false) {
                        CATEGORYCONF.reload = true;
                    }
                    return;
                }
                resExp = window.location.search.match(/id=(\d+)/);
                if (resExp !== null && parseFloat(resExp[1])) {
                    $('#jstree').jstree('deselect_all');
                    var selRes = $('#jstree').jstree('select_node', resExp[1]);
                    if (selRes === false) {
                        CATEGORYCONF.reload = true;
                    }
                }
            }
        });
});

//przeładowanie strony
CATEGORYCONF.loadEditUrl = function (nodeId) {
    window.location.assign('/cmsAdmin/category/edit/?id=' + nodeId);
};

//przeładowanie strony
CATEGORYCONF.loadPreviewUrl = function (nodeId) {
    window.open('/cms-content-preview?versionId=' + nodeId, '_blank');
};

CATEGORYCONF.showMessage = function (data) {
    $('#categoryMessageContainer').empty();
    var msg = null;
    if (typeof data.error === 'string') {
        msg = data.error;
    } else if (typeof data.message === 'string') {
        msg = data.message;
    }
    if (msg === null) {
        return;
    }
    $('#categoryMessageContainer').html('<span>(' + msg + ')</span>');
    $('#categoryMessageContainer > span').delay(1500).fadeOut(500);

};

CATEGORYCONF.hideMessage = function () {
    $('#categoryMessageContainer').empty();
};
