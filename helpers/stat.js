(function() {

    if(process.argv.length!==3) {
        process.stderr.write('Usage: ' + process.argv[0] + ' ' + process.argv[1] + ' <client>\n')
        return;
    };
    
    
    var client = process.argv[2];
    
    var path = '../public/visika/'+client;
            
    var finder = require('./myfinder.js');

    var stat = {_count:0};
    
    var params = {
        path: path,
        callback: function(info) {
            var follow = true;
            if(info.filename==='.svn') {
                follow=false;
            }
            if(info.filename==='config.json') {
                
                var data = require(info.path);
                stat._count++;
                data.inputs.forEach(function(it) {
                    var name=it.name;
                    if(name in stat) {
                        stat[name]++;
                    } else {
                        stat[name]=1;
                    }
                    
                })
            };
            return follow;
        }
        
    };
    
    finder.find(params);
    console.log(stat)
})();