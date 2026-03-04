function handler(editor) {
    var r = {
        update: function(values, texts, images, qrcodes, editor) {
            var n = [],
                    pre_fon = '',
                    maxlength = 250;

            var formatMobil = function(dw) {
                return '+49 152 ' + ('22887' + dw).chunkString(2).join(' ');
            };
            if (values.vorname !== '') {
                n.push(values.vorname);
            }
            if (values.nachname !== '') {
                n.push(values.nachname);
            }
            var name = n.join(' ');
            texts.name.setText(name);
            values.fullName = name;

            var  abteilung1, abteilung2 = '';
     
            var deltaY = 0;
     
   
            position = values.position;
            texts.position.setText(position);
            texts.position.setDeltaY(deltaY);
            if (position === '') {
                deltaY -= 3;
            }
            
            
            
            
            abteilung = values.abteilung;
            texts.abteilung.setText(abteilung);
            
            
            texts.abteilung.setDeltaY(deltaY); 
  
            if (abteilung === '') {
                deltaY -= 3;
            }
           
            
            texts.abteilung_2.setDeltaY(deltaY); 
            abteilung_2 = values.abteilung_2
            texts.abteilung_2.setText(abteilung_2);

            texts.email.setText(values.email);

            if(values.fon === ''){
                texts.fon.setText('');
                texts._fon.setText('');
            }else{
                texts.fon.setText(pre_fon + values.fon);
                texts._fon.setText('Phone');
            }



            if (values.mobil === '') {
                texts.mobil.setText('');
                texts._mobil.setText('');
            } else {
                texts.mobil.setText(values.mobil);
                texts._mobil.setText('Cell');
            }

            var e = values.mobil.length > 0;
            var d = e ? 0 : 0, dy = d;



            texts.strasse.setDeltaY(dy);
            dy+=d;
            texts.ort.setDeltaY(dy);
            texts.fon.setDeltaY(dy);
            texts._fon.setDeltaY(dy);
            dy+=d;
            if(values.fon==='') {
                dy-=3;
            }

            texts.mobil.setDeltaY(dy);
            texts._mobil.setDeltaY(dy);
            dy+=d;
            if(values.mobil==='') {
                dy-=3;
            }
            texts.email.setDeltaY(dy);
            if(values.email==='') {
                dy-=3;
            }
            texts.internet.setDeltaY(dy);
        }

    };
    return r;
}