function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [];

            //if (values.titel !== '') {
            //    n.push(values.titel);
            //}
            if (values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            texts.name.setText(name);
            values.fullName = name;

            var titel1, titel2 = '';
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

            var abteilung1, abteilung2 = '';
            if (values.abteilung1 === "(Freitext)") {
                abteilung1 = values.abteilung1_freitext;
                editor.showRow('abteilung1_freitext');
            } else {
                abteilung1 = values.abteilung1;
                editor.hideRow('abteilung1_freitext');
            }
            texts.abteilung1.setText(abteilung1);

            if (values.abteilung2 === "(Freitext)") {
                abteilung2 = values.abteilung2_freitext;
                editor.showRow('abteilung2_freitext');
            } else {
                abteilung2 = values.abteilung2;
                editor.hideRow('abteilung2_freitext');
            }
            texts.abteilung2.setText(abteilung2);

            var deltaY = 0;
            if (titel1 === '') {
                deltaY -= 3;
            }

            texts.titel2.setDeltaY(deltaY);

            if (titel2 === '') {
                deltaY -= 3;
            }

            texts.abteilung1.setDeltaY(deltaY);

            if (abteilung1 === '') {
                deltaY -= 3;
            }

            texts.abteilung2.setDeltaY(deltaY);

            texts.strasse.setText(values.strasse);
            texts.ort.setText(values.ort);

            texts.email.setText(values.email);
            texts.fon.setText(values.fon);
            texts.fax.setText(values.fax);

            texts.mobil.setText(values.mobil);
            
            
            deltaY = 0;
            
            if (values.fon === '') {

                deltaY -= 3;
                texts._fon.setText('');
            } else {
                deltaY -= 0;
                texts._fon.setText('Tel.');
            }
            
            texts.fax.setDeltaY(deltaY);
            texts._fax.setDeltaY(deltaY);
            if (values.fax === '') {

                deltaY -= 3;
                texts._fax.setText('');
            } else {
                deltaY -= 0;
                texts._fax.setText('Fax');
            }
            
            texts.mobil.setDeltaY(deltaY);
            texts._mobil.setDeltaY(deltaY);
            
            if (values.mobil === '') {

                deltaY -= 3;
                texts._mobil.setText('');
            } else {
                deltaY -= 0;
                texts._mobil.setText('Mobil');
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