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
            var deltaY = 0;

            texts.titel2.setDeltaY(deltaY);
/*            if (titel2 === '') {
                deltaY -= 3;
            }*/

            texts.titel2.setDeltaY(deltaY);

            texts.email.setText(values.email);

            if (values.fon == '') {
                texts.fon.setText('');
                texts._fon.setText('');
            } else {
                texts.fon.setText(values.fon);
                texts._fon.setText('Phone');
            }



            if (values.mobil == '') {
                texts.mobil.setText('');
                texts._mobil.setText('');
            } else {
                texts.mobil.setText(values.mobil);
                texts._mobil.setText('Mobile');
            }
            if(values.fon === ""){
                deltaY -= 3.5;
            }

            texts.mobil.setDeltaY(deltaY);
            texts._mobil.setDeltaY(deltaY);

            if(values.mobil === ""){
                deltaY -= 3.5;
            }

            texts.email.setDeltaY(deltaY);
            texts._email.setDeltaY(deltaY);

            if (values.email == '') {
                texts.email.setText('');
                texts._email.setText('');
            } else {
                texts.email.setText(values.email);
                texts._email.setText('E-Mail');
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



      /*      texts.email.setDeltaY(dy);*/
       /*     if(values.email==='') {
                dy-=3;
            }*/
        }

    };
    return r;
}