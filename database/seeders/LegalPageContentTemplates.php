<?php

namespace Database\Seeders;

/**
 * Default legal page HTML templates (Switzerland / EU travel agency).
 * Placeholders are replaced at display time via LegalPageService.
 */
class LegalPageContentTemplates
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function pages(): array
    {
        return [
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'sort_order' => 1,
                'summary' => 'General terms governing use of our website and travel services.',
                'meta_title' => 'Terms & Conditions | [COMPANY NAME]',
                'meta_description' => 'Read the terms and conditions for using [COMPANY NAME] travel booking services in Switzerland and internationally.',
                'content' => self::termsContent(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'sort_order' => 2,
                'summary' => 'How we collect, use and protect your personal data under Swiss and European data protection law.',
                'meta_title' => 'Privacy Policy | [COMPANY NAME]',
                'meta_description' => 'Privacy policy explaining how [COMPANY NAME] processes personal data, passport information and booking details.',
                'content' => self::privacyContent(),
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'sort_order' => 3,
                'summary' => 'Information about cookies and similar technologies used on this website.',
                'meta_title' => 'Cookie Policy | [COMPANY NAME]',
                'meta_description' => 'Learn which cookies [COMPANY NAME] uses and how to manage your cookie preferences.',
                'content' => self::cookieContent(),
            ],
            [
                'title' => 'Booking Conditions',
                'slug' => 'booking-conditions',
                'sort_order' => 4,
                'summary' => 'Conditions applicable to flight, hotel, cruise, car rental and insurance booking requests.',
                'meta_title' => 'Booking Conditions | [COMPANY NAME]',
                'meta_description' => 'Booking conditions for travel requests and quotations with [COMPANY NAME].',
                'content' => self::bookingContent(),
            ],
            [
                'title' => 'Cancellation & Refund Policy',
                'slug' => 'cancellation-policy',
                'sort_order' => 5,
                'summary' => 'Cancellation, modification and refund rules for travel services.',
                'meta_title' => 'Cancellation & Refund Policy | [COMPANY NAME]',
                'meta_description' => 'Cancellation and refund policy for bookings made through [COMPANY NAME].',
                'content' => self::cancellationContent(),
            ],
            [
                'title' => 'Disclaimer',
                'slug' => 'disclaimer',
                'sort_order' => 6,
                'summary' => 'Limitations of liability and informational disclaimers.',
                'meta_title' => 'Disclaimer | [COMPANY NAME]',
                'meta_description' => 'Legal disclaimer for website content and travel information provided by [COMPANY NAME].',
                'content' => self::disclaimerContent(),
            ],
            [
                'title' => 'Company Information',
                'slug' => 'company-information',
                'sort_order' => 7,
                'summary' => 'Legal and contact information about our company.',
                'meta_title' => 'Company Information | [COMPANY NAME]',
                'meta_description' => 'Company information, registration details and contact data for [COMPANY NAME].',
                'content' => self::companyContent(),
            ],
        ];
    }

    protected static function termsContent(): string
    {
        return <<<'HTML'
<h2>1. Introduction</h2>
<p>These Terms &amp; Conditions ("Terms") govern your access to and use of the website operated by <strong>[COMPANY NAME]</strong> ("we", "us", "our"), a travel agency based in Switzerland. By accessing our website or submitting a booking request, you agree to these Terms.</p>

<h2>2. Services</h2>
<p>We provide travel consultancy and booking request services including flights, hotels, cruises, rental cars, travel insurance and holiday packages. Unless expressly confirmed in writing, online submissions are <strong>booking requests</strong> and not confirmed reservations.</p>

<h2>3. Account &amp; Accuracy</h2>
<p>You must provide accurate contact details and travel information. You are responsible for passport validity, visas, health requirements and insurance appropriate to your destination.</p>

<h2>4. Prices &amp; Quotations</h2>
<p>Displayed prices are indicative. Final quotations may differ due to availability, taxes, surcharges or supplier rules. A quotation is valid only for the period stated on the quote document.</p>

<h2>5. Payment</h2>
<p>Payment terms are communicated when a booking is confirmed. We may use secure third-party payment providers where applicable.</p>

<h2>6. Liability</h2>
<p>To the extent permitted by Swiss law, we are not liable for indirect losses or events beyond our reasonable control. Our liability for confirmed services is limited as set out in our Booking Conditions and supplier terms.</p>

<h2>7. Governing Law</h2>
<p>These Terms are governed by Swiss law. The courts at our registered office in Switzerland shall have exclusive jurisdiction, subject to mandatory consumer protections in your country of residence where applicable.</p>

<h2>8. Contact</h2>
<p><strong>[COMPANY NAME]</strong><br>[COMPANY ADDRESS]<br>Email: <a href="mailto:[COMPANY EMAIL]">[COMPANY EMAIL]</a><br>Phone: [COMPANY PHONE]</p>
HTML;
    }

    protected static function privacyContent(): string
    {
        return <<<'HTML'
<h2>1. Data Controller</h2>
<p><strong>[COMPANY NAME]</strong>, [COMPANY ADDRESS], is the data controller for personal data collected through this website. Contact: <a href="mailto:[COMPANY EMAIL]">[COMPANY EMAIL]</a>.</p>

<h2>2. Data We Collect</h2>
<ul>
<li>Identity and contact data (name, email, phone, WhatsApp)</li>
<li>Travel and booking details (destinations, dates, preferences)</li>
<li>Passport and identity document information where required for bookings</li>
<li>Uploaded passport copies and supporting documents</li>
<li>Technical data (IP address, browser, cookies — see Cookie Policy)</li>
</ul>

<h2>3. Purposes &amp; Legal Bases</h2>
<p>We process data to handle booking requests, provide quotations, fulfil contracts, communicate with you, comply with legal obligations, and improve our services. Processing is based on contract performance, consent, legitimate interests and legal duties under the Swiss Federal Act on Data Protection (FADP) and, where applicable, the EU GDPR.</p>

<h2>4. Retention</h2>
<p>We retain booking and identity data as long as necessary for the travel service, legal retention periods, and dispute resolution.</p>

<h2>5. Sharing</h2>
<p>We share data with airlines, hotels, insurers and other suppliers only as needed to process your request. Processors are bound by confidentiality and data protection agreements.</p>

<h2>6. International Transfers</h2>
<p>Where data is transferred outside Switzerland or the EEA, we implement appropriate safeguards such as standard contractual clauses or adequacy decisions.</p>

<h2>7. Your Rights</h2>
<p>You may request access, correction, deletion, restriction, portability or object to processing. Contact <a href="mailto:[COMPANY EMAIL]">[COMPANY EMAIL]</a>. You may lodge a complaint with the Swiss Federal Data Protection and Information Commissioner (FDPIC).</p>

<h2>8. Security</h2>
<p>We apply technical and organisational measures to protect personal data, including access controls and secure document handling.</p>
HTML;
    }

    protected static function cookieContent(): string
    {
        return <<<'HTML'
<h2>1. What Are Cookies?</h2>
<p>Cookies are small text files stored on your device when you visit our website. We also use similar technologies such as local storage for cookie consent preferences.</p>

<h2>2. Categories</h2>
<table class="table table-bordered">
<thead><tr><th>Category</th><th>Purpose</th><th>Required</th></tr></thead>
<tbody>
<tr><td>Necessary</td><td>Security, session, consent storage</td><td>Yes</td></tr>
<tr><td>Analytics</td><td>Website traffic and performance</td><td>No</td></tr>
<tr><td>Marketing</td><td>Advertising and remarketing</td><td>No</td></tr>
<tr><td>Preferences</td><td>Remember your settings</td><td>No</td></tr>
</tbody>
</table>

<h2>3. Managing Cookies</h2>
<p>On your first visit you can accept all cookies, reject non-essential cookies, or open <a href="/cookie-settings">Cookie Settings</a> to choose categories. You may change preferences at any time.</p>

<h2>4. Contact</h2>
<p>Questions: <a href="mailto:[COMPANY EMAIL]">[COMPANY EMAIL]</a></p>
HTML;
    }

    protected static function bookingContent(): string
    {
        return <<<'HTML'
<h2>1. Booking Requests</h2>
<p>Submissions via our website create a <strong>booking request</strong>. Confirmation occurs only after our consultant verifies availability, fare and supplier conditions.</p>

<h2>2. Documents</h2>
<p>You must provide accurate passenger and passport details. Incorrect information may result in denied boarding, cancellation fees or additional charges.</p>

<h2>3. Quotations</h2>
<p>Quotes are non-binding until accepted according to our quote process. Prices may change before ticket or voucher issuance.</p>

<h2>4. Third-Party Suppliers</h2>
<p>Travel services are subject to airline, hotel, cruise line, car rental and insurer terms. We will communicate applicable supplier conditions where relevant.</p>

<h2>5. Insurance</h2>
<p>Travel insurance is strongly recommended. Coverage depends on the selected policy wording.</p>
HTML;
    }

    protected static function cancellationContent(): string
    {
        return <<<'HTML'
<h2>1. General</h2>
<p>Cancellation and refund rights depend on the fare rules, supplier policies and timing of your request. Fees may apply.</p>

<h2>2. Requests</h2>
<p>Contact us at <a href="mailto:[COMPANY EMAIL]">[COMPANY EMAIL]</a> or [COMPANY PHONE] with your booking reference. We will advise applicable charges before processing.</p>

<h2>3. Refunds</h2>
<p>Refunds, if permitted, are processed to the original payment method within a reasonable period after supplier approval.</p>

<h2>4. Force Majeure</h2>
<p>Events beyond reasonable control may affect cancellation options according to supplier and legal rules.</p>
HTML;
    }

    protected static function disclaimerContent(): string
    {
        return <<<'HTML'
<h2>1. Website Information</h2>
<p>Content on this website is for general information only. We endeavour to keep information accurate but do not warrant completeness or timeliness.</p>

<h2>2. Travel Advice</h2>
<p>Entry requirements, health advice and safety information may change. Verify with official authorities before travel.</p>

<h2>3. External Links</h2>
<p>Links to third-party websites are provided for convenience. We are not responsible for their content or practices.</p>

<h2>4. Limitation</h2>
<p>To the maximum extent permitted by law, [COMPANY NAME] excludes liability for reliance on website content except where liability cannot be excluded under mandatory law.</p>
HTML;
    }

    protected static function companyContent(): string
    {
        return <<<'HTML'
<p>The following company details are maintained in our website settings and displayed dynamically on this page.</p>
<p>For enquiries regarding bookings, quotations or data protection, please use the contact details shown below.</p>
HTML;
    }
}
