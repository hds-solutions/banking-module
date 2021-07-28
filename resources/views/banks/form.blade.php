@include('backend::components.errors')

<x-backend-form-text :resource="$resource ?? null" name="name" required
    label="banking::bank.name.0"
    placeholder="banking::bank.name._"
    {{-- helper="banking::bank.name.?" --}} />

<x-backend-form-controls
    submit="banking::banks.save"
    cancel="banking::banks.cancel" cancel-route="backend.banks" />
