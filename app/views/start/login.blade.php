@extends('master')

@section('head')
{{ HTML::style('/css/edit.css') }}
@endsection

@section('content')

<div class="outerbox">
    <h1>
        @if($mandant->sprache === 'en')
            Web2Print<br />Business Card Orders
        @else
            Web2Print<br />Visitenkarten-Bestellungen
        @endif
    </h1>

    @if(isset($error))
        <h2>{{ $error }}</h2>
    @endif

    <div class="loginbox">
        <form method="post" action="/login" id="form">
            <table>
                <tr>
                    <td>
                        <label>
                            @if($mandant->sprache === 'en')
                                Username:
                            @else
                                Benutzer:
                            @endif
                        </label>
                    </td>
                    <td><input type="text" name="username" id="username" /></td>
                </tr>
                <tr>
                    <td>
                        <label>
                            @if($mandant->sprache === 'en')
                                Password:
                            @else
                                Kennwort:
                            @endif
                        </label>
                    </td>
                    <td><input type="password" name="password" id="password" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="button"
                            value="@if($mandant->sprache === 'en') Login @else anmelden @endif"
                            id="submit" />
                    </td>
                </tr>
            </table>
        </form>
    </div><br /><br />

    <?php
    $me = MandantExtra::where('mandant_id', '=', $mandant->id)
        ->where('key', '=', 'login_info_unten')
        ->first();
    if ($me) {
        echo $me->value;
    }
    ?>
</div>

@endsection
