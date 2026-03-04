@extends('master')

@section('head')

{{ HTML::script('/js/jquery-1.10.1.min.js') }}
<script type="text/javascript">
function change() {
    var s = document.getElementById('auswahl');
    var l = s.options.length;
    var idx = s.selectedIndex;
    console.log(idx);
    for (i = 0; i < l; i++) {
        var div = document.getElementById('div' + i);
        div.style.display = (i === idx) ? 'block' : 'none';
    }
}
$(document).ready(change);
</script>
@endsection

@section('content')

<?php
$isEn = (!empty($mandant->sprache) && $mandant->sprache === 'en');
?>

<div class="auswahl">
    @if(isset($order))
    <form action="/verwaltung/details" method="POST">
        <h1>
            {{ $isEn ? 'Template selection for order "' . $order->name . '"' : 'Template-Auswahl zur Bestellung "' . $order->name . '"' }}
        </h1>
        <input type="hidden" name="id" value="{{ $order->id }}" />
    @else
    <form action="/gestaltung" method="POST">
        <?php if($mandant->id == 3 || $mandant->id == 2): ?>
            <h1>{{ $isEn ? 'Please choose your template:' : 'Wähle Deine Vorlage aus:' }}</h1>
        <?php else: ?>
            <h1>{{ $isEn ? 'Please choose your template:' : 'Wählen Sie Ihre Vorlage aus:' }}</h1>
        <?php endif; ?>

        <?php if($mandant->id == 9): ?>
            <small>
                If you are placed in Europe, please use the dropdown: Driventic EU.<br>
                The business cards will be sent directly to your location.<br><br>

                If you are placed out of Europe, please use the dropdown: Driventic Intern. Download.<br>
                You will receive a print pdf which you can use for printing in your region.<br><br>

            </small>
        <?php endif; ?>

    @endif

    <div style="height:30px;margin-bottom:25px">
        <select id="auswahl" name="templateid" onchange="change()" onkeyup="change()">
            @foreach($vorlagen as $v)
                <option
                    @if(isset($vorlage) && $vorlage->id == $v->id) selected="selected" @endif
                    value="{{ $v->id }}">
                    {{ $v->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <?php
        $i = 0;
        foreach ($vorlagen as $vorlage) {
            echo '<div id="div', $i, '"';
            if ($i > 0) {
                echo ' style="display:none"';
            }
            if ($vorlage->deleted != 1) {
                echo '><img src="/visika/', $vorlage->template_folder, '/muster.png" /></div>';
                $i++;
            }
        }
        ?>
    </div>

    <br />
    <input type="submit" class="button" value="&raquo; {{ $isEn ? 'next step' : 'weiter' }}" />
    </form>
</div>
@endsection
