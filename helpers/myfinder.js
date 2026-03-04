(function() {
    var fs = require('fs');
    var modPath = require('path');
    var sep = modPath.sep;
    function walkDir(findParams, info) {
        
        var follow = findParams.callback(info);
        if(follow && info.stat.isDirectory()) {
            var files = fs.readdirSync(info.path);
            files.forEach(function(f) {
                var path = info.path + sep + f;
                walkDir(findParams, {
                    path: path,
                    depth: info.depth+1,
                    parent: info.path,
                    filename: f,
                    stat: fs.statSync(path)
                });
            });
        }        
    }
    
    module.exports = {
        find: function(findParams) {
            var path = modPath.normalize(findParams.path);
            
            var info = {
                path: path,
                depth: 0,
                parent: modPath.dirname(path),
                filename: modPath.basename(path),
                stat: fs.statSync(path)
            };
            
            var r = walkDir(findParams, info);
        },
        ACCEPT: 1
        
    }
})();

    