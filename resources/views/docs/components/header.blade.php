<tr style="border-top: none; padding: 0">
  @foreach (range(0, 5) as $key)
    <td style="border: none;"></td>
  @endforeach
</tr>

<tr>
  <td colspan="2" style="text-align: center">
    @isset($urlLogo)
      <img src="{{$urlLogo}}" style="height: 80px; width: 150px" alt="logo"/>
    @endisset
  </td>

  <td colspan="4" style="text-align: center">
    <p> {{ $company->name }} | CNPJ: {{ $company->cnpj }}</p>

    @if($company->addressMain)
      <p>
        {{ $company->addressMain->street ? "R. {$company->addressMain->street} nº {$company->addressMain->number}" : '' }}
        {{ $company->addressMain->block ? "| bloco: {$company->addressMain->block}" : '' }}
        {{ $company->addressMain->tower ? "| torre: {$company->addressMain->tower}" : '' }}
        {{ $company->addressMain->floor ? "| andar: {$company->addressMain->floor}" : '' }}
        {{ $company->addressMain->apartment ? "| ap.: {$company->addressMain->apartment}" : '' }}
        {{ $company->addressMain->house ? "| casa: {$company->addressMain->house}" : '' }}
        {{ $company->addressMain->district ? ", {$company->addressMain->district}" : '' }}
      </p>
    @endif

    @if($company->addressMain)
      <p>
        {{ $company->addressMain->city ? "{$company->addressMain->city}" : '' }}
        {{ $company->addressMain->state ? " - {$company->addressMain->state}" : '' }}
      </p>
    @endif

    <p>{{ $company->phones_label }}</p>

    <p>{{ $company->emails_label }}</p>
</tr>
