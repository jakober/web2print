@extends('master')
@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::script('/js/jsqr-0.2-min.js')}}
{{HTML::script('/js/lib.js?r='.$__rev)}}
{{HTML::style('/css/edit.css?r='.$__rev)}}
<?php
echo '<script type="text/javascript">svgconfig = ';
echo json_encode(json_decode(file_get_contents(public_path() . '/visika/' . $vorlage->template_folder . '/config.json'))), ";\n";
$f = public_path() . '/visika/' . $vorlage->template_folder . '/data.json';
echo 'var values = ', isset($input) ? json_encode($input) : '{}', ';';
echo '</script>';
?>
{{HTML::script('/js/visika-edit-0.1.js?r='.$__rev)}}
{{HTML::script('/visika/' . $vorlage->template_folder . '/handler.js?r='.$__rev.'&v=1.5')}}
<script type="text/javascript">
    $(document).ready(function() {
        var cfg = {
            svgConfig: svgconfig,
            svgContainer: document.getElementById('preview'),
            formContainer: document.getElementById('inputs')
        };
        cfg.button = document.getElementById('submit');
        cfg.values = values;
        cfg.handler = new handler();

        myEditor = new vEditor(cfg);
        if (svgconfig.scale) {
            myEditor.setSVGScale(svgconfig.scale);
        }

        var submit = $('#submit');

        submit.click(function() {
            if (submit.hasClass('disabled')) {
                return;
            }
            if (myEditor.validateForm()) {
                var r = myEditor.submitData({
                    url: '/gestaltung/save',
                    success: function() {
                        <?php if(!empty($download_only)): ?>
                              location.href = '/bestellung';
                        <?php else: ?>
                            location.href = '/anschrift';
                        <?php endif; ?>

                    }
                });
            }
        });
    });
</script>

<script>
function getInternetExplorerVersion()
{
    var rV = -1; // Return value assumes failure.

    if (navigator.appName == 'Microsoft Internet Explorer' || navigator.appName == 'Netscape') {
        var uA = navigator.userAgent;
        var rE = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

        if (rE.exec(uA) != null) {
            rV = parseFloat(RegExp.$1);
        }
        /*check for IE 11*/
        else if (!!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            rV = 11;
        }
    }
    return rV;
}


function areYouIE(){
        alertText = "";

    var iEVersion = getInternetExplorerVersion();
    if(iEVersion != -1){
        alertText = "Veralteter Browser: Sie verwenden den abgekündigten Browser Internet-Explorer 11. Bei der Anzeige der Mobilnummer kann es hier zu Darstellungsproblemen kommen. Bitte verwenden Sie einen aktuelleren Browser wie z.B. Microsoft Edge, Chrome oder Firefox.";
        $('#warning').show();
    }
    $('#warning').html(alertText);
}
$(document).ready(function(){
    areYouIE();
});


</script>

@endsection

@section('content')

<?php $user = Session::get('user'); $isEn = ($user && !empty($user->lang) && $user->lang == 'en'); ?>

<p style="max-width: 50%;">
    @if($mandant->sprache === 'en')
        Please enter your personal and business information in the fields below. Make sure that all information is correct before continuing.
    @endif
</p>

<div class="form">
    <div id="inputs"></div>
    <div class="buttons">





            <input class="button" type="button" value="&raquo; @if($mandant->sprache === 'en') next step @else weiter @endif" id="submit" />


    </div>
    <div id="warning" style="display: none;border: 1px solid red;padding: 20px;max-width: 450px;margin-top: 50px;">

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

</div>


@endsection