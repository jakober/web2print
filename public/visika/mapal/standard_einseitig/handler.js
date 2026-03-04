function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {

            // 1. STANDORT-LOGIK (Die "Datenbank" für deine Standorte)
        var standortDaten = {
            "Aalen": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Obere Bahnstraße",
                ort: "73431 Aalen, Germany"
            },
            "Eppingen": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Jakob-Dieffenbacher-Str. 8",
                ort: "75031 Eppingen, Germany"
            },
            "Pforzheim": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Heilbronner Str. 25",
                ort: "75179 Pforzheim, Germany"
            },
            "Altenstadt": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Im Tal 12",
                ort: "89281 Altenstadt, Germany"
            },
            "Meiningen-Dreißigacker": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Gleimershäuser Straße 5a",
                ort: "98617 Meiningen-Dreißigacker, Germany"
            },
            "Ehrenfriedersdorf": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Gewerbegebiet an der B95, 2a",
                ort: "09427 Ehrenfriedersdorf, Germany"
            },
            "Winterlingen": {
                firma: "MAPAL Dr. Kress SE & Co. KG",
                strasse: "Ebinger Straße 115",
                ort: "72474 Winterlingen, Germany"
            },
            "Toulouse": {
                firma: "MAPAL France S.A.S.",
                strasse: "12 Rue Marius Tercé",
                ort: "31300 Toulouse, France"
            },
            "Le Chambon Feugerolles": {
                firma: "MAPAL France S.A.S.",
                strasse: "Z.I. La Siladière",
                ort: "42500 Le Chambon Feugerolles, France"
            },
            "Vigneux-de-Bretagne": {
                firma: "MAPAL France S.A.S.",
                strasse: "6 Rue Marius Berliet",
                ort: "44360 Vigneux-de-Bretagne, France"
            },
            "Villepinte": {
                firma: "MAPAL France S.A.S.",
                strasse: "Bat. Euler, 33 Rue des Vanesses",
                ort: "93420 Villepinte, France"
            },
            "Gessate": {
                firma: "MAPAL Italia S.r.l.",
                strasse: "Via Monza 82",
                ort: "20060 Gessate (MI), Italia"
            },
            "Komorniki": {
                firma: "MAPAL Narzędzia Precyzyjne z o.o.",
                strasse: "ul. Polna 121",
                ort: "62052 Komorniki, Poland"
            },
            "Mladá Boleslav": {
                firma: "MAPAL C&S",
                strasse: "Bezděčín 126",
                ort: "293 01 Mladá Boleslav, Czech Republic"
            },
            "Codlea Brasov": {
                firma: "MAPAL Romania S.R.L.",
                strasse: "Noua Street No. 27",
                ort: "505100 Codlea Brasov, Romania"
            }
        };
            // Prüfen, ob ein Standort gewählt wurde und die Texte entsprechend setzen
            var gewaehlterStandort = values.standort;
            if (gewaehlterStandort && standortDaten[gewaehlterStandort]) {
                if (texts.firma) texts.firma.setText(standortDaten[gewaehlterStandort].firma);
                if (texts.strasse) texts.strasse.setText(standortDaten[gewaehlterStandort].strasse);
                if (texts.ort) texts.ort.setText(standortDaten[gewaehlterStandort].ort);
            } else {
                // Felder leeren, falls (noch) nichts ausgewählt ist (der Bindestrich aus dem Select)
                if (texts.firma) texts.firma.setText("");
                if (texts.strasse) texts.strasse.setText("");
                if (texts.ort) texts.ort.setText("");
            }

            // 2. NAMENS-LOGIK
            var n = [];
            if (values.titel && values.titel !== '') {
                n.push(values.titel);
            }
            if (values.vorname && values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname && values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            if (texts.name) texts.name.setText(name);
            values.fullName = name;

            // 3. TEXTE ZUWEISEN
            // 3. TEXTE ZUWEISEN & TELEFON-KOMBINATION
            var telefonZeile = "";
            var hatFon = (values.fon && values.fon !== '');
            var hatMobil = (values.mobil && values.mobil !== '');

            if (hatFon && hatMobil) {
                // Beide gefüllt -> Trennung mit ' | '
                telefonZeile = values.fon + ' | ' + values.mobil;
            } else if (hatFon) {
                // Nur Festnetz
                telefonZeile = values.fon;
            } else if (hatMobil) {
                // Nur Mobil
                telefonZeile = values.mobil;
            }

            // Den kombinierten String dem Textfeld "fon" in der PDF-Vorschau zuweisen
            if (texts.fon) texts.fon.setText(telefonZeile);

            if (texts.position1) texts.position1.setText(values.position1);
            if (texts.titel2) texts.titel2.setText(values.titel2);
            if (texts.titel3) texts.titel3.setText(values.titel3);

            if (values.email !== '') {
                if (texts.email) texts.email.setText(values.email);
            } else {
                if (texts.email) texts.email.setText('');
            }

            // 4. VCARD & QR-CODE LOGIK
            var vCard = 'BEGIN:VCARD\nVERSION:3.0\n';
            var vCardTitel = (values.titel) ? values.titel : '';

            vCard += 'N:' + values.nachname + ';' + values.vorname + ';;' + vCardTitel + ';\n';
            vCard += 'FN:' + values.fullName + '\n';

            if (hatFon) {
                vCard += 'TEL;TYPE=WORK,VOICE:' + values.fon + '\n';
            }
            if (hatMobil) {
                vCard += 'TEL;TYPE=CELL,VOICE:' + values.mobil + '\n';
            }
            if (values.email !== '') {
                vCard += 'EMAIL;TYPE=INTERNET,WORK:' + values.email + '\n';
            }
            if (values.position1 !== '') {
                vCard += 'TITLE:' + values.position1 + '\n';
            }

            // Optionale Erweiterung: Die gewählte Firmenadresse auch in die vCard packen
            if (gewaehlterStandort && standortDaten[gewaehlterStandort]) {
                var adr = standortDaten[gewaehlterStandort];
                vCard += 'ORG:' + adr.firma + '\n';
                var plzOrt = adr.ort.split(' ');
                var plz = plzOrt[0] || '';
                var stadt = plzOrt[1] ? plzOrt[1].replace(',', '') : '';
                vCard += 'ADR;TYPE=WORK:;;' + adr.strasse + ';' + stadt + ';;' + plz + ';Germany\n';
            }

            vCard += 'END:VCARD';

            if (qrcodes && qrcodes.length > 0) {
                qrcodes[0].code = vCard;
            }
        }
    };
    return r;
}