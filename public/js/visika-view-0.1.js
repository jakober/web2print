vViewer = function(cfg) {
    var svgs = [];
    var viewer = {
        init: function() {
            var s = cfg.svgContainer.getElementsByTagName('svg');
            $.each(s, function(i, o) {
                var textLayer = document.createElementNS("http://www.w3.org/2000/svg", "g");
                o.appendChild(textLayer);
                var jO = $(o);
                if (cfg.scale) {
                    jO.width(cfg.scale * jO.width());
                    jO.height(cfg.scale * jO.height());
                }
                svgs[i] = {
                    svg: jO,
                    textLayer: textLayer,
                    w: jO.width(),
                    h: jO.height()
                };
            });

            var i = 0, j = 0;
            var data = cfg.data;
            for (i = 0; i < data.length && svgs[i]; i++) {
                var o = data[i];
                var texts = o.texts;
                for (j = 0; j < texts.length; j++) {
                    var t = texts[j];
                    var e = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    e.setAttributeNS(null, "x", t.x * 3.55160421545667);
                    e.setAttributeNS(null, "y", t.y * 3.55160421545667);
                    e.style.fontFamily = t.fontFamily;
                    e.style.fill = CMYKtoRGB(t.color);
                    e.style.fontWeight = t.fontWeight;
                    e.style.textAnchor = t.textAnchor;
                    e.style.fontSize = t.fontSize * 0.93 + 'pt';
                    var textNode = document.createTextNode(t.text);
                    e.appendChild(textNode);
                    svgs[i].textLayer.appendChild(e);
                }

                var images = o.qrcodes;
                for (j = 0; j < images.length; j++) {
                    var t = images[j];
                    var e = document.createElementNS("http://www.w3.org/2000/svg", "image");
                    if( t.size == 40.5 && t.x == 8 ){t.x = 6}
                    if( t.size == 40.5 && t.y == 17 ){t.y = 14}
                    if( t.size == 40.5 ){t.size = 32}
                    e.setAttributeNS(null, "x", t.x * 3.55160421545667);
                    e.setAttributeNS(null, "y", t.y * 3.55160421545667);
                    e.setAttributeNS(null, "width", t.size * 3.55160421545667);
                    e.setAttributeNS(null, "height", t.size * 3.55160421545667);
                    e.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', t.image);
                    svgs[i].textLayer.appendChild(e);
                }

                var images = o.images;
                for (j = 0; j < images.length; j++) {
                    var t = images[j];
                    var e = document.createElementNS("http://www.w3.org/2000/svg", "image");
                    e.setAttributeNS(null, "x", t.x * 3.55160421545667);
                    e.setAttributeNS(null, "y", t.y * 3.55160421545667);
                    e.setAttributeNS(null, "width", t.size * 3.55160421545667);
                    e.setAttributeNS(null, "height", t.size * 3.55160421545667);
                    e.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', t.image);
                    svgs[i].textLayer.appendChild(e);
                }
            }
        },

    };
    viewer.init();
    return viewer;
}