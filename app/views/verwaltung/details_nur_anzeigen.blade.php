@extends('master')
@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::script('/js/lib.js?r='.$__rev)}}
{{HTML::script('/js/visika-view-0.1.js?r='.$__rev)}}
{{HTML::style('/css/edit.css?r='.$__rev)}}
<script type="text/javascript">data =<?php echo $order->output; ?>;
    $(document).ready(function() {
        new vViewer({
            svgContainer: document.getElementById('preview'),
            data: data,
            scale: {{$vorlage->scale}}
        });
        var imgs = $('#preview').find('image');
        if(imgs.length===1) {
            var img1 = imgs[0];
            var url = img1.href.baseVal;
            $('#qrCodeLink').attr('href',url);
            
        }
        
    });
</script>
@endsection

@section('content')
<div class="form">
    <table class="list">
        <tr><td><strong>Name</strong></td><td>{{$order->name}}</td></tr>
        <tr><td><strong>Auftragsnummer</strong></td><td>{{$order->id}}</td></tr>
        <tr><td><strong>Vorlage</strong></td><td>{{$vorlage->name}}</td></tr>
        <tr><td><strong>Stückzahl</strong></td><td>{{$order->menge}}</td></tr>
        @if(count($extras))
        <tr><td><strong>Stückzahl</strong></td><td><?php
        $a = array();
        foreach($extras as $e) {
            $a[] = e($e->name);
        }
        echo implode(', ',$a);
        ?></td></tr>
        @endif
        <tr><td style="vertical-align:top;width:140px"><strong>Lieferanschrift</strong></td><td>
        {{{$order->anschrift_firma}}}<br />
        {{{$order->anschrift_abteilung}}}{{$order->anschrift_abteilung==''?'':'<br />'}}
        {{{$order->anschrift_name}}}{{$order->anschrift_name==''?'':'<br />'}}
        {{{$order->anschrift_strasse}}}<br />
        {{{$order->anschrift_plz}}} {{{$order->anschrift_ort}}}</td></tr>

    </table>
    <div class="buttons" id="buttons1">
        <a href="/verwaltung/uebersicht"><input class="button" type="button" value="zurück" /></a>
    </div>
    
    <div style="">
        <h2>Protokoll</h2>
    <table>
        @foreach($protokoll as $p)
        <tr><td>{{$p->created_at->format('d.m.Y H:i:s')}}: </td><td style="text-align: left;" class="number">{{$p->text}}</td></tr>
        @endforeach
    </table>
    </div>
    
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
    <a  style="display: none;" id="qrCodeLink" href="#" download="qrcode.png">Download QR-Code</a>
</div>

@endsection