@if(isset($faqs) && $faqs->isNotEmpty())
<div class="faq-area py-80">
    <div class="container">
        <div class="site-heading text-center mb-4">
            <span class="site-title-tagline"><i class="far fa-question-circle"></i> FAQ</span>
            <h2 class="site-title">Frequently Asked Questions</h2>
        </div>
        <div class="accordion" id="faqAccordion">
            @foreach($faqs as $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq-heading-{{ $faq->id }}">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}">
                            {{ $faq->question }}
                        </button>
                    </h2>
                    <div id="faq-collapse-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{{ $faq->answer }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
