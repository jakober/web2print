function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [],
                    pre_fon = '+49 ', pre_fax = pre_fon,
                    maxlength = 250;

            var formatMobil = function(dw) {
                return '+49 152 ' + ('22887' + dw).chunkString(2).join(' ');
            };
            if (values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            texts.name.setText(name);
            values.fullName = name;

            var titel1, titel2 = '', abteilung1, abteilung2 = '';
            if (values.titel1 === "(Freitext)") {
                titel1 = values.titel1_freitext;
                editor.showRow('titel1_freitext');
            } else {
                titel1 = values.titel1;
                editor.hideRow('titel1_freitext');
            }
            titel2 = values.titel2;
            texts.titel1.setText(titel1);
            texts.titel2.setText(titel2);
            var deltaY = 0;
            if (titel1 === '') {
                deltaY -= 3;
            }
            texts.titel2.setDeltaY(deltaY);
            if (titel2 === '') {
                deltaY -= 3;
            }
            abteilung1 = values.position;
            if (values.abteilung !== '') {
                if (abteilung1 !== '') {
                    abteilung1 += ' ';
                }
                abteilung1 += values.abteilung;
                var l = editor.getTextLength(abteilung1, 'Helvetica Neue', 'normal', 6.5);
                if (l > maxlength) {
                    abteilung1 = values.position;
                    abteilung2 = values.abteilung;
                }
            }
            texts.abteilung1.setText(abteilung1);
            texts.abteilung1.setDeltaY(deltaY);
            if (abteilung1 === '') {
                deltaY -= 3;
            }
            texts.abteilung2.setText(abteilung2);
            texts.abteilung2.setDeltaY(deltaY);
            texts.email.setText(values.email);
            texts.fon.setText(pre_fon + values.fon);

            var deltaY = 0;
            if (values.fax === '') {
                deltaY = -3;
                texts.fax.setText('');
                texts._fax.setText('');
            }
            else {
                texts.fax.setText(pre_fax + values.fax);
                texts._fax.setText('Fax');
            }

            if (values.mobil === '') {
                deltaY -= 3;
                texts.mobil.setText('');
                texts._mobil.setText('');
            } else {
                texts.mobil.setText(formatMobil(values.mobil));
                texts._mobil.setText('Mobil');
                texts.mobil.setDeltaY(deltaY);
                texts._mobil.setDeltaY(deltaY);
            }

            texts.email.setDeltaY(deltaY);
            if (values.email === '') {
                deltaY -= 3;
            }
            texts.internet.setDeltaY(deltaY);
        }


    };
    return r;
}