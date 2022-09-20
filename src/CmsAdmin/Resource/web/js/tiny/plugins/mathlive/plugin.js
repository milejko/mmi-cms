(function() {
  /**
   * editor init hook fnc
   * @param ed
   */
  var initHandler = function(ed, url) {
    var settings;
    var configFile;

    if (ed.settings.mathlive) {
      settings = ed.settings.mathlive;
    }

    var MathliveOpen = function(latex) {
      if (window.navigator.userAgent.indexOf('Edge') > -1) {
        var $modal = $('.js-browser');
        $modal.css('display', 'flex');
        $('.modal-close, .js-btn-close').on('click', function() {
          $modal.fadeOut();
        });
      } else
        ed.windowManager.open(
          {
            url: url + '/index.html',
            title: settings.toolbarBtnText,
            resizable: true,
            maximiziable: true,
            width: '900px',
            classes: 'mathlive-popup'
          },
          {
            latex: typeof latex === 'string' ? latex : '',
            config: configFile || {}
          }
        );
    };

    var Init = function(event) {
      $.getJSON(url + '/config/technical.json').done(function(config) {
        configFile = config;
      });
    };
    var replaceContent = function(element) {
      element.each((i, element) => {
        var latex = $(element)
          .find('.selectable')
          .text()
          .replace(/\$/, '')
          .replace(/\$$/, '');
        var markup = MathLive.latexToMarkup(latex);
        $(element).replaceWith(
          '<span class="rendered-latex" contenteditable="false">' +
            '<span class="selectable" style="display: none">' +
            latex +
            '</span>' +
            '\n  ' +
            markup +
            '\n</span>'
        );
      });
    };

    var PreProcess = function(event) {
      replaceContent($(event.target.dom.select('.rendered-latex.mathquill-rendered-math')));
    };

    var LoadContent = function(event) {
      replaceContent($(event.target.dom.select('.rendered-latex.mathquill-rendered-math')));
    };
    var SetContent = function(event) {
      $(ed.getDoc())
        .off('click')
        .on('click', '.rendered-latex', function(e) {
          try {
            if (!ed.windowManager.getWindows().length) {
              ed.selection.select($($(this).find('.rendered-latex').prevObject).get(0));
              var latex = $(this)
                .find('.selectable')
                .text()
                .replace(/\$/, '')
                .replace(/\$$/, '');
              e.stopPropagation();
              MathliveOpen(latex);
            }
          } catch (e) {
            e.stopPropagation();
            MathliveOpen('');
          }
        });
    };
    var KeyPress = function(e) {
      if (e.altKey && e.which === 81) {
        if (!ed.windowManager.getWindows().length) {
          MathliveOpen();
        }
      }
    };

    ed.on('Init', Init);
    ed.on('PreProcess', PreProcess);
    ed.on('KeyUp', KeyPress);
    ed.on('KeyDown', KeyPress);
    ed.on('LoadContent', LoadContent);
    ed.on('SetContent', SetContent);

    ed.addButton('mathlive', {
      title: settings.toolbarBtnText || 'Open MathLive',
      image: url + '/icon.png',
      onclick: MathliveOpen
    });
  };

  var getInfo = function() {
    return {
      longname: 'MathLive',
      author: 'Rafał Piekarski / Nowaera',
      authorurl: 'http://www.nowaera.pl',
      infourl: 'http://www.nowaera.pl',
      version: '0.1'
    };
  };

  /**
   * NE Mathlive.
   */
  tinymce.create('tiny.mathlive', {
    init: initHandler,
    getInfo: getInfo
  });
  tinymce.PluginManager.add('mathlive', tiny.mathlive);
})();
