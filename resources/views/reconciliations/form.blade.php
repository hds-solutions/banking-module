@include('backend::components.errors')

<x-backend-form-text name="document_number" required
    :resource="$resource ?? null" :default="$highs['document_number'] ?? null"

    label="banking::reconciliation.document_number.0"
    placeholder="banking::reconciliation.document_number._"
    {{-- helper="banking::reconciliation.document_number.?" --}} />

<x-backend-form-datetime name="transacted_at" required
    :resource="$resource ?? null" default="{{ now() }}"

    label="banking::reconciliation.transacted_at.0"
    placeholder="banking::reconciliation.transacted_at._"
    {{-- helper="banking::reconciliation.transacted_at.?" --}} />

<x-backend-form-multiple name="checks"
    :values="$checks" :selecteds="isset($resource) ? $resource->checks : []"
    contents-view="banking::reconciliations.form.check" data-type="reconciliation"
    label="banking::reconciliation.checks.0" />

<x-backend-form-controls
    submit="banking::reconciliations.save"
    cancel="banking::reconciliations.cancel"
        cancel-route="{{ isset($resource)
            ? 'backend.reconciliations.show:'.$resource->id
            : 'backend.reconciliations' }}" />
