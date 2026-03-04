function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var vorwahl = "07321 91899-";

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

            var stelle = "Zweigstelle Schnaitheim";
            var strasse = "Brenzlesstr. 8";
            var plz = "89520";
            var ort = "Heidenheim";

            // Adresse direkt einfügen
            var adresse = stelle + ", " + strasse + ";" + plz + ";" + ort;


            function replaceUmlauts(str) {
                return str.normalize("NFC") // Stellt sicher, dass Zeichen in normalisierter Form vorliegen
                    .replace(/ä/g, 'ae')
                    .replace(/Ä/g, 'Ae')
                    .replace(/ö/g, 'oe')
                    .replace(/Ö/g, 'Oe')
                    .replace(/ü/g, 'ue')
                    .replace(/Ü/g, 'Ue')
                    .replace(/ß/g, 'ss')
                    .replace(/à/g, 'a')
                    .replace(/á/g, 'a')
                    .replace(/é/g, 'e')
                    .replace(/è/g, 'e');
            }

            // Werte mit ersetzten Umlauten
            var nameNoUmlaut = replaceUmlauts(values.name);
            var fullNameNoUmlaut = replaceUmlauts(values.fullName);
            var titelNoUmlaut = replaceUmlauts(values.titel);
            var titel2NoUmlaut = replaceUmlauts(values.titel2);
            var funktionNoUmlaut = replaceUmlauts(values.funktion);
            var funktion2NoUmlaut = replaceUmlauts(values.funktion2);
            var vollmachtNoUmlaut = replaceUmlauts(values.vollmacht);
            var emailNoUmlaut = replaceUmlauts(values.email);
            var internetNoUmlaut = replaceUmlauts(values.internet);
            var adresseNoUmlaut = replaceUmlauts(adresse);
            var orgNoUmlaut = replaceUmlauts("Heidenheimer Volksbank eG");

            var vCard = 'BEGIN:VCARD\nVERSION:3.0\n';

            // Name hinzufügen
            vCard += 'N:' + nameNoUmlaut + ';;;;\n';
            vCard += 'FN:' + fullNameNoUmlaut + '\n';

            // Telefonnummern hinzufügen
            if (values.telefon !== '') {
                vCard += 'TEL;TYPE=WORK,VOICE:' + vorwahl + values.telefon + '\n';
            }
            if (values.mobil !== '') {
                vCard += 'TEL;TYPE=CELL,VOICE:' + values.mobil + '\n';
            }

            // E-Mail hinzufügen
            if (values.email !== '') {
                vCard += 'EMAIL;TYPE=INTERNET,WORK:' + emailNoUmlaut + '\n';
            }

            var titles = [];
            if (values.titel !== '') {
                titles.push(titelNoUmlaut);
            }
            if (values.titel2 !== '') {
                titles.push(titel2NoUmlaut);
            }
            if (values.funktion !== '') {
                titles.push(funktionNoUmlaut);
            }
            if (values.funktion2 !== '') {
                titles.push(funktion2NoUmlaut);
            }
            if (titles.length > 0) {
                vCard += 'TITLE:' + titles.join(', ') + '\n';
            }

            vCard += 'ORG:' + orgNoUmlaut + '\n';


            if (values.vollmacht !== '') {
                vCard += 'NOTE:Vollmacht: ' + vollmachtNoUmlaut + '\n';
            }

            // Adresse hinzufügen
            vCard += 'ADR;TYPE=WORK:;;' + adresseNoUmlaut + ';;;\n';

            // URL hinzufügen
            if (values.internet !== '') {
                vCard += 'URL:' + internetNoUmlaut + '\n';
            }

            vCard += 'END:VCARD';

            // Setzen des QR-Code-Inhalts
            qrcodes[0].code = vCard;
        }
    };
    return r;
}