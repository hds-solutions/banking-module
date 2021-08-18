<div class="col-12 d-flex mb-1">
    <x-form-foreign name="checks[]"
        :values="$checks" default="{{ $old ?? $selected?->id }}"
        show="bank.name document_number - payment_amount_pretty"
        append="amount:payment_amount"

        {{-- foreign="checks" foreign-add-label="products-catalog::checks.add" --}}

        label="banking::reconciliation.checks.check_id.0"
        placeholder="banking::reconciliation.checks.check_id._"
        {{-- helper="banking::reconciliation.checks.check_id.?" --}} />

    <button type="button" class="btn btn-danger ml-2"
        data-action="delete"
        @if ($selected !== null)
        data-confirm="Eliminar @lang('Check')?"
        data-text="Esta seguro de eliminar la @lang('Check') {{ $selected->document_numbner }}?"
        data-accept="Si, eliminar"
        @endif>X</button>
</div>
