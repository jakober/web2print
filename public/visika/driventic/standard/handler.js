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
                texts.pre_name.setText(values.titel + " " + values.vorname);
                texts.name.setText(values.nachname);
            } else {
                texts.pre_name.setText("");
                texts.name.setText(name);
            }

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
            texts.funktion_line2.setDeltaY(aktitel === "" ? -3.2 : 0);

            var funktion, funktion2 = '', funktion3 = '';
            var mystring = values.funktion;

            if (values.aktitel_line2 !== "") {
                texts.funktion.setDeltaY(3.2);
                texts.funktion_line2.setDeltaY(3.2);
            }

            texts.funktion.setText(values.funktion);
            texts.aktitel_line2.setText(values.aktitel_line2);
            texts.funktion_line2.setText(values.funktion_line2);

            // ===================================================================
            // Gebündelter Block für Firma, Adresse und Kontaktinformationen
            // ===================================================================

            // Initialisierung der DeltaY-Variablen für die vertikale Positionierung
            var deltaYFirma = 0;
            var deltaYStrasse = 0;
            var deltaYStrassePost = 0;
            var deltaYPreOrt = 0;
            var deltaYOrt = 0;

            // Logik für Firma (Freitext oder Auswahl)
            var firma;
            if (values.firma === "(Free text)") {
                firma = values.firma_freitext;
                editor.showRow('firma_freitext');
            } else {
                firma = values.firma;
                editor.hideRow('firma_freitext');
            }

            // Logik für Ort und Land
            editor.hideRow('land_freitext');
            var land = "";
            var ort_und_land = "";
            if (values.land === "") {
                ort_und_land = values.ort;
            } else {
                if (values.land === "(Free text)") {
                    if (values.land_freitext !== "") {
                        land = values.land_freitext;
                        ort_und_land = values.ort + ", " + values.land_freitext;
                    } else {
                        land = "";
                        ort_und_land = values.ort;
                    }
                    editor.showRow('land_freitext');
                } else {
                    land = values.land;
                    ort_und_land = values.ort + ", " + values.land;
                    editor.hideRow('land_freitext');
                }
            }

            // Anpassung der vertikalen Position, wenn Telefon- oder Mobilnummern fehlen
            if (values.durchwahl == "" || values.mobil == "") {
                deltaYFirma += 3.2;
                deltaYStrasse += 3.2;
                deltaYPreOrt += 3.2;
                deltaYOrt += 3.2;
            } else {
                deltaYFirma = 0;
                deltaYStrasse = 0;
                deltaYStrassePost = 0;
                texts.strasse.setDeltaY(0);
                deltaYPreOrt = 0;
                deltaYOrt = 0;
            }



            // Vertikale Anpassung, wenn Ort+Land zu lang ist
            var ort_und_land_laenge = ort_und_land.length;
            if (ort_und_land_laenge > 35) {
                deltaYFirma -= 3.2;
                deltaYStrasse -= 3.2;
                deltaYPreOrt -= 3.2;
            }


            if (values.mobil !== "" && values.durchwahl !== "") {
                 texts.strasse_post.setDeltaY(-3.2);
            }else{
                 texts.strasse_post.setDeltaY(0);
            }


