{{--
    ERP MODULE: Authentication — Field Error Component
    COMPONENT: FieldError
    DESCRIPTION: Reusable hidden error message paragraph. Shown via JS when validation fails. Accepts $field variable for the input name.
    USAGE: @include('pages.customer.auth.components.shared.field-error', ['field' => 'email'])
--}}
<p id="error-{{ $field }}" class="text-xs text-red-500 mt-1 hidden"></p>