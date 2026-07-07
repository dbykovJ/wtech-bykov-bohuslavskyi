@php use App\Helpers\CountryList; @endphp

<select id="{{ $id ?? 'country' }}" name="{{ $name ?? 'country' }}" {{ $required ?? false ? 'required' : '' }}>
    <option value="" disabled {{ empty($selected) ? 'selected' : '' }}>Оберіть країну</option>
    @foreach( CountryList::all() as $code => $name)
        <option value="{{ $code }}" {{ ($selected ?? '') === $code ? 'selected' : '' }}>
            {{ $name }}
        </option>
    @endforeach
</select>
