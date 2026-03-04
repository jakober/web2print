@extends('admin-master')

@section('content')

<h1>Abrechnungsübersicht {{$mandant->name}}</h1>
<table>
	<tr><th>Monat</th><th>Bestellungen</th><th>nicht abgerechnet</th></tr>

    <?php
    foreach($orders as $i=>$o) {
        $v = $o->vorlage()->first();

    ?>
    <tr<?php echo $i&1?' class="odd"':'';?>>
        <td class=""></td>
        <td class="">{{$v->name}}</td>
        <td class="right">{{$o->menge}}</td>
    </tr>
    <?php

     } ?>

     <tr>
     	<td colspan="3" class="right footer"><input type="button" class="button" value="Auswahl abrechnen" /></td>
     </tr>
</table>
@endsection
