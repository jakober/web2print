(function() {
    var path = '../public/visika';
    var finder = require('./myfinder.js');

    var params = {
        path: path,
        callback: function(info) {
            var follow = true;
            if (info.filename === '.svn') {
                follow = false;
            }
            if (info.filename === 'config.json') {
                fn(info.path);
            }

            return follow;
        }

    };

    function fn(file) {
        var cfg = require(file);
        cfg.texts.forEach(function(it) {
            if('align' in it) {
                delete it.align;
            }
        });
        var s = JSON.stringify(cfg,null,4);
        require('fs').writeFileSync(file,s);
    }
    finder.find(params);
})();