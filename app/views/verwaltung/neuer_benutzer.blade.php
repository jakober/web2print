@extends('master')
@section('head')

<script type="text/javascript">

</script>
@endsection

@section('content')
@if(isset($error))
<div class="error">{{{$error}}}</div>
@endif
<form method="post">
<table class="form">
    <tr>
        <td class="col1"><label for="username">Benutzername</label></td>
        <td class="col2"><input type="text" name="username" id="username" value="{{{isset($username)?$username:''}}}" /></td>
    </tr>
    <tr>
        <td class="col1"><label for="name">Name/Bemerkung</label></td>
        <td class="col2"><input type="text" name="name" id="name" value="{{{isset($name)?$name:''}}}" /></td>
    </tr>
    <tr>
        <td class="col1"><label for="password">Kennwort</label></td>
        <td class="col2"><input type="text" name="password" value="{{{isset($password)?$password:''}}}" /></td>
    </tr>
    <tr>
        <td class="col1"><label for="gruppe">Gruppe</label></td>
        <td class="col2">{{Form::select('gruppe',$gruppen,$gruppe,array('id'=>'gruppe'))}}</td>
    </tr>
    <tr>
        <td class="col1"><label>Vorlagen</label></td>
        <td class="col2">
            @foreach($vorlagen as $v)
            <div><input type="checkbox" name="vorlagen[]" value="{{$v->id}}" id="vorlage_{{$v->id}}" /> <label for="vorlage_{{$v->id}}">{{{$v->name}}}</label></div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center"><input type="submit" class="button" value="Benutzer anlegen" <?php /*disabled="disabled"*/ ?> /></td>
    </tr>

</table>
</form>

@endsection
