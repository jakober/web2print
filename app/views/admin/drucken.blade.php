@extends('admin-master')

@section('content')

<h1>Druckübersicht {{$mandant->name}}</h1>
<table>
	<tr><th>Nr.</th><th>Name</th><th>Template</th><th>Menge</th><th>Lieferort</th><th class="right">Auswahl</th></tr>

    <?php
    foreach($orders as $i=>$o) {
        $v = $o->vorlage()->first();
    ?>
    <tr<?php echo $i&1?' class="odd"':'';?>>
        <td>{{$o->id}}</td>
        <td class="">{{$o->name}}</td>
        <td class="right">{{$v->name}}</td>
        <td class="">{{$o->menge}}</td>
        <td class="">{{$o->anschrift_ort}}</td>
        <td class="right"><label for="{{$o->id}}"><input type="checkbox" id="{{$o->id}}" value="{{$o->id}}" /></label></td>
    </tr>
    <?php

     } ?>

     <tr>
     	<td colspan="6" class="right footer"><input type="button" class="button" value="Auswahl drucken" /></td>
     </tr>
</table>
@endsection
