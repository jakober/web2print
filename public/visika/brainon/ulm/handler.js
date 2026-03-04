function handler() {
    return {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [];

            if(values.titel!=='') {
                n.push(values.titel);
            }
            if(values.vorname!=='') {
                n.push(values.vorname);
            }
            if(values.nachname!=='') {
                n.push(values.nachname);
            }
            var name =n.join(' ');
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

            if (values.funktion === "(Freitext)") {
                texts.funktion.setText(values.funktion_freitext);
                editor.showRow('funktion_freitext');
            } else {
                texts.funktion.setText(values.funktion);
                editor.hideRow('funktion_freitext');
            }
            texts.mobil.setText(values.mobil===''?'':'Mobil '+values.mobil);
            texts.email.setText(values.email);

            var d = "Tel. +49 731 1405340-";
            if(values.durchwahl==="") {
                d+="700";
            } else {
                d+=values.durchwahl;
            }
            texts.telefon.setText(d);
        }
    };
}