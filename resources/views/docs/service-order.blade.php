<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ordem de Serviço {{ $order->id }}</title>

    <style>
       * {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        padding: 0;
        margin: 10px;
      }

      body {
        padding: 0;
        margin: 10px;
      }

      table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        margin: 0;
      }

      strong {
        margin: 0;
      }

      tr, td {
        padding: 3.5px 5px;
        width: 16.66%;
      }

      .line-write {
        padding: 11px;
      }

      .line-money {
        padding: 11px 5px;
      }

       .line-signature {
        padding: 30px 5px 5px
      }

      .text-write{
        padding: 0px;
        width: 100%;
        margin: 0;
        text-decoration: underline;
        line-height: 21px
      }

    </style>
  </head>
  <body>
      <table style="width: 100%">
        <tr>
          <td colspan="1" style="text-align: center">
            @isset($url)
              <img src="{{$url}}" style="height: 60px; width: 110px"/>
            @endisset
          </td>

          <td colspan="4" style="text-align: center">
            <span> CONDOMINÍOS E RESIDÊNCIAS, SERVIÇOS DE ENCANAMENTO EM GERAL </span>
          </td>

          <td colspan="1" style="text-align: center">
              <p style="font-size: 11px; font-weight: bold">16 99187-8532</p>
              <p style="font-size: 8px;">LEONOR P. GALLO, 350</p>
              <p style="font-size: 8px;">RIBEIRÃO PRETO - SP</p>
          </td>
          </td>
        </tr>
        <tr style="border: 0">
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
        </tr>
        <tr>
          <td colspan="6" style="font-size: 16px; font-weight: bold">
            NÚMERO DA ORDEM DE SERVIÇO: {{ $order->id }}
          </td>
        </tr>
        <tr>
          <td colspan="6"> <strong> CLIENTE: </strong> {{ $order->client->name }} </td>
        </tr>
        <tr>
          <td colspan="6">
            <strong> ENDEREÇO: </strong>
            @if($order->address)
              {{ $order->address->street ? "R. {$order->address->street} nº {$order->address->number}" : '' }}
              {{ $order->address->block ? "| BLOCO: {$order->address->block}" : '' }}
              {{ $order->address->tower ? "| TORRE: {$order->address->tower}" : '' }}
              {{ $order->address->floor ? "| ANDAR: {$order->address->floor}" : '' }}
              {{ $order->address->apartment ? "| AP.: {$order->address->apartment}" : '' }}
              {{ $order->address->house ? "| CASA: {$order->address->house}" : '' }}
            @endif
          </td>
        </tr>

        <tr>
          <td colspan="3"> <strong> BAIRRO:  </strong> {{ optional($order->address)->district }} </td>
          <td colspan="3"> <strong> CIDADE:  </strong> {{ optional($order->address)->city }} </td>
        </tr>

        <tr>
          <td colspan="3"> <strong> PROGRAMAÇÃO: </strong> {{ $order->programation_date_format }} </td>
          <td colspan="3"> <strong> TELEFONE(S): </strong> {{ $order->phones }} </td>
        </tr>

        {{-- <tr>
          <td colspan="6"> <strong> VISTORIA PRÉVIA DO LOCAL </strong> </td>
        </tr> --}}

        <tr>
          <td colspan="6">
            <span>VIS. TÉCNICA (&nbsp;&nbsp;&nbsp;)</span>
            <span>EXECUÇÃO (&nbsp;&nbsp;&nbsp;)</span>
            <span>GARANTIA (&nbsp;&nbsp;&nbsp;)</span>
          </td>
        </tr>

        <tr>
          <td colspan="6"> <strong> PROBLEMA RECLAMADO: </strong> {{ $order->complaint }}</td>
        </tr>

        <tr>
          <td colspan="6"><strong>LOCALIZAÇÃO E DESCRIÇÃO DO PROBLEMA CONSTATADO E SUA CAUSA:</strong></td>
        </tr>

        @php
          $words = explode(' ', $order->work_found);
          $countLength = 0;
          $countLine = 1;
          $maxLines = 6;
          $count = 0;
        @endphp

        @while ($count < count($words) && $countLine <= $maxLines && $order->work_found)
          @if($countLength === 0)
            <tr><td colspan="6">
          @endif

          @if($countLength + strlen($words[$count]) >= 89)
              </td></tr>
            @php $countLength = 0; $countLine++; $count--; @endphp
          @else
            @php $countLength += strlen($words[$count]); @endphp
            {{ $words[$count] }}
          @endif

          @php $count++; @endphp
        @endwhile

        @if(($maxLines - $countLine) > 0)
          @foreach (range(1, $order->work_found ? $maxLines - $countLine : $maxLines) as $line)
            <tr>
              <td colspan="6" class="line-write"></td>
            </tr>
          @endforeach
        @endif

        <tr>
          <td colspan="3" rowspan="2">
            AUTORIZAÇÃO PRÉVIA: DECLARO TER ACEITO AS INFORMAÇÕES ACIMA DESCRITAS, AUTORIZO PREVIAMENTE O PRESTADOR DE SERVIÇOS
            A EXECUTAR OS REPAROS NECESSÁRIOS, SOB CONDIÇÕES EXPOSTAS.
          </td>
          <td colspan="3"><strong> DATA/HORA: </strong></td>
        </tr>

        <tr>
          <td colspan="3">  <strong> ASSINATURA RESPONSÁVEL: </strong> </td>
        </tr>

        <tr>
          <td colspan="6"><strong> DESCRIÇÃO DO SERVIÇO EXECUTADO (PARA CONHECIMENTO E AVAL DO RESPONSÁVEL)</strong></td>
        </tr>

        @php
          $words = explode(' ', $order->work_done);
          $countLength = 0;
          $countLine = 1;
          $maxLines = 6;
          $count = 0;
        @endphp

        @while ($count < count($words) && $countLine <= $maxLines && $order->work_done)
          @if($countLength === 0)
            <tr><td colspan="6">
          @endif

          @if($countLength + strlen($words[$count]) >= 89)
              </td></tr>
            @php $countLength = 0; $countLine++; $count--; @endphp
          @else
            @php $countLength += strlen($words[$count]); @endphp
            {{ $words[$count] }}
          @endif

          @php $count++; @endphp
        @endwhile

        @if(($maxLines - $countLine) > 0)
          @foreach (range(1, $order->work_done ? $maxLines - $countLine : $maxLines) as $line)
            <tr>
              <td colspan="6" class="line-write"></td>
            </tr>
          @endforeach
        @endif

        <tr>
          <td colspan="1" class="line-money"> <strong> MÃO DE OBRA </strong> </td>
          <td colspan="2" class="line-money"> R$ </td>
          <td colspan="3" rowspan="2"> <strong> TOTAL R$ </strong> </td>
        </tr>

        <tr>
          <td colspan="1" class="line-money"> <strong> MATERIAIS </strong> </td>
          <td colspan="2" class="line-money"> R$ </td>
        </tr>

        <tr>
          <td colspan="6">
            <strong> FORMAS DE PAGAMENTO:  </strong>
              @foreach ($order->accepted_payment_methods_explode as $payment_method)
              <span>
                ( ){{ $payment_method }}
              </span>
              @endforeach
          </td>
        </tr>

        <tr>
          <td colspan="6"> <strong> FORNECIDO POR: ( ) PRESTADOR  ( ) CLIENTE </strong> </td>
        </tr>

        <tr>
          <td colspan="6"> OBS.: ORÇAMENTOS NÃO APROVADOS, SERÁ COBRADO O VALOR DA VISITA TÉCNICA R$ 30,00 </td>
        </tr>

        <tr>
          <td colspan="6">
            DECLARO QUE ACOMPANHEI E APROVEI A EXECUÇÃO DO SERVIÇO REALIZADO POR PROFISSIONAIS DEVIDADAMENTE UNIFORMIZADOS E
            TODOS OS TESTES FORAM EFETUADOS CONSTATANDO QUE O SERVIÇO FOI REALIZADO A CONTENTO E NÃO HÁ NENHUM DANO.
            DECLARO TAMBÉM QUE FUI ORIENTADO(A) SOBRE A UTILIZAÇÃO DO LOCAL ONDE FOI EFETUADO  O SERVIÇO E QUE RECEBI UMA VIA DESTE DOCUMENTO.
          </td>
        </tr>

        <tr>
          <td colspan="2"> <strong> NOME PRESTADOR/ASSINATURA: </strong> </td>
          <td colspan="4"> <strong> LOCAL-DATA-HORA: </strong> </td>
        </tr>

        <tr>
          <td colspan="2" rowspan="2"> </td>
          <td colspan="4"> <strong> NOME DO RESPONSÁVEL: </strong> </td>
        </tr>

        <tr>
          <td colspan="4" class="line-signature"> <strong> ASSINATURA: </strong> </td>
        </tr>

      </table>
    </div>
  </body>
</html>
