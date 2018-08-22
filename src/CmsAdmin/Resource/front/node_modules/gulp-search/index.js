'use strict';
var fs = require('fs');
var path = require('path');
var objectAssign = require('object-assign');
var sortKeys = require('sort-keys');
var Transform = require('readable-stream/transform');


function exists(pth, mode) {
  try {
    fs.accessSync(pth, mode);
    return true;
  } catch (e) {
    return false;
  }
}

module.exports = function(regexp, fn, opt) {
  var options = objectAssign({
    path: '',
    filename: 'gulp-search-menifest.json'
  }, opt);
  return new Transform({
    objectMode: true,
    transform: function(file, enc, callback) {
      if (file.isNull()) {
        return callback(null, file);
      }

      function doSearch() {
        if (file.isStream()) {
          this.emit('error', new PluginError('gulp-search', 'Streams aren\'t supported'));
          return callback(null, file);
        }

        if (file.isBuffer()) {
          if (regexp instanceof RegExp) {
            var str = String(file.contents),
                entris = str.match(regexp),
                basePath = options.path,
                filename = options.filename,
                filePath = path.join(basePath, filename);

            var fileStr = '';
            if (entris && entris.length > 0) {
              entris.forEach(function (item) {
                if (!exists(filePath)) {
                  if (!exists(basePath)) {
                    fs.mkdirSync(basePath);
                  }
                  fs.writeFileSync(filePath, JSON.stringify({}, null, '  '));
                }
                fileStr = fs.readFileSync(filePath, {
                  encoding: 'utf8'
                });

                var oldObj = JSON.parse(fileStr);
                var newObj = objectAssign(oldObj, fn(item))
                fs.writeFileSync(filePath, JSON.stringify(sortKeys(newObj), null, '  '), {
                  flag: 'w'
                });
              });
            }
            console.log('gulp-search : ',entris);
          }
        }
        callback(null, file);
      }

      doSearch();
    }
  });
};
