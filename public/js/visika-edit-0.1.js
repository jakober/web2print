String.prototype.chunkString = function(len) {
        var _ret;
        if (this.length < 1) {
                return [];
        }
        if (typeof len === 'number' && len > 0) {
                var _size = Math.ceil(this.length / len), _offset = 0;
                _ret = new Array(_size);
                for (var _i = 0; _i < _size; _i++) {
                        _ret[_i] = this.substring(_offset, _offset = _offset + len);
                }
        }
        else if (typeof len === 'object' && len.length) {
                var n = 0, l = this.length, chunk, that = this;
                _ret = [];
                do {
                        len.forEach(function(o) {
                                chunk = that.substring(n, n + o);
                                if (chunk !== '') {
                                        _ret.push(chunk);
                                        n += chunk.length;
                                }
                        });
                        if (n === 0) {
                                return undefined;
                        }
                } while (n < l);
        }
        return _ret;
};

vEditor = function(cfg) {
        var slowBrowser = null;
        var inputFields = {}; // Hier stehen die erzeugten Input-Felder
        var svgs = []; // Hier stehen die SVGs
        var svgTexts = {}; // Hier stehen die Textfelder im SVG

        var inputErrors = {};

        var config = cfg.svgConfig;
        var handler = cfg.handler;
        var button = cfg.button;

        var values = {}, lastValues = {};
        var rows = {};
        var qrcodes = [];
        var images = [];
        var qrcodesBusy = {};

        var jsqr = new JSQR();
        var scale = 3.55160421545667;

        (function() {
                var s = cfg.svgContainer.getElementsByTagName('svg');
                $.each(s, function(i, o) {
                        var textLayer = document.createElementNS("http://www.w3.org/2000/svg", "g");
                        o.appendChild(textLayer);
                        //textLayer.setAttribute('transform','scale(1.22)')
                        var jO = $(o);
                        svgs[i] = {
                                svg: jO,
                                textLayer: textLayer,
                                w: jO.width(),
                                h: jO.height()
                        };
                });
        })();

        function updateSVG() {
                $.each(svgTexts, function(i, o) {
                        o.update();
                });
                $.each(qrcodes, function(i, o) {
                        o.update();
                });
        }

        function isEmpty(obj) {
                for (var prop in obj) {
                        if (obj.hasOwnProperty(prop))
                                return false;
                }

                return true;
        }

        function updateButton() {
                if (isEmpty(qrcodesBusy)) {
                        $(button).removeClass('disabled');
                } else {
                        $(button).addClass('disabled');
                }

        }

        var myEditor = {
                createForm: function() {
                        var table = document.createElement('table');
                        table.className = 'form';
                        $.each(config.inputs, function(i, o) {
                                var input = new myEditor.input(o);
                                var tr = input.createInputRow();
                                table.appendChild(tr);
                        });
                        cfg.formContainer.appendChild(table);
                },
                setupSVG: function() {
                        $.each(config.texts, function(i, o) {
                                var t = new myEditor.svgText(o);
                                if (o.text !== undefined) {
                                        t.setText(o.text);
                                }
                                svgTexts[o.name] = t;
                        });
                        if (config.qrcodes) {
                                $.each(config.qrcodes, function(i, o) {
                                        qrcodes[i] = new myEditor.qrcode(o, i);
                                });
                        }
                        handler.update(values, svgTexts, images, qrcodes, myEditor);
                        updateSVG();
                },
                getTextLength: function(text, font, fontSize, fontWeight, textTransform) {
                        var e = document.createElementNS("http://www.w3.org/2000/svg", "text");
                        e.style.fontFamily = font;
                        e.style.textTransform = textTransform;
                        e.style.fontWeight = fontWeight;
                        e.style.fontSize = fontSize * 0.93 + 'pt';
                        var textNode = document.createTextNode(text);
                        e.appendChild(textNode);
                        svgs[0].textLayer.appendChild(e);
                        var l = e.getComputedTextLength();
                        svgs[0].textLayer.removeChild(e);
                        return l;
                },
                svgText: function(o) {
                        var r = {
                                data: o,
                                text: "",
                                color: o.color,
                                textAnchor: o.textAlignment === "right" ? "end" : (o.textAlignment === "center" ? "middle" : "start"),
                                fontFamily: o.fontFamily,
                                textTransform: o.textTransform,
                                fontWeight: o.fontWeight,
                                fontSize: o.fontSize,
                                letterSpacing: o.letterSpacing || "normal",

                                setText: function(text) {
                                        this.text = text;
                                },
                                setDeltaX: function(x) {
                                        this.deltaX = x;
                                },
                                setDeltaY: function(y) {
                                        this.deltaY = y;
                                },
                                update: function() {
                                        if (this.svgElement === null) {
                                                if (this.text !== "") {
                                                        var e = document.createElementNS("http://www.w3.org/2000/svg", "text");
                                                        e.setAttributeNS(null, "x", (this.x + this.deltaX) * scale);
                                                        e.setAttributeNS(null, "y", (this.y + this.deltaY) * scale);
                                                        e.style.fontFamily = this.fontFamily;
                                                        e.style.textTransform = this.textTransform;
                                                        e.style.fill = CMYKtoRGB(this.color);
                                                        e.style.fontWeight = this.fontWeight;
                                                        e.style.fontSize = this.fontSize * 0.93 + 'pt';
                                                        e.style.textAnchor = this.textAnchor;

                                                        e.style.letterSpacing = this.letterSpacing;

                                                        this.textNode = document.createTextNode(this.text);
                                                        e.appendChild(this.textNode);
                                                        svgs[this.page - 1].textLayer.appendChild(e);
                                                        this.svgElement = e;
                                                }
                                        } else {
                                                this.svgElement.setAttributeNS(null, "x", (this.x + this.deltaX) * scale);
                                                this.svgElement.setAttributeNS(null, "y", (this.y + this.deltaY) * scale);

                                                this.svgElement.style.letterSpacing = this.letterSpacing;

                                                if (this.text === "" && this.svgElement.remove) {
                                                        this.svgElement.remove();
                                                        this.svgElement = null;
                                                        this.textNode = null;
                                                } else {
                                                        this.textNode.nodeValue = this.text;
                                                }
                                        }
                                },
                                page: o.page || 1,
                                svgElement: null,
                                x: o.x,
                                y: o.y,
                                deltaX: 0,
                                deltaY: 0
                        };
                        return r;
                },
                qrcode: function(o, num) {

                    if(o.size == 40.5 && o.x == 8){
                        o.x = 6;
                    }
                    if(o.size == 40.5 && o.y == 17){
                        o.y = 14;
                    }
                    if(o.size == 40.5){
                        o.size = 32;
                    }
                        var r = {
                                code: o.code,
                                page: o.page || 1,
                                color: o.color,
                                x: o.x,
                                y: o.y,
                                margin: o.margin,
                                typeNumber: o.typeNumber || 10,
                                errorCorrectLevel: o.errorCorrectLevel || 'L',
                                size: o.size,
                                num: num,
                                svgElement: null,
                                intervalID: null,
                                update: function() {
                                        var _this = this;
                                        var f = function() {
                                                var t1 = Date.now();
                                                _this.input.data = _this.code;
                                                var matrix = new jsqr.Matrix(_this.input, _this.qr);
                                                var t2 = Date.now();
                                                _this.matrix = matrix;
                                                if (t2 - t1 > 200) {
                                                        slowBrowser = true;
                                                }
                                                var canvas = _this.canvas;
                                                matrix.scale = 4;
                                                matrix.margin = _this.margin;
                                                var w = matrix.pixelWidth;
                                                canvas.setAttribute('width', w);
                                                canvas.setAttribute('height', w);

                                                var ctx = _this.canvas.getContext('2d');
                                                ctx.fillStyle = '#fff';
                                                ctx.fillRect(0, 0, w, w);
                                                ctx.fillStyle = CMYKtoRGB(_this.color);
                                                matrix.draw(_this.canvas, 0, 0);

                                                if (_this.svgElement === null) {
                                                        var e = document.createElementNS("http://www.w3.org/2000/svg", "image");
                                                        e.setAttributeNS(null, "x", (_this.x) * scale);
                                                        e.setAttributeNS(null, "y", (_this.y) * scale);
                                                        e.setAttributeNS(null, "width", (_this.size) * scale);
                                                        e.setAttributeNS(null, "height", (_this.size) * scale);
                                                        svgs[_this.page - 1].textLayer.appendChild(e);
                                                        _this.svgElement = e;
                                                }
                                                //_this.svgElement.setAttribute("xlink:href", _this.canvas.toDataURL());
                                                _this.image = _this.canvas.toDataURL();
                                                console.log(_this.canvas);
                                                $('#qrCodeLink').attr('href',_this.image);


                                                _this.svgElement.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', _this.image);
                                                _this.intervalID = null;
                                                if (qrcodesBusy[num]) {
                                                        delete qrcodesBusy[num];
                                                }
                                                updateButton();
                                        };

                                        if (slowBrowser) {
                                                if (_this.intervalID) {
                                                        clearTimeout(_this.intervalID);
                                                }
                                                _this.showBusy();
                                                _this.intervalID = setTimeout(f, 2000);
                                        } else {
                                                f();
                                        }
                                },
                                showBusy: function() {
                                        qrcodesBusy[num] = 1;
                                        updateButton();
                                        if (this.svgElement) {
                                                this.svgElement.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href',
                                                                'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWP4////fwAJ+wP9CNHoHgAAAABJRU5ErkJggg==');
                                        }
                                }
                        };
                        var code = r.qr = new jsqr.Code();
                        code.encodeMode = code.ENCODE_MODE.BYTE;
                        code.version = code.DEFAULT;
                        code.errorCorrection = code.ERROR_CORRECTION.M;

                        r.input = new jsqr.Input();
                        r.input.dataType = r.input.DATA_TYPE.DEFAULT;
                        var canvas = document.createElement('canvas');
                        canvas.style.display = 'none';
                        document.getElementById('inputs').appendChild(canvas);
                        r.canvas = canvas;
                        return r;
                },

                input: function(inputData) {
                        var f = inputFields[inputData.name] = {}, regexp;

                        function validate(input, v) {
                                if ((inputData.validation.required === true && v === '') || (v !== '' && regexp && !regexp.test(v))) {
                                        inputErrors[input.name] = input;
                                } else {
                                        $(input).removeClass('error');
                                        if (inputErrors[input.name]) {
                                                delete inputErrors[input.name];
                                        }
                                }
                        }

                        var r = {
                                createInputField: function(value) {
                                        var input,
                                                        val = value || (cfg.values === undefined ? undefined : cfg.values[inputData.name]) || inputData['default'] || "",
                                                        v = inputData.validation;

                                        f.value = val;
                                        values[inputData.name] = lastValues[inputData.name] = val;
                                        if (inputData.values === undefined) {
                                                input = document.createElement('input');
                                                // TODO type=email


                                                input.setAttribute('type', v.email ? 'email' : 'text');
                                                input.setAttribute('value', val);
                                                if (inputData.placeholder) {
                                                        input.setAttribute('placeholder', inputData.placeholder);
                                                }
                                        } else {
                                                input = document.createElement('select');
                                                var option = document.createElement('option');
                                                option.value = "";
                                                var n = document.createTextNode(" - ");
                                                option.appendChild(n);
                                                input.appendChild(option);
                                                $.each(inputData.values, function(i, n) {

                                                        option = document.createElement('option');

                                                        var n2 = n;
                                                        if(n2.match('%')){
                                                        	var mystring = n2.split('%');
                        					n2 = mystring[0]+mystring[1];
                        					if(mystring[2]){
                                n2 = mystring[0]+mystring[1]+mystring[2];
                                }
                                                    	}

                                                        option.value = n;
                                                        if (val === n2) {
                                                                option.setAttribute('selected', 'selected');
                                                        }
                                                        n = document.createTextNode(n2);
                                                        option.appendChild(n);
                                                        input.appendChild(option);
                                                });
                                        }

                                        input.setAttribute('name', inputData.name);

                                        if (cfg.edit === false) {
                                                input.setAttribute('disabled', 'disabled');
                                        }
                                        //if (v.required) {
                                        //    input.setAttribute('required', 'required');
                                        //}
                                        if (v.pattern) {
                                                regexp = new RegExp(v.pattern);
                                                //input.setAttribute('pattern', v.pattern);
                                        }
                                        if (v.maxlength) {
                                                input.setAttribute('maxlength', v.maxlength);
                                        }
                                        input.id = 'vki_' + inputData.name;
                                        f.input = input;
                                        input.onchange = input.onkeyup = function(e) {
                                                var t = e.target;
                                                var n = t.name;
                                                var v = t.value;
                                                values[n] = v;
                                                if (lastValues[n] !== values[n]) {
                                                        handler.update(values, svgTexts, images, qrcodes, myEditor);
                                                        updateSVG();
                                                        lastValues[n] = values[n];
                                                }

                                                validate(input, v);
                                        };
                                        if (inputData.values === undefined) {
                                                input.onpaste = input.oncut = input.onchange;
                                        }
                                        validate(input, val);
                                        return input;
                                },
                                createLabel: function() {
                                        var label = document.createElement('label');
                                        var txt = inputData.label;
                                        var n = document.createTextNode(txt);
                                        label.appendChild(n);
                                        return label;
                                },
                                createSubLabel: function() {
                                        var sublabel = document.createElement('small');
                                        var txt = inputData.sublabel;
                                        var n = document.createTextNode(txt);
                                        sublabel.appendChild(n);
                                        return sublabel;
                                },
                                createInputRow: function(value) {
                                        var label = this.createLabel();

                                        var sublabel = this.createSubLabel();

                                        var input = this.createInputField(value);
                                        label.setAttribute('for', input.id);
                                        var tr = document.createElement('tr');
                                        tr.setAttribute('id', 'tr_' + input.name);
                                        var td = document.createElement('td');
                                        td.appendChild(label);
                                        if (inputData.sublabel) {
                                            td.appendChild(sublabel);
                                        }
                                        if (inputData.info) {
                                                var info = document.createElement('div');
                                                info.className = "info";
                                                var div = document.createElement('div');
                                                //var n = document.createTextNode("inputData.info");
                                                //div.appendChild(n)

                                                div.innerHTML = inputData.info;
                                                info.appendChild(div);
                                                td.appendChild(info);
                                        }

                                        td.className = "col1";
                                        tr.appendChild(td);
                                        td = document.createElement('td');
                                        td.appendChild(input);
                                        td.className = "col2";
                                        tr.appendChild(td);
                                        rows[input.name] = tr;
                                        return tr;
                                },
                                setSVGText: function(value) {
                                        if (f.value === "" && value !== "") {
                                                f.value = value;
                                                this.createSVGField();
                                        }
                                        else if (f.value !== "" && value !== "") {
                                                f.value = f.textNode.nodeValue = value;
                                        }
                                        else if (f.value !== "" && value === "") {
                                                var n = f.textNode;
                                                f.value = "";
                                                n.parentNode.removeChild(n);
                                        }
                                },
                                bindInput: function() {
                                        var i = f.input;
                                        var func = function(e) {

                                                var t = e.target;
                                                var n = t.name;
                                                var f = inputFields[n].fieldObject;
                                                f.setSVGText(t.value);
                                        };
                                        i.onkeyup = i.onchange = func;
                                },
                                handler: cfg.handler
                        };
                        f.fieldObject = r;
                        if (inputData.text)
                                f.value = inputData.text;
                        return r;
                },
                hideRow: function(name) {
                        var tr = rows[name];
                        if (tr) {
                                tr.style.display = 'none';
                        }
                },
                showRow: function(name) {
                        var tr = rows[name];
                        if (tr) {
                                tr.style.display = 'table-row';
                        }
                },
                submitData: function(options) {
                        var data = {
                                input: values,
                                output: [
                                        {
                                                texts: [],
                                                images: [],
                                                qrcodes: []
                                        },
                                        {
                                                texts: [],
                                                images: [],
                                                qrcodes: []
                                        }
                                ]
                        };
                        $.each(svgTexts, function(id, o) {
                                if (o.text === "" || o.text === undefined) {
                                        return;
                                }
                                var t = {
                                        x: o.x + o.deltaX,
                                        y: o.y + o.deltaY,
                                        name: id,
                                        fontFamily: o.fontFamily,
                                        textTransform: o.textTransform,
                                        fontWeight: o.fontWeight,
                                        fontSize: o.fontSize,
                                        text: o.text,
                                        textAnchor: o.textAnchor,
                                        color: o.color
                                };
                                data.output[o.page - 1].texts.push(t);
                        });

                        $.each(qrcodes, function(i, o) {
                                var t = {
                                        x: o.x,
                                        y: o.y,
                                        size: o.size,
                                        image: o.image,
                                        color: o.color,
                                        code: o.code,
                                        typeNumber: o.typeNumber,
                                        errorCorrectLevel: o.errorCorrectLevel,
                                        matrix: o.matrix,
                                        margin: o.margin
                                };
                                data.output[o.page - 1].qrcodes.push(t);
                        });

                        var s = JSON.stringify(data);
                        var submitData = {data: s};
                        if (options.id) {
                                submitData.id = options.id;
                        }
                        $.ajax({
                                url: options.url,
                                type: 'POST',
                                dataType: 'JSON',
                                data: submitData,
                                success: function() {
                                        if (options.success) {
                                                options.success();
                                        }
                                },
                                error: function() {
                                        if (options.error) {
                                                options.error();
                                        }
                                }
                        });
                },
                setSVGScale: function(scale) {
                        $.each(svgs, function(i, o) {
                                $(o.svg).width(scale * o.w);
                                $(o.svg).height(scale * o.h);
                        });
                },
                validateForm: function() {
                        if (isEmpty(inputErrors)) {
                                return true;
                        }
                        $.each(inputFields, function(k, i) {
                                if (inputErrors.hasOwnProperty(k)) {
                                        $(inputFields[k].input).addClass('error');
                                } else {
                                        $(inputFields[k].input).removeClass('error');
                                }
                        });
                        return false;
                },
                log: function() {
                }
        };
        if (cfg.formContainer) {
                myEditor.createForm();
        }
        myEditor.setupSVG();
        return myEditor;
};