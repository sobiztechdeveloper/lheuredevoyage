<?php

return [
    'plan_types' => [
        'single_trip' => 'Single Trip',
        'annual_multi_trip' => 'Annual Multi Trip',
        'schengen_visa' => 'Schengen Visa',
        'student' => 'Student Insurance',
        'family' => 'Family Insurance',
        'business' => 'Business Travel',
        'senior' => 'Senior Travel',
    ],

    'coverage_currencies' => ['CHF', 'EUR', 'USD'],

    'travel_purposes' => [
        'tourism' => 'Tourism',
        'business' => 'Business',
        'study' => 'Study',
        'work' => 'Work',
        'family_visit' => 'Family Visit',
        'pilgrimage' => 'Pilgrimage',
    ],

    'request_statuses' => [
        'new' => 'New',
        'under_review' => 'Under Review',
        'waiting_customer_documents' => 'Waiting Customer Documents',
        'quoted' => 'Quoted',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'policy_issued' => 'Policy Issued',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'customer_document_types' => [
        'passport' => 'Passport',
        'visa' => 'Visa',
        'student_letter' => 'Student Letter',
        'medical' => 'Medical Documents',
    ],

    'admin_document_types' => [
        'policy' => 'Policy Document',
        'coverage_certificate' => 'Coverage Certificate',
        'invoice' => 'Invoice',
        'claim_instructions' => 'Claim Instructions',
    ],

    'cms_blocks' => [
        'faq' => 'Insurance FAQs',
        'terms' => 'Insurance Terms',
        'claims' => 'Claim Instructions',
        'emergency' => 'Emergency Contacts',
        'advice' => 'Travel Advice',
    ],
];
