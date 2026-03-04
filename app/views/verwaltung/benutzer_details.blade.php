@extends('master')
@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
<script type="text/javascript">

    $(document).ready(function() {
        var f = function() {
            $('#buttons1').fadeOut(800, function() {
                inputs.removeAttr('disabled');
                inputs.first().focus();
                $('#buttons2').fadeIn(800);
            });
        };
<?php if(Input::has('email')) echo "       f();\n" ?>
        var inputs = $('#inputs').find('input,select');
        $('#btnBearbeiten').click(f);

        $('#btnAbbrechen').click(function() {
            location.href = location.href + '';
        });
    });
</script>
@endsection

@section('content')
@if(isset($error))
<div class="error">{{{$error}}}</div>
@endif
<form method="post">
    <table class="form" id="inputs">
        <tr>
            <td class="col1"><label for="username">Benutzername</label></td>
            <td class="col2" style="width:400px;"><input type="text" name="username" id="username" value="{{{$user->username}}}" disabled="disabled" /></td>
        </tr>
        <tr>
            <td class="col1"><label for="name">Name/Bemerkung</label></td>
            <td class="col2"><input type="text" name="name" id="name" value="{{{$user->name}}}" disabled="disabled" /></td>
        </tr>
        <tr>
            <td class="col1"><label for="password">Kennwort</label></td>
            <td class="col2"><input type="text" name="password" value="" placeholder="Neues Kennwort?" disabled="disabled" /></td>
        </tr>
        <tr>
            <td class="col1"><label for="gruppe">Gruppe</label></td>
            <td class="col2">{{Form::select('gruppe',$gruppen,$user->gruppe_id,array('id'=>'gruppe','disabled'=>'disabled'))}}</td>
        </tr>
        <tr>
            <td class="col1"><label for="sendmail">Infomail an Ersteller</label></td>
            <td class="col2">{{Form::checkbox('sendmail', '1', $user->sendmail, array('id'=>'sendmail','disabled'=>'disabled'))}} </td>
        </tr>
        <tr>
            <td class="col1"><label for="sendmail">Bestellung sofort freigeben</label></td>
            <td class="col2">{{Form::checkbox('confirm', '1', $user->confirm, array('id'=>'confirm','disabled'=>'disabled'))}} </td>
        </tr>
        <tr>
            <td class="col1"><label for="name">E-Mail für Freigabe</label></td>
            <td class="col2"><input type="text" name="confirm_mail" id="confirm_mail" value="{{{$user->confirm_mail}}}" disabled="disabled" /></td>
        </tr>
        <tr>
            <td class="col1"><label>Vorlagen</label></td>
            <td class="col2">
                @foreach($vorlagen as $v)
                <div><input type="checkbox" name="vorlagen[]" value="{{$v->id}}" id="vorlage_{{$v->id}}" <?php if (isset($uv[$v->id])) echo ' checked="checked"' ?> disabled="disabled" /><label for="vorlage_{{$v->id}}">{{str_replace(' ','&nbsp;',$v->name)}}</label></div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div id="buttons1">
                    <input id="btnBearbeiten" type="button" class="button" value="Benutzerdaten ändern" />
                    <a href="/verwaltung/benutzer"><input class="button" type="button" value="zurück" /></a>
                </div>
                <div id="buttons2" style="display:none">
                    <input class="button" type="submit" value="speichern" id="btnSpeichern" />
                    <input class="button" type="button" value="Bearbeitung abbrechen" id="btnAbbrechen" />
                </div>
            </td>
        </tr>

    </table>
</form>

@endsection
