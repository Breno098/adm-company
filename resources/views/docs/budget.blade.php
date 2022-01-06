<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orçamento</title>

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

      <div style="text-align: center; background: #eee; padding: 10px; font-size: 25px">
        ORÇAMENTO
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

        @if($order->phones)
          <p>
            <strong> TELEFONE(S): </strong> {{ $order->phones }}
          </p>
        @endif

        @if($order->emails)
          <p>
            <strong> EMAIL(S): </strong> {{ $order->emails }}
          </p>
        @endif

        <p>
          <strong> DATA VISITA TÉCNICA: </strong> {{ $order->programation_date_format }}
        </p>
      </div>

      @if($order->services)
        <div style="text-align: center; background: #eee; padding: 10px; font-size: 18px">
          SERVIÇOS | MÃO DE OBRA
        </div>

        <div style="padding: 10px">
          <table style="width: 100%">
              <thead>
                <tr style="font-size: 13px">
                  <th style="width: 50%; text-align: left">DESCRIÇÃO</th>
                  <th style="width: 17%; text-align: left">VALOR</th>
                  <th style="width: 15%; text-align: left">QUANTIDADE</th>
                  <th style="width: 23%">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->services ?? [] as $service)
                  <tr style="font-size: 12px; line-height: 150%">
                    <td> - {{ $service->name }} </td>
                    <td> {{ $service->value ? "R$ {$service->value}" : '' }} </td>
                    <td> {{ $service->quantity }} </td>
                    <td style="text-align: right"> {{ $service->value && $service->quantity ? "R$ " . $service->value * $service->quantity : '' }} </td>
                  </tr>
                @endforeach
              </tbody>
          </table>
        </div>
      @endif

      @if($order->products)
        <div style="text-align: center; background: #eee; padding: 10px; font-size: 18px">
          PRODUTOS
        </div>

        <div style="padding: 10px">
          <table style="width: 100%">
              <thead>
                <tr style="font-size: 13px">
                  <th style="width: 50%; text-align: left">DESCRIÇÃO</th>
                  <th style="width: 17%; text-align: left">VALOR</th>
                  <th style="width: 15%; text-align: left">QUANTIDADE</th>
                  <th style="width: 23%">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->products ?? [] as $product)
                  <tr style="font-size: 12px; line-height: 150%">
                    <td> - {{ $product->name }} </td>
                    <td> {{ $product->value ? "R$ {$product->value}" : '' }} </td>
                    <td> {{ $product->quantity }} </td>
                    <td style="text-align: right"> {{ $product->value && $product->quantity ? "R$ " . $product->value * $product->quantity : '' }} </td>
                  </tr>
                @endforeach
              </tbody>
          </table>
        </div>
      @endif

      @if($order->discount_amount)
        <div style="padding: 10px; font-size: 12px; text-align: right; margin-top: 20px">
          Desconto: {{ "R$ {$order->discount_amount}" }}
        </div>
      @endif

      <div style="background: #eee; padding: 10px; font-size: 15px; text-align: right">
        Valor total: {{ $order->amount ? "R$ {$order->amount}" : ''}}
      </div>

      <div style="padding: 10px; font-size: 11px; margin-top: 10px">
        <strong> FORMAS DE PAGAMENTO:  </strong>
          @foreach ($order->formOfPayments as $payment)
          <span>
            ( ){{ $payment->name }}
          </span>
          @endforeach
      </div>

      <div style="text-align: center; font-size: 10px; margin-top: 100px">
        {{ now()->format('d/m/Y') }}
      </div>
    </div>

  </body>
</html>
