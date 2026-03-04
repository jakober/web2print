function handler() {
    return {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [];

            if (values.titel !== '') {
                n.push(values.titel);
            }
            if (values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            texts.name.setText(name);
            values.fullName = name;

            var aktitel;
            if (values.aktitel === "(Freitext)") {
                aktitel = values.aktitel_freitext;
                editor.showRow('aktitel_freitext');
            } else {
                aktitel = values.aktitel;
                editor.hideRow('aktitel_freitext');
            }
            texts.aktitel.setText(aktitel);
            texts.funktion.setDeltaY(aktitel === "" ? -3.2 : 0);
            texts.funktion2.setDeltaY(aktitel === "" ? -3.2 : 0);

            var funktion, funktion2='', funktion3='';
            var mystring = values.funktion;
            
            if (values.funktion === "(Freitext)") {
                funktion = values.funktion_freitext;
                editor.showRow('funktion_freitext');
            } else if(mystring.match('%')) {
            	var mystring2 = mystring.split('%');
            	funktion = mystring2[0];
                funktion2 = mystring2[1];

                
            } else {
                funktion = values.funktion;
                editor.hideRow('funktion_freitext');
            }
            texts.funktion.setText(funktion);
            texts.funktion2.setText(funktion2);


            var rolle = values.rolle;
            if (values.rolle === "(Freitext)") {
                rolle = values.rolle_freitext;
                editor.showRow('rolle_freitext');
            }else{
                rolle = values.rolle;
                editor.hideRow('rolle_freitext');
            }


            var deltaRolleY = 0;
            if(aktitel === ""){
                deltaRolleY = deltaRolleY-3.2;
            }
            if(funktion === ""){
                deltaRolleY = deltaRolleY-3.2;
            }
            if(funktion2 !== ""){
                deltaRolleY = deltaRolleY+3.2;
            }
            texts.rolle.setDeltaY(deltaRolleY);
            texts.rolle.setText(rolle);
			
            texts.mobil.setText(values.mobil);
            texts.email.setText(values.email);
            texts.email.setDeltaY(values.mobil === "" ? 3.2 : 0);

            var phone = '+49 731 1405340 ' + (values.durchwahl === '' ? 700 : values.durchwahl);
            texts.telefon.setText("Tel. " + phone);

            var qrcode = "BEGIN:VCARD\n" +
                    "VERSION:3.0\n" +
                    "N:" + values.nachname + ";" + values.vorname + ";;;\n" +
                    "FN:" + name + "\n" +
                    "ORG:Ingenics Services GmbH\n";

            if (funktion) {
                qrcode += "TITLE:" + funktion + "\n";
            }

            if (values.titel !== "") {
                qrcode += "TITLE:" + values.titel + "\n";
            }
            qrcode += "EMAIL;type=INTERNET;type=WORK:" + values.email + "\n" +
                    "TEL;type=CELL:" + values.mobil + "\n" +
                    "TEL;type=WORK:" + phone + "\n" +
                    "ADR;type=WORK:;;Schillerstrasse 1/15;Ulm;;89077;Germany\n" +
                    "END:VCARD";
            /*qrcodes[0].code = qrcode;*/
        }
    };
}