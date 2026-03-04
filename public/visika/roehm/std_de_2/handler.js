function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [],
                    pre_fon = '+49 7325 16 ', pre_fax = pre_fon,
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
			abteilung2 = values.abteilung;
            texts.abteilung1.setText(abteilung1);
            texts.abteilung1.setDeltaY(deltaY);
            if (abteilung1 === '') {
                deltaY -= 3;
            }
            texts.abteilung2.setText(abteilung2);
            texts.abteilung2.setDeltaY(deltaY);

            texts.email.setText(values.email);
            texts.email_2.setText(values.email);

            texts.fon.setText(pre_fon + values.fon);
            texts.fon_2.setText(pre_fon + values.fon);

            var deltaY = 0;
            
            if (values.fon === '') {
                deltaY = -3;
                texts.fon.setText('');
                texts._fon.setText('');
                texts.fon_2.setText('');
                texts._fon_2.setText('');
            }
            else {
                var t;
                
                texts.fon.setText(t=pre_fon + values.fon);
                texts._fon.setText('Tel.');
                texts.fon_2.setText(t);
                texts._fon_2.setText('Phone');
            }
            
            
            if (values.fax === '') {
                deltaY -= 3;
                texts.fax.setText('');
                texts._fax.setText('');
                texts.fax_2.setText('');
                texts._fax_2.setText('');
            }
            else {
                var t;
                texts.fax.setText(t=pre_fax + values.fax);
                texts._fax.setText('Fax');
                texts.fax_2.setText(t);
                texts._fax_2.setText('Fax');
            }
            
            texts.fax.setDeltaY(deltaY);
			texts._fax.setDeltaY(deltaY);
			texts.fax_2.setDeltaY(deltaY);
			texts._fax_2.setDeltaY(deltaY);
			
            if (values.mobil === '') {
                deltaY -= 3;
                texts.mobil.setText('');
                texts._mobil.setText('');
                texts.mobil_2.setText('');
                texts._mobil_2.setText('');
            } else {
                var t;
                texts.mobil.setText(t=formatMobil(values.mobil));
                texts._mobil.setText('Mobil');
                texts.mobil.setDeltaY(deltaY);
                texts._mobil.setDeltaY(deltaY);
                texts.mobil_2.setText(t);
                texts._mobil_2.setText('Cell');
                texts.mobil_2.setDeltaY(deltaY);
                texts._mobil_2.setDeltaY(deltaY);
            }

            texts.email.setDeltaY(deltaY);
            texts.email_2.setDeltaY(deltaY);

            if (values.email === '') {
                deltaY -= 3;
            }
            texts.internet.setDeltaY(deltaY);
            texts.internet_2.setDeltaY(deltaY);

            // EN, Seite 2
            texts.name_2.setText(name);
            if (values.titel1_2 === "(Freitext)") {
                titel1 = values.titel1_freitext_2;
                editor.showRow('titel1_freitext_2');
            } else {
                titel1 = values.titel1_2;
                editor.hideRow('titel1_freitext_2');
            }
            titel2 = values.titel2_2;
            texts.titel1_2.setText(titel1);
            texts.titel2_2.setText(titel2);
            var deltaY = 0;
            if (titel1 === '') {
                deltaY -= 3;
            }
            texts.titel2_2.setDeltaY(deltaY);
            if (titel2 === '') {
                deltaY -= 3;
            }
            abteilung1 = values.position_2;
			abteilung2 = values.abteilung_2;                
            texts.abteilung1_2.setText(abteilung1);
            texts.abteilung1_2.setDeltaY(deltaY);
            if (abteilung1 === '') {
                deltaY -= 3;
            }
            texts.abteilung2_2.setText(abteilung2);
            texts.abteilung2_2.setDeltaY(deltaY);

        }

    };
    return r;
}
