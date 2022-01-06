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
      }

      body {
        padding: 10px;
      }

      table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
      }

      tr, td {
        padding: 3px 5px;
        width: 16.66%
      }

      .line-write {
        padding: 11px
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
        @endif
        @if($order->address_id)
          <tr>
            <td colspan="3"> <strong> CIDADE:  </strong> {{ $order->address->city }} </td>
            <td colspan="3"> <strong> BAIRRO:  </strong> {{ $order->address->district }} </td>
          </tr>
        @endif

        <tr>
          <td colspan="3"> <strong> PROGRAMAÇÃO: </strong> {{ $order->programation_date_format }} </td>
          <td colspan="3"> <strong> TELEFONE(S): </strong> {{ $order->phones }} </td>
        </tr>
        <tr>
          <td colspan="6"> <strong> PROBLEMA RECLAMADA: </strong>  </td>
        </tr>

        <tr>
          <td colspan="6"> <strong> VISTORIA PRÉVIA DO LOCAL </strong> </td>
        </tr>

        @if($order->description)
          <tr>
            <td colspan="6"> OBSERVAÇÕES: {{ $order->description }} </td>
          </tr>
        @endif

        @if(!$order->description)
          <tr>
            <td colspan="6"> OBSERVAÇÕES: </td>
          </tr>
          @foreach (range(0,2) as $line)
            <tr>
              <td colspan="6" class="line-write"> </td>
            </tr>
          @endforeach
        @endif

        <tr>
          <td colspan="6"> LOCALIZAÇÃO E DESCRIÇÃO DO PROBLEMA CONSTATADO E SUA CAUSA </td>
        </tr>
        @foreach (range(0,4) as $line)
          <tr>
            <td colspan="6" class="line-write"> </td>
          </tr>
        @endforeach

        <tr>
          <td colspan="3" rowspan="2">
            AUTORIZAÇÃO PRÉVIA: DECLARO TER ACEITO AS INFORMAÇÕES ACIMA DESCRITAS, AUTORIZO PREVIAMENTE O PRESTADOR DE SERVIÇOS
            A EXECUTAR OS REPAROS NECESSÁRIOS, SOB CONDIÇÕES EXPOSTAS.
          </td>
          <td colspan="3">  <strong> DATA/HORA: </strong></td>
        </tr>

        <tr>
          <td colspan="3">  <strong> ASSINATURA RESPONSÁVEL: </strong> </td>
        </tr>

        <tr>
          <td colspan="6"> DESCRIÇÃO DO SERVIÇO EXECUTADO (PARA CONHECIMENTO E AVAL DO RESPONSÁVEL) </td>
        </tr>

        @foreach (range(0,7) as $line)
          <tr>
            <td colspan="6" class="line-write"> </td>
          </tr>
        @endforeach

        <tr>
          <td colspan="1"> <strong> MÃO DE OBRA </strong> </td>
          <td colspan="2"> R$ </td>
          <td colspan="3" rowspan="2"> <strong> TOTAL R$ </strong> </td>
        </tr>

        <tr>
          <td colspan="1"> <strong> MATERIAIS </strong> </td>
          <td colspan="2"> R$ </td>
        </tr>

        <tr>
          <td colspan="6">
            <strong> FORMAS DE PAGAMENTO:  </strong>
              @foreach ($order->formOfPayments as $payment)
              <span>
                ( ){{ $payment->name }}
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
          <td colspan="4"> <strong> ASSINATURA: </strong> </td>
        </tr>

      </table>
    </div>
  </body>
</html>
