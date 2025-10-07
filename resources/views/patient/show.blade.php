<ul class="nav nav-pills animation-nav nav-justified mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patient_treatments.index')) active @endif || @if (request()->routeIs('patient_treatments.create')) active @endif"
            href="{{ route('patient_treatments.index', $id) }}" role="tab">
            Patient Treatments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('quotation.index')) active @endif || @if (request()->routeIs('quotation.create')) active @endif"
            href="{{ route('quotation.index', $id) }}" role="tab">
            Quotation
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patientconcernform.index')) active @endif || @if (request()->routeIs('patientconcernform.create')) active @endif"
            href="{{ route('patientconcernform.index', $id) }}" role="tab">
            Consent Form
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('document.index')) active @endif" href="{{ route('document.index', $id) }}"
            role="tab">
            Documents
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('labworks.index')) active @endif" href="{{ route('labworks.index', $id) }}"
            role="tab">
            Lab work
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('prescriptions.index')) active @endif || @if (request()->routeIs('prescriptions.create')) active @endif || @if (request()->routeIs('prescriptions.edit')) active @endif"
            href="{{ route('prescriptions.index', $id) }}" role="tab">
            Prescription
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patient_notes.index')) active @endif"
            href="{{ route('patient_notes.index', $id) }}" role="tab">
            Notes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('orders.index')) active @endif || @if (request()->routeIs('orders.create')) active @endif"
            href="{{ route('orders.index', $id) }}" role="tab">
            Invoice
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('payments.index')) active @endif || @if (request()->routeIs('payments.edit')) active @endif"
            href="{{ route('payments.index', $id) }}" role="tab">
            Payments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (request()->routeIs('patient_appointment.index')) active @endif || @if (request()->routeIs('patient_appointment.create')) active @endif || @if (request()->routeIs('patient_appointment.edit')) active @endif"
            href="{{ route('patient_appointment.index', $id) }}" role="tab">
            Appointments
        </a>
    </li>
</ul>
