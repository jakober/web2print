@extends('master')

@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::script('/js/lib.js')}}
{{HTML::script('/js/visika-view-0.1.js')}}
{{HTML::style('/css/edit.css')}}
{{HTML::style('/css/verwaltung.css')}}

<script type="text/javascript">svgs =<?php echo json_encode($svgs); ?>;
    orders=<?php echo $orders->toJSON(); ?>;
    vorlagen=<?php echo json_encode($vorlagen); ?>;
    configs=<?php echo json_encode($configs); ?>;
    viewers=[];
    $(document).ready(function() {
        var divMain = document.getElementById('bestellungen');
        function fmtDate(date) {
            var a = date.split(' ');
            var b = a[0].split('-');
            return b[2] + '.' + b[1] + '.' + b[0] + ' ' + a[1];
        }
        function empty() {
            divMain.innerHTML = 'Keine neuen Bestellungen vorhanden';
        }
        $.each(orders, function(i, o) {
            var div = document.createElement('div');

            var divInfo = document.createElement('div');
            divInfo.className = 'info';
            divInfo.innerHTML = '<strong>Name:</strong> "'+o.input.fullName+'"<br><strong>Menge:</strong> ' + o.menge + '<br><strong>Bestellt am:</strong> ' + fmtDate(o.created_at);

            var divSVG = document.createElement('div');
            divSVG.className = 'svg';
            divSVG.innerHTML = svgs[vorlagen[o.vorlage_id]][0];
            //divSVG.innerHTML = svgs[vorlagen[o.vorlage_id]].join(' ');

            var buttons = document.createElement('div');
            buttons.className = 'buttons';

            function createButton(text, onclick) {
                var b = document.createElement('input');
                b.type = 'button';
                b.className = 'button';
                b.value = text;
                b.onclick = onclick;
                if(text==="bearbeiten") {
                    b.className='button disabled';
                }
                buttons.appendChild(b);
            }

            createButton('freigeben', function() {
                $.ajax({
                    url: '/verwaltung/freigabe',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id:o.id},
                    success: function() {
                        $(div).fadeOut(1000,function() {
                            $(div).remove();
                            if($('#bestellungen').has('div').length===0) {
                                empty();
                            }
                        });
                    }
                });
            });

            createButton('bearbeiten', function() {

            });

            createButton('in Papierkorb', function() {
                $.ajax({
                    url: '/verwaltung/loeschen',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id:o.id},
                    success: function() {
                        $(div).fadeOut(1000);
                    }
                });
            });
            divInfo.appendChild(buttons);
            var divClear = document.createElement('div');
            divClear.className = 'clear'
            div.appendChild(divInfo);
            div.appendChild(divSVG);
            div.appendChild(divClear);
            //div.innerHTML=svgs[vorlagen[o.vorlage_id]].join('');
            var viewer = new vViewer({
                svgContainer: div,
                data: o.output
            });
            divMain.appendChild(div);
        });
    });
</script>
@endsection

@section('content')
<?php /*
  <select id="select_status">
  <option value="1">neue</option>
  </select> */ ?>

<div id="bestellungen">

</div>

@endsection