@extends('master')

@section('head')
{{ HTML::script('/js/jquery-1.10.1.min.js') }}
{{ HTML::script('/js/lib.js?r='.$__rev) }}
{{ HTML::script('/js/visika-view-0.1.js?r='.$__rev) }}
{{ HTML::style('/css/edit.css?r='.$__rev) }}
<script type="text/javascript">
data = <?php echo json_encode($data); ?>;
$(document).ready(function() {
    new vViewer({
        svgContainer: document.getElementById('preview'),
        data: data,
        scale: <?php echo $vorlage->scale; ?>
    });
});
</script>
@endsection

@section('content')
<?php $isEn = ($mandant->sprache == 'en'); ?>

<div style="float:left; width: 500px">

@if(empty($pdfUrl))

    <?php if($mandantid==2||$mandantid==3): ?>
        <h1>{{ $isEn ? 'Thank you for your order.' : 'Vielen Dank für Deine Bestellung.' }}</h1>
    <?php else: ?>
        <h1>{{ $isEn ? 'Thank you for your order.' : 'Vielen Dank für Ihre Bestellung.' }}</h1>
    <?php endif; ?>

    <form method="get" action="/auswahl">
        <table>
            <tr>
                <?php if($mandantid==2||$mandantid==3): ?>
                    <td>{{ $isEn ? 'Your order number:' : 'Deine Auftragsnummer:' }}</td>
                <?php else: ?>
                    <td>{{ $isEn ? 'Your order number:' : 'Ihre Auftragsnummer:' }}</td>
                <?php endif; ?>
                <td class="number">{{ $order->id }}</td>
            </tr>
            <tr>
                <td>{{ $isEn ? 'Order quantity:' : 'Bestellmenge:' }}</td>
                <td class="number">{{ $order->menge }}</td>
            </tr>
        </table>

        <?php if($mandantid==2||$mandantid==3): ?>
            <p>
                {{ $isEn ? 'You can check your order status at:' : 'Du kannst Deinen Bestellstatus unter' }}
            </p>
            <p><a href="{{ $statusUrl }}" class="link">{{ $statusUrl }}</a></p>
        <?php else: ?>
            <p>
                {{ $isEn ? 'You can check your order status at:' : 'Sie können Ihren Bestellstatus unter' }}
            </p>
            <p><a href="{{ $statusUrl }}" class="link">{{ $statusUrl }}</a></p>
        <?php endif; ?>

        <?php
        if (isset($infotext)) {
            echo $infotext;
        }
        ?>

        @if($user->sendmail)
            <?php if($mandantid==2||$mandantid==3): ?>
                <p>{{ $isEn ? 'You will also receive an e-mail with this information.' : 'Du erhältst auch eine E-Mail mit diesen Informationen.' }}</p>
            <?php else: ?>
                <p>{{ $isEn ? 'You will also receive an e-mail with this information.' : 'Sie erhalten auch eine E-Mail mit diesen Informationen.' }}</p>
            <?php endif; ?>
        @endif

@endif

        @if(!empty($pdfUrl))
            <strong>Create print PDF:</strong><br> Click on "Print-PDF" The system will then provide you with a print-ready PDF file.
            <br><br>
            <strong>Download print PDF:</strong><br> Download the created print PDF to your end device. Save the file in a location where you can easily find it again and forward it.
            <br><br>
            <strong>Select a print shop:</strong><br> Research a print shop of your choice that offers business cards in your desired format and quality.
            <br><br>
            <strong>Send PDF to the print shop:</strong><br> Upload the print PDF according to the specifications of the chosen print shop or send it by email to the respective print shop staff.
            <br><br>
             <p>
                <a href="{{ $pdfUrl }}" target="_blank"
                   style="
                       display:inline-block;
                       padding:12px 24px;
                       font-size:16px;
                       font-weight:bold;
                       color:#fff;
                       text-decoration:none;
                       background:linear-gradient(135deg, #4CAF50, #45a049);
                       border-radius:8px;
                       box-shadow:0 4px 6px rgba(0,0,0,0.2);
                       transition:all 0.3s ease;
                   "
                   onmouseover="this.style.background='linear-gradient(135deg,#45a049,#3e8e41)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.3)';"
                   onmouseout="this.style.background='linear-gradient(135deg,#4CAF50,#45a049)';this.style.boxShadow='0 4px 6px rgba(0,0,0,0.2)';"
                >
                    📄 {{ $isEn ? 'Print PDF with 3mm bleed' : 'Print PDF mit 3mm Beschnitt' }}
                </a>
                <br><br>
                <a href="{{ $pdfUrl2 }}" target="_blank"
                   style="
                       display:inline-block;
                       padding:12px 24px;
                       font-size:16px;
                       font-weight:bold;
                       color:#fff;
                       text-decoration:none;
                       background:linear-gradient(135deg, #4CAF50, #45a049);
                       border-radius:8px;
                       box-shadow:0 4px 6px rgba(0,0,0,0.2);
                       transition:all 0.3s ease;
                   "
                   onmouseover="this.style.background='linear-gradient(135deg,#45a049,#3e8e41)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.3)';"
                   onmouseout="this.style.background='linear-gradient(135deg,#4CAF50,#45a049)';this.style.boxShadow='0 4px 6px rgba(0,0,0,0.2)';"
                >
                    📄 {{ $isEn ? 'Print PDF without bleed' : 'Print-PDF ohne Beschnitt' }}
                </a>
            </p>
        @else

            <input class="button" type="submit" value="{{ $isEn ? 'Place another order' : 'zu einer weiteren Bestellung' }}" />
        @endif

    </form>
</div>

<div id="preview" style="float:right">
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
