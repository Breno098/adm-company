<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo</title>

    <style>
      * {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        padding: 0;
        margin: 10px;
      }

      table {
        width: 97%;
        /* border: 0.5px solid black; padding: 10px 0.5px 50px; */
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
          <td colspan="6" style="text-align: center; font-size: 20px; padding: 10px; color: #000"> RECIBO {{ $order->id }} </td>
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
          <td colspan="6" style="padding: 50px 15px">
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
            @endif
          </td>
        </tr>

        <tr>
          <td colspan="6" style="font-size: 20px; padding: 15px 5px">
            VALOR: {{ $order->amount_to_currency }}
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
