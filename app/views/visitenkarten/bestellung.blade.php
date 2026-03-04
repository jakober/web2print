@extends('master')

@section('head')
{{ HTML::script('/js/jquery-1.10.1.min.js') }}
{{ HTML::script('/js/lib.js?r='.$__rev) }}
{{ HTML::script('/js/visika-view-0.1.js?r='.$__rev) }}
{{ HTML::style('/css/edit.css?r='.$__rev) }}
<script type="text/javascript">
data = <?php echo json_encode($data); ?>;
$(document).ready(function() {
    new vViewer({
        svgContainer: document.getElementById('preview'),
        data: data,
        scale: {{$vorlage->scale}}
    });
});
</script>
@endsection

@section('content')
<?php $isEn = ($mandant->sprache == 'en'); ?>

<div style="float:left">
    @if(empty($download_only))
        <h1>{{ $isEn ? 'Delivery address:' : 'Lieferadresse:' }}</h1>
        {{{ $anschrift['anschrift_firma'] }}}<br />
        {{{ $anschrift['anschrift_abteilung'] }}}{{ $anschrift['anschrift_abteilung']=='' ? '' : '<br />' }}
        {{{ $anschrift['anschrift_name'] }}}{{ $anschrift['anschrift_name']=='' ? '' : '<br />' }}
        {{{ $anschrift['anschrift_strasse'] }}}<br />
        {{{ $anschrift['anschrift_plz'] }}} {{{ $anschrift['anschrift_ort'] }}}<br />
        {{{ $anschrift['anschrift_land'] }}}<br />
        @if(!$anschrift_fix)
            <a href="/anschrift"><input type="button" class="button small" value="{{ $isEn ? 'edit' : 'ändern' }}" /></a>
        @endif

        <h1 style="margin-top:30px">{{ $isEn ? 'Quantity:' : 'Stückzahl:' }}</h1>
        <form method="post" action="bestellabschluss">
            <select name="menge" style="width:170px; text-align: right;">
                @foreach($stueckzahlen as $s)
                    <option value="{{ $s->menge }}">
                        <?php
                            // Anzeige: "100 Stück" bzw. "100 pcs", ggf. Preis
                            echo $s->menge, ($isEn ? ' pcs&nbsp;' : ' Stück&nbsp;');
                            if ($mandant->preise_besteller) {
                                echo '- ', number_format($s->preis, 2, ',', '.'), ' €&nbsp;';
                            }
                        ?>
                    </option>
                @endforeach
            </select>

            @if(count($extras))
                <h1 style="margin-top:35px">{{ $isEn ? 'Extras' : 'Extras' }}</h1>
                <table>
                    @foreach($extras as $extra)
                        <tr>
                            <td><input type="checkbox" id="extras[{{$extra->name}}]" name="extras[{{$extra->name}}]" value="1" /></td>
                            <td>
                                <label for="extras[{{$extra->name}}]" style="width:150px;display:inline-block;">
                                    {{{ $extra->text }}}
                                </label>
                            </td>
                            <?php
                            if ($mandant->preise_besteller) {
                                echo '<td style="width:80px; text-align:right"><label for="extras[',$extra->name,']">', number_format($extra->price, 2, ',', '.'), ' €</label></td>';
                            }
                            ?>
                        </tr>
                    @endforeach
                </table>
            @endif

            <?php if($mandant->id==2 || $mandant->id==3): ?>
                <div style="margin-top:35px; width:350px; font-size:11pt">
                    {{ $isEn
                        ? 'Please review the content of your business card before submitting your binding order. You can make changes via the “Design” tab above.'
                        : 'Bitte kontrolliere den Inhalt Deiner Visitenkarte, bevor Du die Bestellung verbindlich absendest. Änderungen nimmst Du über den oberen Tab Gestaltung vor.' }}
                </div>
            <?php else: ?>
                <div style="margin-top:35px; width:350px; font-size:11pt">
                    {{ $isEn
                        ? '<strong>Check preview:</strong><br> Check the layout of your business card in the preview. You can make changes via the “Design” tab above.<br><br><strong>Confirm the order:</strong><br> Make sure that all data has been transmitted correctly and that the order has been duly confirmed. '
                        : 'Bitte kontrollieren Sie den Inhalt Ihrer Visitenkarte, bevor Sie die Bestellung verbindlich absenden. Änderungen nehmen Sie über den oberen Tab Gestaltung vor.' }}
                </div>
            <?php endif; ?>

            <div style="margin-top:35px">
                <input class="button clear red" type="submit" value="{{ $isEn ? 'Confirm order' : 'Bestellung verbindlich absenden' }}" id="submit" />
            </div>
        </form>
    @else
        <form method="post" action="bestellabschluss" id="downloadForm">
            <input type="hidden" name="menge" vavlue="1" />
            <input type="hidden" name="download_only" value="1">
            <input class="button clear red" type="submit" value="{{ $isEn ? 'Create print PDF' : 'Generate PDF Download' }}" id="downloadBtn" />
        </form>
    @endif
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
</div>

@if(!empty($download_only))
<script>
document.addEventListener("DOMContentLoaded", function() {
    var f = document.getElementById('downloadForm');
    if (f) {
        if (typeof f.requestSubmit === 'function') {
            f.requestSubmit();
        } else {
            HTMLFormElement.prototype.submit.call(f);
        }
    }
});
</script>
@endif
@endsection
