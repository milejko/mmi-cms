/**
 * Obsługa drzewka kategorii CMS
 */

var request = request || {};
var tinymce = tinymce || {};
//konfiguracja
var CATEGORYCONF = CATEGORYCONF || {};
//klucz do stanu drzewka
CATEGORYCONF.stateKey = 'cms-category-jstree';
//po jakim czasie ukryć message
CATEGORYCONF.msgDelay = 2500;

//zarządzanie kategoriami
$(document).ready(function () {
	//przeniesienie messengera
	$('ul#messenger').appendTo('#categoryMessageContainer').show();
	
	//odpalenie drzewka
	$('#jstree').jstree({
        'core': {
			'data': {
				'url': request.baseUrl + '/cmsAdmin/category/node',
				'data': function (node) {
					return {'parentId': node.id};
				}
			},
            'themes': {
                'name': 'default',
				'variant': 'small',
				'responsive' : false,
				'stripes' : true
            },
			'strings': {
				'New node': 'Nowa kategoria',
				'Loading ...': 'Ładowanie ...'
			},
			'multiple': false,
			'expand_selected_onload': true,
			'force_text': true,
            'check_callback': function (op) {
				if (op === 'delete_node') {
					return confirm("Czy na pewno usunąć kategorię?");
					}
				return true;
			}
        },
		'state' : {
			'key' : CATEGORYCONF.stateKey
		},
		'unique' : {
			'duplicate': function (name, counter) {
				return name + ' ' + counter;
			}
		},
		'types': {
			'#': { 'valid_children': ["root"] },
			'root': { 'valid_children': ["default", "inactive"], 'icon': request.baseUrl + '/resource/cmsAdmin/images/tree.png' },
			'default': { 'valid_children': ["default", "inactive"] },
			'inactive': { 'valid_children': ["default", "inactive"], 'icon': 'jstree-inactive' }
		},
		'contextmenu': {
			'items': function (node) {
				var tmp = $.jstree.defaults.contextmenu.items();
				delete tmp.ccp;
				tmp.create.label = "Utwórz podkategorię";
				tmp.rename.label = "Zmień nazwę";
				tmp.remove.label = "Usuń";
				if (this.get_type(node) === "root") {
					delete tmp.rename;
					delete tmp.remove;
					tmp.create.separator_after = false;
					tmp.create.label = "Utwórz kategorię bazową";
				} else {
					tmp.edit = {
						"separator_before": true,
						"separator_after": false,
						"label": "Edytuj",
						"action": function (data) {
							var inst = $.jstree.reference(data.reference);
							var node = inst.get_node(data.reference);
							CATEGORYCONF.editForm(node);
						}
					}
				}
				return tmp;
			}
		},
		'plugins' : [ "state", "unique", "types", "contextmenu", "dnd", "wholerow" ]
    })
	.on('delete_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		$.post(request.baseUrl + '/cmsAdmin/category/delete', {'id': data.node.id})
			.done(function (d) {
				if (d.status) {
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', data.parent);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się usunąć kategorii'});
			});
	})
	.on('create_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		$.post(request.baseUrl + '/cmsAdmin/category/create', {'parentId': data.node.parent, 'order': data.position, 'name': data.node.text})
			.done(function (d) {
				if (d.status) {
					data.instance.set_id(data.node, d.id);
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', d.id);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się utworzyć kategorii'});
			});
	})
	.on('rename_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		$.post(request.baseUrl + '/cmsAdmin/category/rename', {'id': data.node.id, 'name': data.text})
			.done(function (d) {
				if (d.status) {
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
				CATEGORYCONF.showMessage({'error': 'Nie udało się zmienić nazwy kategorii'});
			});
	})
	.on('move_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		var params = {'id': data.node.id, 'parentId': data.parent, 'oldParentId': data.old_parent, 'order': data.position, 'oldOrder': data.old_position};
		$.post(request.baseUrl + '/cmsAdmin/category/move', params)
			.done(function (d) {
				if (d.status) {
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', d.id);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się przenieść kategorii'});
			});
	})
	.on('changed.jstree', function (e, data) {
		if (!data || !data.selected || !data.selected.length || !(0 in data.selected)) {
			return;
		}
		if (request.showCategoryForm === true) {
			request.showCategoryForm = false;
			return;
		}
		$('#categoryContentContainer').empty();
	});
});

