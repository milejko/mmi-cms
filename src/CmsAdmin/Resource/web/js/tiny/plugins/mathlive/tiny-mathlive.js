var needPositionFix = true;
function setLatex () {
  var newLatex = '<span class="rendered-latex" contenteditable="false">' +
        '<span class="selectable" style="display: none">' + mf.latex().replace(/null/g, '') + '</span>' +
        '\n  ' + MathLive.latexToMarkup(mf.latex().replace(/null/g, '')) + '\n</span>';
  top.tinymce.activeEditor.selection.setContent(newLatex);
  // Enforces change event -> GT-4783
  top.tinymce.activeEditor.execCommand('mceAutoresize');
  top.tinymce.activeEditor.windowManager.close();
}

function resizeTinyWindow () {
  if (needPositionFix) {
    var editorId = top.tinymce.activeEditor.windowManager.windows[0]._id;
    var bodyId = '#' + editorId + '-body';
    var sumHeight = $('#mathliveFiled').height() + 100 + $('.ML__keyboard').height();
    top.$(bodyId).height(sumHeight);
    top.$('#' + editorId).css('top', '10%');
    needPositionFix = false;
  }
}

function cancelLatex () {
  top.tinymce.activeEditor.windowManager.close();
}

var params = {
  latex: '',
  mathliveConfig: {}
};

params = Object.assign(params, top.tinymce.activeEditor.windowManager.getParams());
params.config.locale = 'pl';
params.config.removeExtraneousParentheses = false;
params.config.onContentDidChange = function () {
  if ($('.ML__fieldcontainer__field > .ML__mathlive').width() >= 550) {
    $('#mathliveFiled').css('border', '2px solid red');
    $('.mathlive-button.add-to-test').prop('disabled', true);
  } else {
    $('#mathliveFiled').css('border', '');
    $('.mathlive-button.add-to-test').prop('disabled', false);
  }
};
var mf = MathLive.makeMathField('mathliveFiled', params.config);
mf.toggleVirtualKeyboard_();
mf.$focus();
$('html').css('overflow', 'hidden');
$('[data-command*="copyToClipboard"]').remove();
resizeTinyWindow();
mf.$latex(params.latex);
