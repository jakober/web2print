@extends('admin-master')

@section('head')
{{HTML::style('/css/edit.css')}}
@endsection

@section('content')
<div class="outerbox">
    <h1>Web2Print<br />Visitenkarten-Bestellungen</h1>

    <div class="loginbox">
        <form method="post" action="/login">
              <table>
                <tr>
                    <td><label>Benutzer:</label></td>
                    <td><input type="text" name="username" /></td>
                </tr>
                <tr>
                    <td><label>Kennwort:</label></td>
                    <td><input type="password" name="password" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" class="button" value="anmelden" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>
@endsection