// *** NEUE LOGIK FÜR STRASSENUMBRUCH ***
const originalStrasse = values.strasse || '';
if (originalStrasse.length > 35) {
    const midPoint = Math.floor(originalStrasse.length / 2);

    // Finde das letzte Leerzeichen vor der Mitte und das erste danach
    const spaceBefore = originalStrasse.lastIndexOf(' ', midPoint);
    const spaceAfter = originalStrasse.indexOf(' ', midPoint);

    let splitPoint;

    if (spaceBefore === -1 && spaceAfter === -1) {
        // Wenn gar kein Leerzeichen existiert, wird hart in der Mitte getrennt
        splitPoint = midPoint;
    } else if (spaceBefore === -1) {
        // Wenn es nur danach ein Leerzeichen gibt
        splitPoint = spaceAfter;
    } else if (spaceAfter === -1) {
        // Wenn es nur davor ein Leerzeichen gibt
        splitPoint = spaceBefore;
    } else {
        // Wenn beide existieren, nimm das, welches näher an der Mitte ist
        if ((midPoint - spaceBefore) <= (spaceAfter - midPoint)) {
            splitPoint = spaceBefore;
        } else {
            splitPoint = spaceAfter;
        }
    }

    // Der String wird am final ermittelten Punkt aufgeteilt
    const strasseTeil1 = originalStrasse.substring(0, splitPoint);
    const strasseTeil2 = originalStrasse.substring(splitPoint).trim();

    texts.strasse.setText(strasseTeil1);
    texts.strasse_post.setText(strasseTeil2);

    // Y-Werte anpassen, da ein Umbruch stattfindet
    deltaYFirma -= 3.2;
    deltaYStrasse -= 3.2;

} else {
    // Wenn die Straße kurz genug ist, normal setzen und das zweite Feld leeren
    texts.strasse.setText(originalStrasse);
    texts.strasse_post.setText("");
}
// *** ENDE NEUE LOGIK ***


            // Mobilfunknummer und zugehöriges Präfix setzen
            texts.mobil.setText(values.mobil);
            if (values.mobil === "") {
                texts.pre_mobil.setText("");
            } else {
                texts.pre_mobil.setText("Mobile");
            }



            // Telefonnummer (Durchwahl) und zugehöriges Präfix setzen
            var phone = values.durchwahl;
            texts.telefon.setText(phone);
            if (values.durchwahl === "") {
                texts.pre_telefon.setText("");
            } else {
                texts.pre_telefon.setText("Phone");
            }





            // Vertikale Anpassung der Telefonzeile, wenn mobil leer ist
            if (values.mobil === "") {
                texts.telefon.setDeltaY(3.2);
                texts.pre_telefon.setDeltaY(3.2);
            } else {
                texts.telefon.setDeltaY(0);
                texts.pre_telefon.setDeltaY(0);
            }

            // E-Mail-Logik für lange Adressen
            var emailRaw = (values.email || '').trim();
            if (emailRaw.length >= 40) {
                var atPos = emailRaw.lastIndexOf('@');
                if (atPos > 0 && atPos < emailRaw.length - 1) {
                    var localPartWithAt = emailRaw.slice(0, atPos + 1); // inkl. '@'
                    var domainPart = emailRaw.slice(atPos + 1);
                    texts.pre_mail && texts.pre_mail.setText(localPartWithAt);
                    texts.email.setText(domainPart);
                }
            } else {
                 // Fallback: normale Anzeige, nichts trennen
                texts.email.setText(emailRaw);
                texts.pre_mail && texts.pre_mail.setText("");
            }

            // Finale Zuweisung der Texte und Positionen für den Adressblock
            texts.firma.setText(firma);
            // texts.strasse.setText(values.strasse); // DIESE ZEILE WURDE DURCH DIE NEUE LOGIK ERSETZT

            if (ort_und_land_laenge > 35) {
                texts.pre_ort.setText(values.ort + ", ");
                texts.ort.setText(land);
            } else {
                texts.pre_ort.setText("");
                texts.ort.setText(ort_und_land);
            }

            texts.firma.setDeltaY(deltaYFirma);
            texts.strasse.setDeltaY(deltaYStrasse);
            texts.ort.setDeltaY(deltaYOrt);
            texts.pre_ort.setDeltaY(deltaYPreOrt);


            // ===================================================================
            // Ende des gebündelten Blocks
            // ===================================================================

            var titel = [];
            if (values.titel !== '') {
                titel.push(values.titel);
            }
            if (values.aktitel !== '') {
                titel.push(values.aktitel.replace(/\s+/g, "\u00A0"));
            }
            titel = titel.join(' ');

            var qrcode = "BEGIN:VCARD\n" +
                "VERSION:3.0\n" +
                "N:" + values.nachname + ";" + values.vorname + ";;;\n" +
                "FN:" + name + "\n" +
                "ORG:Ingenics AG\n";


            var titeltext = "";
            if (titel !== '' && titel != "(Freitext)") {
                titeltext = titel + ' / ';
            } else {
                titeltext = values.aktitel_freitext + ' / ';
            }
            if (funktion !== '') {
                titeltext = titeltext + funktion;

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