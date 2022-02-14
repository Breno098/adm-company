<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Pagamento</title>

    <style>
      * {
        font-family: Arial, Helvetica, sans-serif
      }
    </style>
  </head>
  <body>

    <div style="border: 0.5px solid black; padding: 10px 0.5px 50px">
      <div style="text-align: center; background: #eee; padding: 10px; font-size: 25px">
        Recibo de Pagamento
      </div>

      <div style="margin: 30px; display: flex; justify-content: space-between"
      >
        <div style="line-height: 0.8;">
          <p> Nome: {{ $employeeReceipt->employee->name ?? '' }} </p>
          <p> Cargo/Função: {{ $employeeReceipt->employee->position->name ?? '' }} </p>

          @isset($employeeReceipt->employee->cpf)
            <p> CPF: {{ $employeeReceipt->employee->cpf }} </p>
          @endisset

          @isset($employeeReceipt->employee->rg)
            <p> RG: {{ $employeeReceipt->employee->rg }} </p>
          @endisset
        </div>
        <div>
          @isset($url)
            <img src="{{$url}}" style="width: 150px; height: 80px; margin: 0 500px"/>
          @endisset
        </div>
      </div>

      <div style="text-align: justify; margin: 30px; line-height: 1.6;">
        <p>
          Recebi de Desentupidora Crispim, a quantia de <b>{{ $employeeReceipt->amount_to_currency }}</b> ({{ strtoupper($employeeReceipt->full_amount_in_full) }}),
          referente ao trabalho executado entre <b>{{ $employeeReceipt->date_start_format }}</b> e <b>{{ $employeeReceipt->date_end_format }}</b>, dando-lhe por este recibo a devida quitação.
        </p>
      </div>

      <div style="text-align: center; margin: 30px">
        __________________________________________
        <p style="margin-top: 10px">{{ $employeeReceipt->employee->name }}</p>
      </div>

      <div style="text-align: right; margin: 80px 15px 0; font-size: 15px">
        Local e Data: ________________ , ____ de ________________ de ____
      </div>
    </div>

  </body>
</html>
