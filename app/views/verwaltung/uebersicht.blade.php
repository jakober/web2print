@extends('master')

@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::style('/css/verwaltung.css')}}


<script type="text/javascript">







function showsave(id){
    $('#save_'+id).fadeIn(100);
    $('#saved_'+id).hide();
}

function setBestellnummer(id){
var bestellnummer = $('#'+id).val();
        $.ajax({
            type: "POST",
            url: '/ajax/action/setBestellnummer',
            data: { id:id,bestellnummer:bestellnummer },
            cache: false,
            success: function(data){
               $('#save_'+id).hide();
               $('#saved_'+id).show().delay( 5000 ).hide(200);
            }
            });

}
</script>

@endsection

@section('content')
<?php /*
  <select id="select_status">
  <option value="1">neue</option>
  </select> */ 
  $path = public_path();

    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }else{
        $page = 0;
    }



  ?>

<div id="bestellungen">
    <table class="list" style="width: 97.8%;">
        <tr class="custom-bg"><th>Nr.</th><th>Datum</th><th>Name</th><th>Vorlage</th><th>Menge</th><th>Status</th><th>Aktionen</th>@if ($mandant->bestNr == 1)<th>Bestell-Nr.</th>@endif</tr>
        @foreach($orders as $i=>$o)
        <tr<?php echo $i&1?' class="odd"':'';?>>
            <td class="right">{{$o->id}}</td>
            <td class="" >{{$o->created_at->format('d.m.Y')}} {{$o->created_at->format('H:i:s')}} Uhr</td>
            <td>{{$o->name}}</td>
            <td>{{{$o->vorlage()->first()->name}}}</td>
            <td class="right">{{$o->menge}}</td>
            <td class="status status_{{$o->status_id}}"><span>{{$o->status()->first()->bezeichnung}}</span></td>
            <td><form method="get" action="/verwaltung/details"><input type="hidden" name="id" value="{{$o->id}}" /><input type="submit" class="button small" value="Details" /></form>
            <form  method="get" action="/verwaltung/bestellung_kopieren"><input type="hidden" name="id" value="{{$o->id}}" /><input onclick="return confirm('Möchten Sie die Bestellung erneut aufgeben?')" style="background: green;" type="submit" class="button small" value="erneut bestellen" /></form></td>


            @if ($mandant->bestNr == 1)
            <td align="left" style="text-align: left;width: 190px;">

                <input  onfocus="showsave('{{$o->id}}')" type="text" id="{{$o->id}}" value="{{$o->bestellnummer}}" name="bestellnummer" placeholder="Bestell-Nr. anlegen" />

                <img onclick="setBestellnummer('{{$o->id}}')" id="save_{{$o->id}}" style="display: none;cursor: pointer;margin-top: 2px;" src="../img/icons/save.png" />
                <img id="saved_{{$o->id}}" style="display: none;cursor: pointer;margin-top: 2px;" src="../img/icons/saved.png" />

            </td>
            @endif
        </tr>
        @endforeach
    </table>



    <p class="paging">
            <?php if($page!=0): ?>
            <a href="<?=URL::current();?>?page=<?=$page-1?>">zurück</a>
            <?php endif; ?>

            <?php for($a=0;$a<$pages;$a++): ?>
            <a <?php if($a==$page){ echo 'class="active" '; } ?> href="<?=URL::current();?>?page=<?=$a?>"><?=$a+1?></a>
            <?php endfor; ?>

            <?php if($page!=($pages-1)): ?>
            <a href="<?=URL::current();?>?page=<?=$page+1?>">weiter</a>
            <?php endif; ?>

    </p>
</div>

@endsection