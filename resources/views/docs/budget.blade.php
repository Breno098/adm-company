<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orçamento</title>

    <style>
      * {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        padding: 0;
        margin: 10px;
      }

      table {
        width: 97%;
      }

      table, th, td {
        /* border: 1px solid black; */
        border-collapse: collapse;
      }

      tr, td {
        padding: 3px 5px;
        width: 16.66%
      }

      .line-write {
        padding: 11px
      }

      strong {
        margin: 0;
      }

   </style>
  </head>
  <body>
    <table>
      <tbody>
        @component('docs.components.header', [
          'urlLogo' => $urlLogo,
          'company' => $company
        ])
        @endcomponent

        <tr style="background: #eee;">
          <td colspan="6" style="text-align: center; font-size: 20px; padding: 10px; color: #000"> ORÇAMENTO {{ $order->id }} </td>
        </tr>

        <tr>
          <td colspan="6"> <strong> CLIENTE: </strong> {{ $order->client->name }} </td>
        </tr>

        @if($order->address_id)
          <tr>
            <td colspan="6">
              <strong> ENDEREÇO: </strong>
              {{ $order->address->street ? "R. {$order->address->street} nº {$order->address->number}" : '' }}
              {{ $order->address->block ? "| BLOCO: {$order->address->block}" : '' }}
              {{ $order->address->tower ? "| TORRE: {$order->address->tower}" : '' }}
              {{ $order->address->floor ? "| ANDAR: {$order->address->floor}" : '' }}
              {{ $order->address->apartment ? "| AP.: {$order->address->apartment}" : '' }}
              {{ $order->address->house ? "| CASA: {$order->address->house}" : '' }}
            </td>
          </tr>

          <tr>
            <td colspan="3"> <strong> BAIRRO:  </strong> {{ $order->address->district }} </td>
            <td colspan="3"> <strong> CIDADE:  </strong> {{ $order->address->city }} </td>
          </tr>
        @endif

        @if($order->phones)
          <tr>
            <td colspan="6">
              <strong> TELEFONE(S): </strong> {{ $order->phones }}
            </td>
          </tr>
        @endif

        @if($order->emails)
          <tr>
            <td colspan="6">
              <strong> EMAIL(S): </strong> {{ $order->emails }}
            </td>
          </tr>
        @endif

        <tr>
          <td colspan="6">
            <strong> DATA VISITA TÉCNICA: </strong> {{ $order->programation_date_format }}
          </td>
        </tr>

        <tr>
          <td colspan="6" class="line-write"> </td>
        </tr>

        @if($order->services->count())
          <tr style="background: #eee;">
            <td colspan="6" style="text-align: center; font-size: 18px; padding: 8px; color: #000"> MÃO DE OBRA </td>
          </tr>


          <tr>
            <td colspan="2" style="text-align: left; font-weight: bold">DESCRIÇÃO</td>
            <td style="text-align: left; font-weight: bold">VALOR</td>
            <td style="text-align: left; font-weight: bold">QUANTIDADE</td>
            <td colspan="2" style="text-align: right; font-weight: bold">TOTAL</td>
          </tr>

          @foreach ($order->services ?? [] as $service)
            <tr style="font-size: 12px; line-height: 150%">
              <td colspan="2"> - {{ $service->name }} </td>
              <td> R$ {{ number_format($service->value ?? 0, 2, ",", ".") }} </td>
              <td> {{ $service->quantity }} </td>
              @php
                $total = $service->value && $service->quantity ? number_format($service->value * $service->quantity, 2, ",", ".") : '0,00';
              @endphp
              <td colspan="2" style="text-align: right"> {{ "R$ {$total}" }} </td>
            </tr>
          @endforeach
        @endif

        <tr>
          <td colspan="6" class="line-write"> </td>
        </tr>

        @if($order->products->count())
          <tr style="background: #eee;">
            <td colspan="6" style="text-align: center; font-size: 18px; padding: 8px; color: #000"> MATERIAIS </td>
          </tr>

          <tr>
            <td colspan="2" style="text-align: left; font-weight: bold">DESCRIÇÃO</td>
            <td style="text-align: left; font-weight: bold">VALOR</td>
            <td style="text-align: left; font-weight: bold">QUANTIDADE</td>
            <td colspan="2" style="text-align: right; font-weight: bold">TOTAL</td>
          </tr>

          @foreach ($order->products ?? [] as $product)
            <tr style="font-size: 12px; line-height: 150%">
              <td colspan="2"> - {{ $product->name }} </td>
              <td> R$ {{ number_format($product->value ?? 0, 2, ",", ".") }} </td>
              <td> {{ $product->quantity }} </td>
              @php
                $total = $product->value && $product->quantity ? number_format($product->value * $product->quantity, 2, ",", ".") : '0,00';
              @endphp
              <td colspan="2" style="text-align: right"> {{ "R$ {$total}" }} </td>
            </tr>
          @endforeach
        @endif

        <tr>
          <td colspan="6" class="line-write"> </td>
        </tr>

        @if($order->discount_amount)
          <tr>
            <td colspan="6" style="font-size: 13px; padding: 10px 5px; text-align: right">
              Desconto: {{ $order->discount_amount_to_currency }}
            </td>
          </tr>
        @endif

        <tr>
          <td colspan="6" style="font-size: 20px; padding: 15px 5px; text-align: right">
            VALOR: {{ $order->amount_to_currency }}
          </td>
        </tr>

        <tr>
          <td colspan="6" class="line-write"> </td>
        </tr>

        <tr>
          <td colspan="6" class="line-write"> </td>
        </tr>

        <tr style="background: #eee;">
          <td colspan="6" style="text-align: center; font-size: 15px; padding: 8px; color: #000"> FORMAS DE PAGAMENTO </td>
        </tr>

        <tr>
          <td colspan="6" style="text-align: center">
            @foreach ($order->accepted_payment_methods_explode as $payment_method)
            <span>
              (&nbsp;&nbsp;&nbsp;) {{ $payment_method }}
            </span>
            @endforeach
          </td>
        </tr>

        <tr>
          <td colspan="6" style="font-size: 20px;">
            @isset($urlSignature)
              <div style="width: 100%; text-align: center; margin-top: 35px;">
                <img src="{{$urlSignature}}" style="width: 220px height: 120px; border-bottom: 0.5px solid black;" alt="signature"/>
              </div>
            @endisset

            <div style="width: 100%; text-align: center; margin: 55px 0 15px">
              {{ $company->name }}
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div style="text-align: center; font-size: 10px; margin-top: 100px">
      {{ now()->format('d/m/Y') }}
    </div>
  </body>
</html>
