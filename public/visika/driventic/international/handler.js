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
            if (name.length > 24) {
                texts.pre_name.setText(values.titel+" "+values.vorname);
                texts.name.setText(values.nachname);
            }else{
                texts.pre_name.setText("");
                texts.name.setText(name);
            }

            values.fullName = name;

            var aktitel;
            if (values.aktitel === "(Freitext)" || values.aktitel === "(Free text)") {
                aktitel = values.aktitel_freitext;
                editor.showRow('aktitel_freitext');
            } else {
                aktitel = values.aktitel;
                editor.hideRow('aktitel_freitext');
            }
            texts.aktitel.setText(aktitel);
            texts.funktion.setDeltaY(aktitel === "" ? -4 : 0);
            texts.funktion2.setDeltaY(aktitel === "" ? -4 : 0);

            var funktion, funktion2='', funktion3='';
            var mystring = values.funktion;

            if (values.funktion === "(Freitext)" || values.aktitel === "(Free text)") {
                funktion = values.funktion_freitext;
                editor.showRow('funktion_freitext');
            } else if(mystring.match('%')) {
            	var mystring2 = mystring.split('%');
            	funktion = mystring2[0];
                funktion2 = mystring2[1];

                var funktion_gesamt = funktion+' '+funktion2;
            } else {
                funktion = values.funktion;
                editor.hideRow('funktion_freitext');
            }

            texts.funktion.setText(funktion);
            texts.funktion2.setText(funktion2);







            texts.mobil.setText(values.mobil);


            var emailRaw = (values.email || '').trim();
            if (emailRaw.length >= 40) {
                var atPos = emailRaw.lastIndexOf('@');
                if (atPos > 0 && atPos < emailRaw.length - 1) {
                    var localPartWithAt = emailRaw.slice(0, atPos + 1); // inkl. '@'
                    var domainPart = emailRaw.slice(atPos + 1);
                    texts.pre_mail && texts.pre_mail.setText(localPartWithAt);
                    texts.email.setText(domainPart);
                    return;
                }
            }
            // Fallback: normale Anzeige, nichts trennen
            texts.email.setText(emailRaw);
            texts.pre_mail && texts.pre_mail.setText("");





       //     texts.email.setDeltaY(values.mobil === "" ? -4 : 0);


/*            texts.mobil.setDeltaY(0);*/
     /*       texts.mobil.setDeltaY(values.email === "" ? 4 : 0);*/

            var phone = values.durchwahl;


            if(values.mobil === ""){
                texts.telefon.setDeltaY(3.2);
                texts.pre_telefon.setDeltaY(3.2);
            }else{
                texts.telefon.setDeltaY(0);
                texts.pre_telefon.setDeltaY(0);
            }



             var deltaYFirma = 0;

            var deltaYStrasse = 0;

            var deltaYOrt = 0;

            var deltaYPreOrt = 0;



            if(values.durchwahl == "" || values.mobil == ""){
                deltaYFirma = deltaYFirma+3.2;
                deltaYStrasse = deltaYStrasse+3.2;
                deltaYPreOrt = deltaYPreOrt+3.2;
                deltaYOrt = deltaYOrt+3.2;
            }else{
                deltaYFirma = 0;
                deltaYStrasse = 0;
                texts.strasse.setDeltaY(0);
                deltaYPreOrt = 0;
                deltaYOrt = 0;
            }


            texts.telefon.setText(phone);


            if (values.durchwahl === "") {
                texts.pre_telefon.setText("");
              //  deltaY += 2.9;
            } else {
                texts.pre_telefon.setText("Phone");
            }

            if (values.mobil === "") {
                    texts.pre_mobil.setText("");
                //  deltaY += 2.9;
            } else {
                    texts.pre_mobil.setText("Mobile");
            }

            var titel = [];
            if(values.titel!=='') {
                titel.push(values.titel);
            }
            if(values.aktitel!=='') {
                titel.push(values.aktitel.replace(/\s+/g,"\u00A0"));
            }
            titel = titel.join(' ');

            var firma;
            if (values.firma === "(Free text)") {
                firma = values.firma_freitext;
                editor.showRow('firma_freitext');
            } else {
                firma = values.firma;
                editor.hideRow('firma_freitext');
            }



            editor.hideRow('land_freitext');

            var land = "";
            var ort_und_land = "";
            var ort_und_land_laenge = ort_und_land.length;

            if (values.land === "") {
                ort_und_land = values.ort;
            }else{

                if (values.land === "(Free text)") {
                    if(values.land_freitext!=""){
                        land = values.land_freitext;
                        ort_und_land  = values.ort+", "+values.land_freitext;
                    }else{
                        land = "";
                        ort_und_land = values.ort;
                    }
                    editor.showRow('land_freitext');
                } else {
                    land = values.land;
                    ort_und_land = values.ort+", "+values.land;
                    editor.hideRow('land_freitext');
                }
            }

           var ort_und_land_laenge = ort_und_land.length;
           if(ort_und_land_laenge > 35){
                deltaYFirma = deltaYFirma-3.2;
                deltaYStrasse = deltaYStrasse-3.2;

                deltaYOrt = deltaYOrt;
           }else{
                deltaYStrasse = deltaYStrasse;
                deltaYOrt = deltaYOrt;
           }



           texts.firma.setDeltaY(deltaYFirma);
           texts.strasse.setDeltaY(deltaYStrasse);
           texts.ort.setDeltaY(deltaYOrt);
           texts.pre_ort.setDeltaY(deltaYPreOrt);


           if(ort_und_land_laenge > 35){

               texts.pre_ort.setText(values.ort+", ");
                texts.ort.setText(land);
           }else{

               texts.pre_ort.setText("");
                texts.ort.setText(ort_und_land);
           }


           texts.firma.setText(firma);
           texts.strasse.setText(values.strasse);


            var qrcode = "BEGIN:VCARD\n" +
                    "VERSION:3.0\n" +
                    "N:" + values.nachname + ";" + values.vorname + ";;;\n" +
                    "FN:" + name + "\n" +
                    "ORG:Ingenics AG\n";


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
            /*qrcodes[0].code = qrcode;*/
        }
    };
}