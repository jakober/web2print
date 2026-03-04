function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [], t;

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
            texts.name_2.setText(name);
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

            var titel1_2, titel2_2 = '';
            if (values.titel1_2 === "(Freitext)") {
                titel1_2 = values.titel1_freitext_2;
                editor.showRow('titel1_freitext_2');
            } else {
                titel1_2 = values.titel1_2;
                editor.hideRow('titel1_freitext_2');
            }
            titel2_2 = values.titel2_2;
            texts.titel1_2.setText(titel1_2);
            texts.titel2_2.setText(titel2_2);

            var abteilung1_2, abteilung2_2 = '';
            if (values.abteilung1_2 === "(Freitext)") {
                abteilung1_2 = values.abteilung1_freitext_2;
                editor.showRow('abteilung1_freitext_2');
            } else {
                abteilung1_2 = values.abteilung1_2;
                editor.hideRow('abteilung1_freitext_2');
            }
            texts.abteilung1_2.setText(abteilung1_2);

            if (values.abteilung2_2 === "(Freitext)") {
                abteilung2_2 = values.abteilung2_freitext_2;
                editor.showRow('abteilung2_freitext_2');
            } else {
                abteilung2_2 = values.abteilung2_2;
                editor.hideRow('abteilung2_freitext_2');
            }
            texts.abteilung2_2.setText(abteilung2_2);

            var deltaY = 0;
            if (titel1_2 === '') {
                deltaY -= 3;
            }

            texts.titel2_2.setDeltaY(deltaY);

            if (titel2_2 === '') {
                deltaY -= 3;
            }

            texts.abteilung1_2.setDeltaY(deltaY);

            if (abteilung1_2 === '') {
                deltaY -= 3;
            }

            texts.abteilung2_2.setDeltaY(deltaY);

            texts.strasse.setText(values.strasse);
            texts.ort.setText(values.ort);
            texts.strasse_2.setText(values.strasse);
            texts.ort_2.setText(values.ort);

            texts.email.setText(values.email);
            texts.email_2.setText(values.email);
            texts.fon.setText(t = values.fon);
            texts.fon_2.setText(t);
            texts.fax.setText(t = values.fax);
            texts.fax_2.setText(t);

            texts.mobil.setText(values.mobil);
            texts.mobil_2.setText(values.mobil);

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
            
            
            
            
            
            
            deltaY = 0;
            
            if (values.fon === '') {

                deltaY -= 3;
                texts._fon_2.setText('');
            } else {
                deltaY -= 0;
                texts._fon_2.setText('Phone');
            }
            
            texts.fax_2.setDeltaY(deltaY);
            texts._fax_2.setDeltaY(deltaY);
            if (values.fax === '') {

                deltaY -= 3;
                texts._fax_2.setText('');
            } else {
                deltaY -= 0;
                texts._fax_2.setText('Fax');
            }
            
            texts.mobil_2.setDeltaY(deltaY);
            texts._mobil_2.setDeltaY(deltaY);
            
            if (values.mobil === '') {

                deltaY -= 3;
                texts._mobil_2.setText('');
            } else {
                deltaY -= 0;
                texts._mobil_2.setText('Cell');
            }

            texts.email_2.setDeltaY(deltaY);

            if (values.email === '') {
                deltaY -= 3;
            }
            texts.internet_2.setDeltaY(deltaY);
            
            

        }

    };
    return r;
}