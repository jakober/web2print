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
			texts.funktion3.setDeltaY(aktitel === "" ? -3.2 : 0);

            var funktion, funktion2='', funktion3='';
            var mystring = values.funktion;
            if (values.funktion === "(Freitext)") {
                funktion = values.funktion_freitext;
                funktion2 = values.funktion_freitext2;
                editor.showRow('funktion_freitext');
                editor.showRow('funktion_freitext2');
            } else if(mystring.match('%')) {
            	var mystring2 = mystring.split('%');
            	funktion = mystring2[0];
                funktion2 = mystring2[1];
                if(mystring2[2]){
                funktion3 = mystring2[2];
                }
                
            } else {
                funktion = values.funktion;
                editor.hideRow('funktion_freitext');
                editor.hideRow('funktion_freitext2');
            }
            texts.funktion.setText(funktion);
            texts.funktion2.setText(funktion2);
            texts.funktion3.setText(funktion3);

            texts.mobil.setText(values.mobil);
            texts.email.setText(values.email);
            texts.email.setDeltaY(values.mobil === "" ? 3.2 : 0);

            var phone = '+49 731 93680 ' + (values.durchwahl === '' ? 0 : values.durchwahl);
            texts.telefon.setText("Tel. " + phone);

			var titel = [];
			if(values.titel!=='') {
				titel.push(values.titel);
			}
			if(values.aktitel!=='') {
				titel.push(values.aktitel.replace(/\s+/g,"\u00A0"));
			}
			titel = titel.join(' ');

            var qrcode = "BEGIN:VCARD\n" +
                    "VERSION:3.0\n" +
                    "N:" + values.nachname + ";" + values.vorname + ";;;\n" +
                    "FN:" + name + "\n" +
                    "ORG:Ingenics Engineering GmbH\n";

            var titeltext = "";
			if(titel!=='' && titel!="(Freitext)"){
				titeltext = titel+' / ';
			}else{
				titeltext = values.aktitel_freitext+' / ';
			}
			
            if (funktion!=='') {
            	titeltext = titeltext+funktion;
                
            }
            if (funktion2!=='') {
            	titeltext = titeltext+' / '+funktion2;
                
            }
            if (funktion3!=='') {
            	titeltext = titeltext+' / '+funktion3;
                
            }
			qrcode += "TITLE:" + titeltext + "\n";


            qrcode += "EMAIL;type=INTERNET;type=WORK:" + values.email + "\n" +
                    "TEL;type=CELL:" + values.mobil + "\n" +
                    "TEL;type=WORK:" + phone + "\n" +
                    "ADR;type=WORK:;;Schillerstrasse 1/15;Ulm;;89077;Germany\n" +
                    "END:VCARD";

            qrcodes[0].code = qrcode;
        }
    };
}