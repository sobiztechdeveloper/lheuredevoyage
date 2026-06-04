@php
    $contact = $contactDetail ?? $siteContact ?? null;
    $settings = $siteSettings ?? \App\Models\WebsiteSetting::cached();
    $address = $contact?->address ?: $settings->company_address;
    $phone = $contact?->phone ?: $settings->company_phone;
    $email = $contact?->email ?: $settings->company_email;
    $whatsapp = $contact?->whatsapp_number ?? null;
@endphp
@if($address)
<div class="contact-info">
    <div class="contact-info-icon"><i class="far fa-map-marker-alt"></i></div>
    <div class="contact-info-content">
        <h5>Office Address</h5>
        <p>{{ $address }}</p>
    </div>
</div>
@endif
@if($phone)
<div class="contact-info">
    <div class="contact-info-icon"><i class="far fa-phone"></i></div>
    <div class="contact-info-content">
        <h5>Call Us</h5>
        <p><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></p>
    </div>
</div>
@endif
@if($email)
<div class="contact-info">
    <div class="contact-info-icon"><i class="far fa-envelope"></i></div>
    <div class="contact-info-content">
        <h5>Email Us</h5>
        <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
    </div>
</div>
@endif
@if($whatsapp)
<div class="contact-info">
    <div class="contact-info-icon"><i class="fab fa-whatsapp"></i></div>
    <div class="contact-info-content">
        <h5>WhatsApp</h5>
        <p><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener noreferrer">{{ $whatsapp }}</a></p>
    </div>
</div>
@endif
