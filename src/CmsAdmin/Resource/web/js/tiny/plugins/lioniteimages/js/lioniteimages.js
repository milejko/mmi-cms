;
(function () {
	var parentWin = (!window.frameElement && window.dialogArguments) || opener || parent || top;
	var parentEditor = parentWin.lionite_activeEditor;

	var LioniteImages = window.LioniteImages = {
		options: {
			baseUrl: parentEditor.settings.baseUrl,
			uploadUrl: parentEditor.settings.baseUrl + '/cmsAdmin/upload/plupload',
			params: {
				formObject: parentEditor.settings.object,
				formObjectId: parentEditor.settings.objectId,
				cmsFileId: 0
			},
			removeUrl: parentEditor.settings.baseUrl + '/cmsAdmin/upload/delete',
			galleryUrl: parentEditor.settings.baseUrl + '/?module=cms&controller=file&action=listLayout&class=' + config.dataTyp + '&object=' + parentEditor.settings.object + '&objectId=' + parentEditor.settings.objectId + '&t=' + parentEditor.settings.time + '&hash=' + parentEditor.settings.hash,
			dataTyp: config.dataTyp,
			dataAccept: config.dataAccept,
			maxFileSize: config.maxFileSize,
			maxChunkSize: config.maxChunkSize,
			callback: function (src, options) {

			}
		},
		init: function () {
			var o = this.options;
			var lionit = this;
			this.ajaxUpload($('#lionite-file-upload'), o.uploadUrl);
			$.ajax({
				url: o.galleryUrl,
				success: function (response) {
					$('#lionite-gallery').html(response);
				}
			});
			this.events();
		},
		events: function () {
			var self = this;
			var o = this.options;
			$('#lionite-gallery').click(function (e) {
				e.preventDefault();
				var el = $(e.target);
				if (el.is('a')) {
					if (el.is('.insert')) {

						if (el.attr('data-typ') == 'image') {
							var box = '<figure class="image" contenteditable="false"><img contenteditable="true" src="' + el.attr('href') + '" alt="" /><figcaption>opis</figcaption></figure>';
						}

						if (el.attr('data-typ') == 'audio') {
							var box = '<audio src="' + el.attr('href') + '" controls></audio>';
						}

						if (el.attr('data-typ') == 'video') {
							var box = '<video src="' + el.attr('href') + '" controls></video>';
						}

						self.insert(box);
						self.close();

					} else if (el.is('.delete')) {
						$('#error').html('');

						var confirm = 'div#dialog-confirm';
						$(confirm + ' p').html('Czy na pewno trwale usunąć plik ' + el.attr('data-file') + '?');
						$('#dialog-confirm').dialog({
							resizable: false,
							width: 500,
							modal: true,
							closeText: 'Zamknij',
							title: 'Usunąć plik?',
							buttons: {
								'Usuń': function () {
									$.post(o.removeUrl, {cmsFileId: el.attr('data-id'), object: o.params.formObject, objectId: o.params.formObjectId}, 'json')
											.done(function (data) {
												if (data.result === 'OK') {
													el.parents('li:first').remove();
												} else {
													$('#error').html('Usunięcie pliku nie powiodło się');
												}
											})
											.fail(function () {
												$('#error').html('Usunięcie pliku nie powiodło się');
											});
									$(this).dialog('close');
								},
								'Anuluj': function () {
									$(this).dialog('close');
								}
							}
						});
					} else if (el.is('.b_audio')) {
						var box = el.parent().find('.audio');
						var getaudio = el.parent().find('audio')[0];

						if (!box.hasClass("audioplay")) {
							
							$('audio').each(function(){
								$(this).parent().parent().find('.edit').html('Odtwórz');
								$(this).parent().parent().find('.audio').removeClass('audioplay');
								this.pause();
								this.currentTime = 0;
							});
							
							box.addClass('audioplay');
							getaudio.load();
							getaudio.play();
							el.html('Stop');
						} else if (box.hasClass("audioplay")) {
							el.html('Odtwórz');
							getaudio.pause();
							box.removeClass('audioplay');
						}
					} else if (el.is('.b_video')) {
						var box = el.parent().find('.video');
						var getaudio = el.parent().find('video')[0];

						if (!box.hasClass("videoplay")) {
							
							$('video').each(function(){
								$(this).parent().parent().find('.edit').html('Odtwórz');
								$(this).parent().parent().find('.video').removeClass('audioplay');
								this.pause();
								this.currentTime = 0;
							});
							
							box.addClass('videoplay');
							getaudio.load();
							getaudio.play();
							el.html('Stop');
						} else if (box.hasClass("videoplay")) {
							el.html('Odtwórz');
							getaudio.pause();
							box.removeClass('videoplay');
						}
					}
				}

			});
			$('#lionite-gallery-manager .urlinsert').click(function () {
				var src = $("input[name='src']").val();
				if (src != '') {
					var image = '<img src="' + src + '" />';

					self.insert(image);
				}
				self.close();
			});
			$('.close-link').click(function () {
				self.close();
			});

		},
		insert: function (text) {
			parentEditor.insertContent(text);
		},
		close: function () {
			parentEditor.windowManager.close();
		},
		ajaxUpload: function (el, connector) {
			var o = this.options;
			var url = connector,
					uploadButton = $('<button/>')
					.addClass('btn btn-primary')
					.prop('disabled', true)
					.text('Processing...')
					.on('click', function () {
						var $this = $(this),
								data = $this.data();
						$this
								.off('click')
								.text('Abort')
								.on('click', function () {
									$this.remove();
									data.abort();
								});
						data.submit().always(function () {
							$this.remove();
						});
					});

			$(el).fileupload({
				url: url,
				dropZone: $(el).parents(".dropzone:first"),
				dataType: 'json',
				autoUpload: true,
				acceptFileTypes: config.dataAccept,
				sequentialUploads: true,
				maxFileSize: config.maxFileSize,
				maxChunkSize: config.maxChunkSize,
				processQueue: {
					action: 'validate',
					acceptFileTypes: config.dataAccept
				},
				formData: function (form) {
					var params = form.serializeArray();

					// generowanie fileid
					if( this.files[0].fileid === undefined ){
						this.files[0].fileid = Math.random().toString(36).substring(2) + this.files[0].lastModified;
					}
					
					// dodatkowe dane dla filecontrollera
					params.push({name: 'fileId', value: this.files[0].fileid});
					params.push({name: 'name', value: this.files[0].name});
					params.push({name: 'fileSize', value: this.files[0].size});

					// przeslanie chunk
					if (this.maxChunkSize !== undefined && this.maxChunkSize < this.files[0].size) {
						this.files[0].chunks = Math.ceil(this.files[0].size / this.maxChunkSize);
						if (this.files[0].chunk === undefined) {
							this.files[0].chunk = 0;
						}else{
							this.files[0].chunk = this.files[0].chunk + 1;
						}						
						params.push({name: 'chunks', value: this.files[0].chunks});
						params.push({name: 'chunk', value: this.files[0].chunk});
					}

					for (var i in o.params) {
						params.push({name: i, value: o.params[i]});
					}
					return params;
				}
			});
			$(el).on('fileuploadadd', function (e, data) {
				data.context = $('<div/>').appendTo('#files');

			});
			$(el).on('fileuploadprocessalways', function (e, data) {
				var index = data.index,
						file = data.files[index],
						node = $(data.context.children()[index]);


				if (file.preview) {
					node
							.prepend('<br>')
							.prepend(file.preview);
				}
				if (file.error) {
					var txt = file.error + ' : ' + file.name;
					$('#error')
							.append('<br>')
							.append($('<span class="text-danger"/>').text(txt));
				}
				if (index + 1 === data.files.length) {
					data.context.find('button')
							.text('Upload')
							.prop('disabled', !!data.files.error);
				}
			});
			$(el).on('fileuploadprogressall', function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .progress-bar').css(
						'width',
						progress + '%'
						);

				if (progress > 99) {
					$.ajax({
						url: o.galleryUrl,
						success: function (response) {
							$('#lionite-gallery').html(response);
							$('#progress .progress-bar').css({width: 0});
						}
					});
				}
			});
			$(el).on('fileuploaddone', function (e, data) {

				/*
				 $.each(data.result.files, function (index, file) {
				 if(file.html) {
				 $('#lionite-gallery').append(file.html);
				 $('#progress .progress-bar').css({width:0});
				 } else if (file.url) {
				 var link = $('<a>')
				 .attr('target', '_blank')
				 .prop('href', file.url);
				 $(data.context.children()[index])
				 .wrap(link);
				 } else if (file.error) {
				 var error = $('<span class="text-danger"/>').text(file.error);
				 $(data.context.children()[index])
				 .append('<br>')
				 .append(error);
				 }
				 });
				 */
			});
			$(el).on('fileuploadfail', function (e, data) {
				$.each(data.files, function (index, file) {
					var error = $('<span class="text-danger"/>').text('File upload failed.');
					$(data.context.children()[index])
							.append('<br>')
							.append(error);
				});
			}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

			$(el).on('fileuploadstart', function (e, data) {
				$('#error').html('');
			});

		}
	};
})();
$(function () {
	LioniteImages.init();
});
