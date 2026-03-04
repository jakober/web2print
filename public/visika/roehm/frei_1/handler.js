function handler(editor) {

    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [];

            if (values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            texts.name.setText(name);
            values.fullName = name;

            var titel1, titel2, abteilung1;
            texts.titel1.setText(titel1=values.titel1);
            texts.titel2.setText(titel2=values.titel2);

            texts.abteilung1.setText(abteilung1=values.abteilung1);
            texts.abteilung2.setText(values.abteilung2);

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

            texts.firma.setText(values.firma);
            texts.strasse.setText(values.strasse);
            texts.ort.setText(values.ort);

            texts.email.setText(values.email);
            texts.fon.setText(values.fon);
            texts._fon.setText(values._fon);
            texts.fax.setText(values.fax);
            texts._fax.setText(values._fax);

            texts.mobil.setText(values.mobil);
            var deltaY = 0;
            if (values._fon === '') {
                deltaY -= 3;
                texts.fon.setText('');
            }else{
            	deltaY -= 0;
            	texts.fon.setText(values.fon);
            }
			texts.fax.setDeltaY(deltaY);
            texts._fax.setDeltaY(deltaY);

            if (values._fax === '') {
                deltaY -= 3;
                texts.fax.setText('');
            }else{
            	deltaY -= 0;
            	texts.fax.setText(values.fax);
            }

			texts.mobil.setDeltaY(deltaY);
			texts._mobil.setDeltaY(deltaY)
			
			if (values.mobil === '') {
                deltaY -= 3;
                texts._mobil.setText('');
            }else{
            	deltaY -= 0;
                texts._mobil.setText(values._mobil);
            }
			
			texts.email.setDeltaY(deltaY);
			
			if (values.email === '') {
                deltaY -= 3;
            }else{
            	 deltaY -=0;
            }
			texts.internet.setText(values.internet);
			texts.internet.setDeltaY(deltaY);

        }


    };
    return r;
}