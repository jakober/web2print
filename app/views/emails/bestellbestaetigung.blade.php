@extends('emails/emailmaster')

@section('content')
<h1>{{ $isEn ? 'Thank you for your order.' : 'Vielen Dank für Ihre Bestellung.' }}</h1>

<table>
    <tr>
        <td>{{ $isEn ? 'Your order number:' : 'Ihre Auftragsnummer:' }}</td>
        <td class="number">{{ $order->id }}</td>
    </tr>
    <tr>
        <td>{{ $isEn ? 'Order quantity:' : 'Bestellmenge:' }}</td>
        <td class="number">{{ $order->menge }}</td>
    </tr>
</table>

<p>
    {{ $isEn ? 'You can check your order status at:' : 'Sie können Ihren Bestellstatus unter' }}
</p>
<p><a href="{{ $statusUrl }}" class="link">{{ $statusUrl }}</a></p>
@if(!$isEn)
    <p>einsehen.</p>
@endif

@if(isset($infotext))
    {!! $infotext !!}
@endif
@endsection
