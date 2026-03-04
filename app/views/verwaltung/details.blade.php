@extends('master')
@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::script('/js/jsqr-0.2-min.js')}}
{{HTML::script('/js/lib.js?r='.$__rev)}}
{{HTML::style('/css/edit.css?r='.$__rev)}}
<?php
echo '<script type="text/javascript">svgconfig = ';
echo json_encode(json_decode(file_get_contents(public_path() . '/visika/' . $vorlage->template_folder . '/config.json'))), ";\n";
$f = public_path() . '/visika/' . $vorlage->template_folder . '/data.json';
echo 'var values = ', isset($input) ? json_encode($input) : '{}', ';';
echo '</script>';
?>
{{HTML::script('/js/visika-edit-0.1.js?r='.$__rev)}}
{{HTML::script('/js/FileSaver.js?r='.$__rev)}}
{{HTML::script('/visika/' . $vorlage->template_folder . '/handler.js?r='.$__rev)}}
<script type="text/javascript">
    
    
    $(document).ready(function() {

        var orderId =<?php echo $order->id; ?>;
        var vorlageId = <?php echo $vorlage->id; ?>;
        values = <?php echo $order->input; ?>;

        var cfg = {
            svgConfig: svgconfig,
            svgContainer: document.getElementById('preview'),
            formContainer: document.getElementById('inputs'),
            edit: false
        };
        cfg.button = document.getElementById('btnSpeichern');
        cfg.values = values;
        cfg.handler = new handler();

        myEditor = new vEditor(cfg);
        if (svgconfig.scale) {
            myEditor.setSVGScale(svgconfig.scale);
        }

        var inputs = $('#form').find('input,select');
        var edit = function() {
            $('#buttons1').fadeOut(800, function() {
                inputs.removeAttr('disabled');
                inputs.first().focus();
                $('#buttons2').fadeIn(800, function() {
                });
            });
        };

        $('#btnBearbeiten').click(edit);

        var toggleBack = function() {
            $('#buttons2').fadeOut(800, function() {
                inputs.attr('disabled', 'disabled');
                $('#buttons1').fadeIn(800);
            });
        };

        var save = $('#btnSpeichern').click(function() {
            if (save.hasClass('disabled')) {
                return;
            }

            if (myEditor.validateForm()) {
                var r = myEditor.submitData({
                    url: '/verwaltung/updateorder',
                    id: orderId,
                    success: function() {
                        var e = $('#extras').find('input');
                        var extras = [];
                        e.each(function(i, o) {
                            if (o.checked) {
                                extras.push(o.name);
                            }
                        });
                        var data = {id: orderId, extras: extras, menge: $('#sel_menge').val(), vorlageId: vorlageId};
                        $('#address').find('input').each(function(i, o) {
                            data[o.name] = o.value;
                        });
                        $.ajax({
                            url: '/verwaltung/updateorder2',
                            type: 'POST',
                            dataType: 'JSON',
                            data: data,
                            success: function() {
                                toggleBack();
                            }
                        });
                    }
                });
            }
        });

        $('#btnFreigabe').click(function() {
            $.ajax({
                url: '/verwaltung/freigabe',
                type: 'POST',
                dataType: 'JSON',
                data: {id: orderId, menge: $('#sel_menge').val()},
                success: function() {
                    location.href = '/verwaltung/uebersicht';
                }
            });
        });
        $('#btnStornieren').click(function() {
            if(!confirm('Bestellen wirklich stornieren?')){
                return;
            }
            $.ajax({
                url: '/verwaltung/loeschen',
                type: 'POST',
                dataType: 'JSON',
                data: {id: orderId},
                success: function() {
                    location.href = '/verwaltung/uebersicht';
                }
            });
        });
        $('#btnAbbrechen').click(function() {
            location.href = '/verwaltung/details?id=' + orderId;
        });

        
        
  


        <?php if(isset($edit)) echo 'edit();' ?>
    });
</script>
@endsection

