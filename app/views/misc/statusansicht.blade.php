@extends('master')

@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::script('/js/lib.js')}}
{{HTML::script('/js/visika-view-0.1.js')}}
{{HTML::style('/css/edit.css')}}

<script type="text/javascript">data=<?php echo $order->output;?>;
$(document).ready(function() {
    var viewer = new vViewer({
        svgContainer: document.getElementById('preview'),
        data: data,
        scale: {{$vorlage->scale}}
    });
});
</script>
@endsection

@section('content')
<div style="float:left">
    <h1>Bestellstatus</h1>
    <table>
        <tr><td>Auftragsnummer:</td><td>{{$order->id}}</td></tr>
        <tr><td>Bestellmenge:</td><td >{{$order->menge}} Stück</td></tr>
        <tr><td>Status:</td><td class="number">{{$status->bezeichnung}}</td></tr>
    </table>
    <h2>Protokoll</h2>
    <table>
        @foreach($protokoll as $p)
        <tr><td>{{$p->created_at->format('d.m.Y H:i:s')}}: </td><td class="number">{{$p->text}}</td></tr>
        @endforeach
    </table>

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