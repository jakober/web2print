@extends('admin-master')

@section('content')
<h1>Übersicht</h1>
<table>
	<tr><th>Kunde</th><th>nicht abgerechnet</th><th>gesamt</th><th></th></tr>

    <?php
    foreach($mandanten as $m) {
    $count1 = Order::where('mandant_id','=',$m->id)->where('status_id','=',5)->count();
    $count2 = Order::where('mandant_id','=',$m->id)->where('status_id','!=',6)->count();
    ?>

    <tr class="{{$count1!=0?'abzurechnen':''}}">
        <td>{{$m->name}}</td>
        <td class="right">{{$count1}}</td>
        <td class="right">{{$count2}}</td>
        <td class=""><a href="abrechnen?id=<?=$m->id?>"><input class="button small" type="button" value="abrechnen" /></a></td>
    </tr>
    <?php } ?>
</table>
@endsection
