<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo</title>

    <style>
      * {
        font-family: Arial, Helvetica, sans-serif
      }
    </style>
  </head>
  <body>

    <div style="border: 0.5px solid black; padding: 10px 0.5px 50px">

      <div style="display: flex; justify-content: space-between; margin-bottom: 10px">
          @isset($url)
            <img src="{{$url}}" style="width: 150px; height: 80px; margin: 5px 10px"/>
          @endisset

        <div style="line-height: 0.5; margin-left: 350px; font-size: 12px">
          <p> LEONOR PENNACHIOTTI GALLO, 350 | PQ, FLAMBOYANS </p>
          <p> RIBEIRÃO PRETO - SP | CEP 14093-651 </p>
          <p> CNPJ 17.107.361/0001-34 </p>
          <p> desentupidoracrispim@gmail.com </p>
          <p> (16) 99187-8532 </p>
        </div>
      </div>

      <div style="text-align: center; background: #eee; padding-top: 5px; font-size: 22px">
        RECIBO
      </div>

      <div style="text-align: center; background: #eee; padding: 3px; font-size: 12px">
        {{ $order->id }}
      </div>

      <div style="padding: 10px; font-size: 12px">
        <p>
          <strong> CLIENTE: </strong> {{ $order->client->name }}
        </p>

        <p>
          <strong> ENDEREÇO: </strong>
          {{ $order->address->street ? "R. {$order->address->street} nº {$order->address->number}" : '' }}
          {{ $order->address->district ?? '' }}
          {{ $order->address->block ? "| BLOCO: {$order->address->block}" : '' }}
          {{ $order->address->tower ? "| TORRE: {$order->address->tower}" : '' }}
          {{ $order->address->floor ? "| ANDAR: {$order->address->floor}" : '' }}
          {{ $order->address->apartment ? "| AP.: {$order->address->apartment}" : '' }}
          {{ $order->address->house ? "| CASA: {$order->address->house}" : '' }}
        </p>
      </div>

      <div style="padding: 20px 15px 50px; font-size: 12px">
        @if($order->client)
          RECEBI DE {{ $order->client->name }}
        @endif

        @if($order->address)
          COM ENDEREÇO
          {{ $order->address->street ? "R. {$order->address->street}" : '' }}
          {{ $order->address->number ? "Nº {$order->address->number}" : '' }}
          {{ $order->address->district ? ", {$order->address->district}" : '' }}
          {{ $order->address->city ? $order->address->city : '' }}
          {{ $order->address->state ? " - {$order->address->state}" : '' }}
          {{ $order->address->cep ? " ({$order->address->cep}) " : '' }}

          O VALOR DE {{ $order->amount_to_currency }} ({{ $order->amount_to_currency_extensive }})
          REFERENTE A PAGAMENTO REALIZADO


          @foreach ($order->payments ?? [] as $key => $payment)
            @if ($loop->first)
                {{ "( {$payment->name}" }}
            @else
              {{ " E {$payment->name}" }}
            @endif

            @if ($loop->last)
                {{ ")" }}
            @endif
          @endforeach
        @endif
      </div>

      <div style="background: #eee; padding: 10px; font-size: 20px; text-align: left">
        Valor: {{ $order->amount_to_currency }}
      </div>

      <div style="text-align: center; font-size: 10px; margin-top: 100px">
        {{ now()->format('d/m/Y') }}
      </div>
    </div>

  </body>
</html>
