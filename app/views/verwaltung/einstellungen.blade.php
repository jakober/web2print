@extends('master')
@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::style('/css/verwaltung.css')}}
<script type="text/javascript">

    $(document).ready(function() {
        var f = function() {
            $('#buttons1').fadeOut(800, function() {
                inputs.removeAttr('disabled');
                inputs.first().focus();
                $('#buttons2').fadeIn(800);
            });
        };
        var inputs = $('#inputs').find('input,select');
        $('#btnBearbeiten').click(f);

        $('#btnAbbrechen').click(function() {
            location.href = location.href + '';
        });
    });
</script>

@endsection

@section('content')
<h1>Einstellungen</h1>
<form method="post">
    <table class="form" id="inputs">
        <tr>
            <td class="col1"><label for="name">Name Verwaltung</label></td>
            <td class="col2"><input type="text" id="name" name="name" value="{{{$mandant->name}}}" disabled="disabled" /></td>
        </tr>
        <tr>
            <td class="col1"><label for="email">E-Mail Verwaltung</label></td>
            <td class="col2"><input type="text" id="email" name="email" value="{{{$mandant->email}}}" disabled="disabled" /></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div id="buttons1">
                    <input id="btnBearbeiten" type="button" class="button" value="Einstellungen bearbeiten" />
                    <a href="/verwaltung/benutzer"><input class="button" type="button" value="zurück" /></a>
                </div>
                <div id="buttons2" style="display: none">
                    <input class="button" type="submit" value="speichern" id="btnSpeichern" />
                    <input class="button" type="button" value="Bearbeitung abbrechen" id="btnAbbrechen" />
                </div>
            </td>
        </tr>
    </table>
</form>

@endsection
