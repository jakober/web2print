function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [], t;

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

            var titel1_2, titel2_2, abteilung1_2;
            texts.titel1_2.setText(titel1_2=values.titel1_2);
            texts.titel2_2.setText(titel2_2=values.titel2_2);

            texts.abteilung1_2.setText(abteilung1_2=values.abteilung1_2);
            texts.abteilung2_2.setText(values.abteilung2_2);

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

            texts.firma.setText(values.firma);
            texts.firma_2.setText(values.firma);
            texts.strasse.setText(values.strasse);
            texts.ort.setText(values.ort);
            texts.strasse_2.setText(values.strasse);
            texts.ort_2.setText(values.ort);

            texts.email.setText(values.email);
            texts.email_2.setText(values.email);
            texts.fon.setText(t = values.fon);
            texts._fon.setText(values._fon);
            texts.fon_2.setText(t);
            texts._fon_2.setText(values._fon_2);
            texts.fax.setText(t = values.fax);
            texts.fax_2.setText(t);
            texts._fax.setText(values._fax);
            texts._fax_2.setText(values._fax_2);

            texts.mobil.setText(values.mobil);
            texts.mobil_2.setText(values.mobil);
			
			
			
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
			
			texts.internet.setDeltaY(deltaY);
			texts.internet.setText(values.internet);
			
			
			var deltaY = 0;
            if (values._fon_2 === '') {
                deltaY -= 3;
                texts.fon_2.setText('');
            }else{
            	deltaY -= 0;
            	texts.fon_2.setText(values.fon);
            }
			texts.fax_2.setDeltaY(deltaY);
            texts._fax_2.setDeltaY(deltaY);

            if (values._fax_2 === '') {
                deltaY -= 3;
                texts.fax_2.setText('');
            }else{
            	deltaY -= 0;
            	texts.fax_2.setText(values.fax);
            }

			texts.mobil_2.setDeltaY(deltaY);
			texts._mobil_2.setDeltaY(deltaY)
			
			if (values.mobil === '') {
                deltaY -= 3;
                texts._mobil_2.setText('');
            }else{
            	deltaY -= 0;
                texts._mobil_2.setText(values._mobil_2);
            }
			
			texts.email_2.setDeltaY(deltaY);
			
			if (values.email === '') {
                deltaY -= 3;
            }else{
            	 deltaY -=0;
            }
			
			texts.internet_2.setDeltaY(deltaY);
			texts.internet_2.setText(values.internet);
			


        }


    };
    return r;
}