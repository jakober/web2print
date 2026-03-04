function handler() {
    return {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [],
                    pre_fon = 'T +49 (0) 7321 36 '
                    , pre_fax = 'F +49 (0) 7321 36 '
                    , pre_mobil = 'M +49 (0) '
                    , pre_mail = '',
                    maxlength = 250;

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


            var funktion, funktion2='', funktion3='';
            var mystring = values.funktion;


            funktion = values.funktion;
            texts.funktion.setText(funktion);
            texts.email.setText(values.email+pre_mail);
     //       texts.fax.setDeltaY(values.mobil === "" ? 3.2 : 0);
            texts.fon.setText(pre_fon + values.fon);


            if(values.mobil[0]+values.mobil[1]+values.mobil[2] == '+49'){
                values.mobil = values.mobil.substr(3);
            }

            if(values.mobil[0] == '0'){
                values.mobil = values.mobil.substr(1);
            }

            texts.mobil.setText(pre_mobil + values.mobil);
            texts.fax.setText(pre_fax + values.fax);
    //        texts.telefon.setText("Tel. " + phone);

            var titel = [];
            if(values.titel!=='') {
                titel.push(values.titel);
            }

            titel = titel.join(' ');





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

        }
    };
}