@section('content')
<div class="form">
    <div>
        <strong>Name</strong>: {{$order->name}}<br />
        <strong>Auftragsnummer</strong>: {{$order->id}}<br />
        <strong>Vorlage</strong>: {{$vorlage->name}} <form method="post" action="/verwaltung/template" style="display:inline" /><input type="hidden" name="orderid" value="{{$order->id}}" /><input type="submit" class="button small" value="ändern" /></form>
    </div>
    <br />
    <div id="form">
        <div id="inputs"></div>
        <br />
        <div>
            <strong>Stückzahl</strong>:
            <select name="menge" id="sel_menge" disabled="disabled" style="width:170px; text-align:right">
                <?php
                foreach ($stueckzahlen as $s) {
                    echo '<option value="', $s->menge, '"';
                    if ($s->menge == $order->menge) {
                        echo ' selected="selected"';
                    }
                    echo '>', $s->menge, ' Stück&nbsp;';
                    if ($mandant->preise_besteller) {
                        echo '- ', number_format($s->preis, 2, ',', '.'), ' €&nbsp;';
                    }
                    echo '</option>';
                }
                ?>
            </select>
        </div>

        @if(count($extras))
        <h2>Extras:</h2>
        <table id="extras">
            @foreach($extras as $extra)
            <tr>
                <td><input type="checkbox" id="extras[{{$extra->name}}]" name="{{$extra->name}}" disabled="disabled"<?php if ($extra->checked) echo ' checked="checked"'; ?> />
                    <label for="extras[{{$extra->name}}]" style="width:150px;display:inline-block;">{{{$extra->text}}}</label></td><?php
                if ($mandant->preise_besteller) {
                    echo '<td style="width:80px; text-align:right"><label for="extras[', $extra->name, ']">', number_format($extra->price, 2, ',', '.'), ' €</label></td>';
                }
                ?></tr>
            @endforeach
        </table>
        @endif

        <h2>Lieferadresse:</h2>
        @if($mandant->anschriftFix())
        {{{$order->anschrift_firma}}}<br />
        @if($order->anschrift_abteilung)
        {{{$order->anschrift_abteilung}}}<br />
        @endif
        @if($order->anschrift_name)
        {{{$order->anschrift_name}}}<br />
        @endif
        {{{$order->anschrift_strasse}}}<br />
        {{{$order->anschrift_plz}}} {{{$order->anschrift_ort}}}<br />
        @if($order->anschrift_land)
        {{{$order->anschrift_land}}}
        @endif
        @else
        <table class="form" id="address">
            <tr id="tr_firma">
                <td class="col1"><label for="anschrift_firma">Firma</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_firma}}}" name="anschrift_firma" id="anschrift_firma" disabled="disabled"></td>
            </tr>
            <tr id="tr_abteilung">
                <td class="col1"><label for="anschrift_abteilung">Abteilung</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_abteilung}}}" name="anschrift_abteilung" id="anschrift_abteilung" disabled="disabled"></td>
            </tr>
            <tr id="tr_name">
                <td class="col1"><label for="anschrift_name">Name</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_name}}}" name="anschrift_name" id="anschrift_name" disabled="disabled"></td>
            </tr>
            <tr id="tr_strasse">
                <td class="col1"><label for="anschrift_strasse">Straße</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_strasse}}}" name="anschrift_strasse" id="anschrift_strasse" disabled="disabled"></td>
            </tr>
            <tr id="tr_plz">
                <td class="col1"><label for="anschrift_plz">PLZ</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_plz}}}" name="anschrift_plz" id="anschrift_plz" style="width:42px" maxlength="5" disabled="disabled"></td>
            </tr>
            <tr id="tr_ort">
                <td class="col1"><label for="anschrift_ort">Ort</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_ort}}}" name="anschrift_ort" id="anschrift_ort" disabled="disabled"></td>
            </tr>
            <tr id="tr_ort">
                <td class="col1"><label for="anschrift_land">Land</label></td>
                <td class="col2"><input type="text" value="{{{$order->anschrift_land}}}" name="anschrift_land" id="anschrift_land" disabled="disabled"></td>
            </tr>
        </table>
        @endif
    </div>
    <div class="buttons" id="buttons1">
        @if($order->status_id<3)
        @if($order->status_id==1)
        <input class="button green" type="button" value="freigeben" id="btnFreigabe" />
        @endif
        <input class="button" type="button" value="bearbeiten" id="btnBearbeiten" />
        <input class="button red" type="button" value="stornieren"  id="btnStornieren" />
        @endif
        <a href="/verwaltung/uebersicht"><input class="button" type="button" value="zurück" /></a>
    </div>
    <div class="buttons" id="buttons2" style="display:none">
        @if($order->status_id<3)
        <input class="button" type="button" value="speichern" id="btnSpeichern" />
        <input class="button" type="button" value="Bearbeitung abbrechen" id="btnAbbrechen" />
        @endif
    </div>
</div>
<div id="preview">
    <?php
    for ($i = 1; $i <= $vorlage->seiten; $i++) {
        $path = storage_path() . '/vorlagen/' . $vorlage->folder . '/page' . $i . '.svg';
        if (file_exists($path)) {
            echo file_get_contents($path);
        }
    }
    ?>
    <div style="text-align: left;margin: 30px;">
        <h2>Protokoll</h2>
    <table>
        @foreach($protokoll as $p)
        <tr><td>{{$p->created_at->format('d.m.Y H:i:s')}}: </td><td class="number">{{$p->text}}</td></tr>
        @endforeach
    </table>
    </div>
    
    
    <a style="display: none;" id="qrCodeLink" href="#" download="qrcode.png">Download QR-Code</a>

</div>

@endsection