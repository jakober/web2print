function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var vorwahl = "07321 92532-";

            texts.name.setText(values.name);
            values.fullName = values.name;

            var deltaY = 0;

            texts.titel.setText(values.titel);
            if (values.titel === '') {
                deltaY -= 2.9;
            }
            texts.titel.setDeltaY(deltaY);

            texts.titel2.setText(values.titel2);
            if (values.titel2 === '') {
                deltaY -= 2.9;
            }
            texts.titel2.setDeltaY(deltaY);

            texts.vollmacht.setText(values.vollmacht);
            if (values.vollmacht === '') {
                deltaY -= 2.9;
            }
            texts.vollmacht.setDeltaY(deltaY);

            texts.funktion.setText(values.funktion);
            if (values.funktion === '') {
                deltaY -= 2.9;
            }
            texts.funktion.setDeltaY(deltaY);

            texts.funktion2.setText(values.funktion2);
            if (values.funktion2 === '') {
                deltaY -= 2.9;
            }
            texts.funktion2.setDeltaY(deltaY);

            var fon = vorwahl + values.telefon;
            texts.telefon.setText(fon);
            var fax = values.telefax !== '' ? vorwahl+values.telefax : '';
            texts.telefax.setText(fax);
            texts.internet.setText(values.internet);
            texts.email.setText(values.email);
            texts.mobil.setText(values.mobil);

            values.fullName = values.name;
            deltaY=0;
            if (values.mobil === "") {
                texts._mobil.setText("");
                deltaY = 2.9;
                texts.telefon.setDeltaX(0);
                texts.telefax.setDeltaX(0);
            } else {
                texts._mobil.setText("Mobil");
                texts.telefon.setDeltaX(2.5);
                texts.telefax.setDeltaX(2.5);
            }

            texts.telefax.setDeltaY(deltaY);
            texts._telefax.setDeltaY(deltaY);

            if (values.telefax === "") {
                texts._telefax.setText("");
                deltaY += 2.9;
            } else {
                texts._telefax.setText("Fax");
            }

            texts._telefon.setDeltaY(deltaY);
            texts.strasse.setDeltaY(deltaY);
            texts.ort.setDeltaY(deltaY);
            texts.telefon.setDeltaY(deltaY);

            texts.stelle.setDeltaY(deltaY);
        }
    };
    return r;
}