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
function chkForm() {
    var a = ['strasse', 'plz', 'ort'];
    var ok = true;
    for (var i = 0; i < a.length; i++) {
        var k = a[i];
        var el = document.getElementById('i_' + k);
        if (el.value === '') {
            ok = false;
            el.className = 'error';
        }
    }
    return ok;
}
</script>
@endsection

@section('content')

<?php $isEn = ($mandant->sprache == 'en'); ?>

<?php
if(isset($anschrift)){
    $anschrift_firma   = $anschrift['anschrift_firma'];
    $anschrift_abteilung = $anschrift['anschrift_abteilung'];
    $anschrift_name    = $anschrift['anschrift_name'];
    $anschrift_strasse = $anschrift['anschrift_strasse'];
    $anschrift_plz     = $anschrift['anschrift_plz'];
    $anschrift_ort     = $anschrift['anschrift_ort'];
    $anschrift_land    = $anschrift['anschrift_land'];
}else{
    $anschrift_firma   = $adressen[0]->firma;
    $anschrift_abteilung = $adressen[0]->abteilung;
    $anschrift_name    = $adressen[0]->name;
    $anschrift_strasse = $adressen[0]->strasse;
    $anschrift_plz     = $adressen[0]->plz;
    $anschrift_ort     = $adressen[0]->ort;
    $anschrift_land    = $adressen[0]->land;
}
?>

<div style="float:left">
    <p>
        {{ $isEn
            ? 'To which address should we send <br />the business cards?'
            : 'An welche Adresse sollen die Visitenkarten<br /> geschickt werden?' }}
    </p>

    @if($manuell)
    <form method="post" action="/bestellung" onsubmit="return chkForm()">
    <table class="form">
        <tr id="tr_firma">
            <td class="col1"><label for="i_firma">{{ $isEn ? 'Company' : 'Firma' }}</label></td>
            <td class="col2"><input type="text" value="<?=$anschrift_firma?>" name="anschrift_firma" id="i_firma"></td>
        </tr>
        <tr id="tr_abteilung">
            <td class="col1"><label for="i_abteilung">{{ $isEn ? 'Department' : 'Abteilung' }}</label></td>
            <td class="col2"><input type="text" value="<?=$anschrift_abteilung?>" name="anschrift_abteilung" id="i_abteilung"></td>
        </tr>
        <tr id="tr_name">
            <td class="col1"><label for="i_name">{{ $isEn ? 'Name' : 'Name' }}</label></td>
            <td class="col2"><input type="text" value="<?=$anschrift_name?>" name="anschrift_name" id="i_name"></td>
        </tr>
        <tr id="tr_strasse">
            <td class="col1"><label for="i_strasse">{{ $isEn ? 'Street' : 'Straße' }}</label></td>
            <td class="col2"><input type="text" value="<?=$anschrift_strasse?>" name="anschrift_strasse" id="i_strasse"></td>
        </tr>
        <tr id="tr_plz">
            <td class="col1"><label for="i_plz">{{ $isEn ? 'ZIP code' : 'PLZ' }}</label></td>
            <td class="col2"><input type="text" value="<?=$anschrift_plz?>" name="anschrift_plz" id="i_plz" style="width:42px" maxlength="5"></td>
        </tr>
        <tr id="tr_ort">
            <td class="col1"><label for="i_ort">{{ $isEn ? 'City' : 'Ort' }}</label></td>
            <td class="col2"><input type="text" value="<?=$anschrift_ort?>" name="anschrift_ort" id="i_ort"></td>
        </tr>
        @if($mandant->sprache=="en")
            <tr id="tr_land">
                <td class="col1"><label for="i_land">{{ $isEn ? 'Country' : 'Land' }}</label></td>
                <td class="col2"><input type="text" value="<?=$anschrift_land?>" name="anschrift_land" id="i_land"></td>
            </tr>
        @endif
    </table>
    <br />
    @endif

    <?php
    $anzahl_adressen = count($adressen);
    if($anzahl_adressen>1){
        foreach ($adressen as $a) {
            echo '<input type="hidden" name="anschrift_firma" value="',htmlentities($a->firma),'" id="i_firma">';
            if ($a->abteilung) {
                echo '<input type="hidden" name="anschrift_abteilung" value="',htmlentities($a->abteilung),'" id="i_abteilung">';
            }
            echo '<input type="hidden" name="anschrift_name" value="',htmlentities($a->name),'" id="i_name">';
            echo '<input type="hidden" name="anschrift_strasse" value="',htmlentities($a->strasse),'" id="i_strasse">';
            echo '<input type="hidden" name="anschrift_plz" value="',htmlentities($a->plz),'" id="i_plz">';
            echo '<input type="hidden" name="anschrift_ort" value="',htmlentities($a->ort),'" id="i_ort">';
            echo '<input type="hidden" name="anschrift_land" value="',htmlentities($a->land),'" id="i_land">';
            echo '<table class="anschrift"><tr>';
            echo '<td valign="top"><input type="radio" id="radio_', $a->id, '" name="a" /></td>';
            echo '<td><label for="radio_', $a->id, '">';
            echo htmlspecialchars($a->firma);
            if ($a->abteilung) {
                echo '<br />', htmlspecialchars($a->abteilung);
            }
            echo '<br />', htmlspecialchars($a->strasse);
            echo '<br />', $a->plz, ' ', htmlspecialchars($a->ort);
            echo '</label></td>';
            echo '</tr></table><br />';
        }
    }
    ?>
    <input class="button" type="submit" value="&raquo; {{ $isEn ? 'continue to order' : 'weiter zur Bestellung' }}" id="submit" />
    </form>
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
@endsection