CATEGORYCONF.editForm = function (node) {
	CATEGORYCONF.hideMessage();
	var params = {'id': node.id};
	params.name = node.text;
	if (node.type) {
		params.type = node.type;
	} else {
		params.type = 'default';
	}
	if (params.id === "0" || params.type === "root") {
		$('#categoryContentContainer').empty();
		return;
	}
	$('#categoryContentContainer').load(request.baseUrl + '/cmsAdmin/category/edit', params, function(responseTxt, statusTxt, xhr) {
		if (statusTxt === "error") {
			$('#categoryContentContainer').empty();
			CATEGORYCONF.showMessage({'error': 'Nie udało się pobrać szczegółów kategorii'});
		} else {
			CATEGORYCONF.initTinyMce();
		}
	});
};

CATEGORYCONF.showMessage = function (data) {
	$('#categoryMessageContainer').empty();
	var msg = null;
	var msgClass = ' class="notice warning"';
	var icon = '<i class="icon-warning-sign icon-large"></i>';
	if (typeof data.error === 'string') {
		msg = data.error;
		msgClass = ' class="notice error"';
		icon = '<i class="icon-remove-sign icon-large"></i>';
	} else if (typeof data.message === 'string') {
		msg = data.message;
		msgClass = ' class="notice success"';
		icon = '<i class="icon-ok icon-large"></i>';
	}
	if (msg === null) {
		return;
	}
	var html = '<ul id="messenger">';
	html += '<li' + msgClass + '>' + icon + '<div class="alert">' + msg + '<a class="close-alert" href="#"></a></div></li>';
	html += '</ul>';
	$('#categoryMessageContainer').html(html);
	$('#categoryMessageContainer ul#messenger').show();
};

CATEGORYCONF.hideMessage = function () {
	$('#categoryMessageContainer').empty();
};

CATEGORYCONF.initTinyMce = function() {
	tinymce.init({
		selector: 'textarea.tinymce',
		language : 'pl',
		theme : 'modern',
		skin : 'lightgray',
		plugins : 'advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen,hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview,searchreplace,tabfocus,table,template,textcolor,visualblocks,visualchars,wordcount',
		toolbar1 : 'undo redo | bold italic underline strikethrough | forecolor backcolor | styleselect | bullist numlist outdent indent | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | link unlink anchor image insertfile preview',
		image_advtab: true,
		contextmenu: 'link image inserttable | cell row column deletetable',
		width: '',
		height: 200,
		autoresize_min_height: 200,
		image_list: request.baseUrl + '/cms/file/list?object=$object&objectId=$objectId&t=$t&hash=$hash',
		document_base_url: request.baseUrl,
		convert_urls: false,
		entity_encoding: 'raw',
		relative_urls: false,
		paste_data_images: false,
		font_formats: 'Andale Mono=andale mono,times;'+
			'Arial=arial,helvetica,sans-serif;'+
			'Arial Black=arial black,avant garde;'+
			'Book Antiqua=book antiqua,palatino;'+
			'Comic Sans MS=comic sans ms,sans-serif;'+
			'Courier New=courier new,courier;'+
			'Georgia=georgia,palatino;'+
			'Helvetica=helvetica;'+
			'Impact=impact,chicago;'+
			'Symbol=symbol;'+
			'Tahoma=tahoma,arial,helvetica,sans-serif;'+
			'Terminal=terminal,monaco;'+
			'Times New Roman=times new roman,times;'+
			'Trebuchet MS=trebuchet ms,geneva;'+
			'Verdana=verdana,geneva;'+
			'Webdings=webdings;'+
			'Wingdings=wingdings,zapf dingbats;'+
			'EmpikBTT=EmpikBold;'+
			'EmpikLTT=EmpikLight;'+
			'EmpikRTT=EmpikRegular',
		fontsize_formats: '1px 2px 3px 4px 6px 8px 9pc 10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 36px 48px 50px 72px 100px'
	});
};