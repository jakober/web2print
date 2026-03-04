function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [],
                    pre_fon = '', pre_fax = pre_fon,
                    maxlength = 250;


            if (values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            texts.name.setText(name);
            values.fullName = name;

            texts.position1.setText(values.position1);


            var titel2 = '', abteilung1, abteilung2 = '';

            titel2 = values.titel2;
            texts.titel2.setText(titel2);

            var titel3 = ''
            titel3 = values.titel3;
            texts.titel3.setText(titel3);

            var deltaY = 0;

            texts.titel2.setDeltaY(deltaY);
            texts.titel3.setDeltaY(deltaY);
/*            if (titel2 === '') {
                deltaY -= 3;
            }*/

            texts.titel2.setDeltaY(deltaY);





            var deltaY = 0;



            var deltaYFon = 0;
            var deltaYMobil = 0;
            var deltaYEmail = 0;
            var deltaYOrt = 0;
            var deltaYFirma = 0;
            var deltaYQR = 0;
            // Überprüfen Sie, welche Felder leer sind, und passen Sie deltaY für die darüber liegenden Felder an
            if (values.email === "") {
                deltaYFon += 3.1;
                deltaYMobil += 3.1;
                deltaYOrt += 3.1;
                deltaYFirma += 3.1;

            }
            if (values.mobil === "" ) {
                deltaYFon += 3.1;
                deltaYOrt += 3.1;
                deltaYFirma += 3.1;
            }
            if(values.fon === ""){
                deltaYOrt += 3.1;
                deltaYFirma += 3.1;
            }



            // Setzen Sie die Texte und aktualisieren Sie deltaY für jedes Feld
            if (values.fon !== '') {
                texts.fon.setText(values.fon);
                texts._fon.setText('Phone');
                texts.fon.setDeltaY(deltaYFon);
                texts._fon.setDeltaY(deltaYFon);
            } else {
                texts.fon.setText('');
                texts._fon.setText('');
            }

            if (values.mobil !== '') {
                texts.mobil.setText(values.mobil);
                texts._mobil.setText('Mobile');
                texts.mobil.setDeltaY(deltaYMobil);
                texts._mobil.setDeltaY(deltaYMobil);
            } else {
                texts.mobil.setText('');
                texts._mobil.setText('');
            }

            if (values.email !== '') {
                texts.email.setText(values.email);
                texts.email.setDeltaY(deltaYEmail);  // deltaYEmail ist immer 0 in diesem Fall
            } else {
                texts.email.setText('');
            }


            if (texts.ort) {
                texts.ort.setDeltaY(deltaYOrt);
            }
            if (texts.roehm) {
                texts.roehm.setDeltaY(deltaYFirma);
            }

            var e = values.mobil.length > 0;

           var d = e ? -4 : 0, dy = d;
/*            texts.fon.setDeltaY(dy);
            texts._fon.setDeltaY(dy);*/
            dy+=d;


 /*           texts.mobil.setDeltaY(dy);
            texts._mobil.setDeltaY(dy);*/

            dy+=d;
            if(values.mobil==='') {
                dy-=1;
            }

            if (titel2 === '') {
                deltaY -= 3;
            }

            if (values.email != "") deltaYQR -= 3.1;
            if (values.mobil != "") deltaYQR -= 3.1;
            if (values.fon != "") deltaYQR -= 3.1;

            var qrcode = "";
            var url = "https://aluca-world.com";

            // Generieren des vCard-Strings
            var vCard = 'BEGIN:VCARD\nVERSION:3.0\n';
            vCard += 'N:' + values.nachname + ';' + values.vorname + ';;;\n';
            vCard += 'FN:' + values.fullName + '\n';
            if (values.fon !== '') {
                vCard += 'TEL;TYPE=WORK,VOICE:' + values.fon + '\n';
            }
            if (values.mobil !== '') {
                vCard += 'TEL;TYPE=CELL,VOICE:' + values.mobil + '\n';
            }
            if (values.email !== '') {
                vCard += 'EMAIL;TYPE=INTERNET,WORK:' + values.email + '\n';
            }
            if (values.position1 !== '') {
                vCard += 'TITLE:' + values.position1 + '\n';
            }
            vCard += 'END:VCARD';

            // Setzen Sie den vCard-String als den neuen Inhalt des QR-Codes
            qrcodes[0].code = vCard;

            /*qrcodes[0].code = url;*/

              // Ändert den Y-Wert um 10 Einheiten nach unten

      /*      texts.email.setDeltaY(dy);*/
       /*     if(values.email==='') {
                dy-=3;
            }*/
        }

    };
    return r;
}