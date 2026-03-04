function handler() {
    return {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [];

			var titel = values.titel;
			var vorname = values.vorname;
			var nachname = values.nachname;
			values.fullName = vorname+' '+nachname;
			if (titel === "") {
				texts.fullname.setText(vorname + ' ' + nachname);
			} else {
				texts.fullname.setText(titel + ' ' + vorname + ' ' + nachname);
			}
			
            var aktitel;
            if (values.aktitel === "(Freitext)") {
                aktitel = values.aktitel_freitext;
                editor.showRow('aktitel_freitext');
            } else {
                aktitel = values.aktitel;
                editor.hideRow('aktitel_freitext');
            }
            texts.aktitel.setText(aktitel);

            texts.mobil.setText(values.mobil);
            texts.email.setText(values.email);
            texts.email.setDeltaY(values.mobil === "" ? 3.2 : 0);

            var phone = '+49 123 456789-' + (values.durchwahl === '' ? 0 : values.durchwahl);
            texts.telefon.setText("Tel. " + phone);      
        }
    };
}