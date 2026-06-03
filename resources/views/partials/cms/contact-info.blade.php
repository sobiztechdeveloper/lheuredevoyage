@php $contact = $contactDetail ?? $siteContact ?? null; @endphp
@if($contact)
    @if($contact->address)
    <div class="contact-info">
        <div class="contact-info-icon"><i class="far fa-map-marker-alt"></i></div>
        <div class="contact-info-content">
            <h5>Office Address</h5>
            <p>{{ $contact->address }}</p>
        </div>
    </div>
    @endif
    @if($contact->phone)
    <div class="contact-info">
        <div class="contact-info-icon"><i class="far fa-phone"></i></div>
        <div class="contact-info-content">
            <h5>Call Us</h5>
            <p><a href="tel:{{ preg_replace('/\s+/', '', $contact->phone) }}">{{ $contact->phone }}</a></p>
        </div>
    </div>
    @endif
    @if($contact->email)
    <div class="contact-info">
        <div class="contact-info-icon"><i class="far fa-envelope"></i></div>
        <div class="contact-info-content">
            <h5>Email Us</h5>
            <p><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
        </div>
    </div>
    @endif
    @if($contact->whatsapp_number)
    <div class="contact-info">
        <div class="contact-info-icon"><i class="fab fa-whatsapp"></i></div>
        <div class="contact-info-content">
            <h5>WhatsApp</h5>
            <p><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp_number) }}" target="_blank" rel="noopener">{{ $contact->whatsapp_number }}</a></p>
        </div>
    </div>
    @endif
@endif
