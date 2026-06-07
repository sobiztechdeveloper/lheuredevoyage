   <div class="header-top">
       <div class="container">
           <div class="row align-items-center">
               <div class="col-md-7">
                   <div class="header-top-left">
                       @if(($siteSettings ?? null)?->facebook_url || ($siteSettings ?? null)?->instagram_url || ($siteSettings ?? null)?->linkedin_url || ($siteSettings ?? null)?->youtube_url)
                       <div class="top-social">
                           @if($siteSettings->facebook_url)<a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
                           @if($siteSettings->instagram_url)<a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>@endif
                           @if($siteSettings->linkedin_url)<a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>@endif
                           @if($siteSettings->youtube_url)<a href="{{ $siteSettings->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>@endif
                       </div>
                       @endif
                       <div class="top-contact-info">
                           <ul>
                               @if(($siteSettings ?? null)?->company_phone)
                               <li><a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->company_phone) }}"><i class="far fa-phone-arrow-down-left"></i>{{ $siteSettings->company_phone }}</a></li>
                               @endif
                               @if(($siteSettings ?? null)?->company_email)
                               <li><a href="mailto:{{ $siteSettings->company_email }}"><i class="far fa-envelopes"></i>{{ $siteSettings->company_email }}</a></li>
                               @endif
                           </ul>
                       </div>
                   </div>
               </div>
               <div class="col-md-5">
                   <div class="header-top-right">
                       <div class="lang">
                           <select name="lang" class="select">
                               <option value="1">ENG</option>
                               <option value="2">FRA</option>
                               <option value="3">DEU</option>
                           </select>
                       </div>
                       <div class="currency">
                           <select name="currency" id="header-currency" class="select" data-switch-url="{{ route('currency.switch') }}" aria-label="Display currency">
                               <option value="CHF" @selected(display_currency() === 'CHF')>CHF</option>
                               <option value="USD" @selected(display_currency() === 'USD')>USD</option>
                           </select>
                       </div>
                       @guest
                       <div class="account">
                           <a href="{{ route('login') }}"><i class="far fa-sign-in"></i>Login</a>
                           <a href="{{ route('register') }}"><i class="far fa-user-tie"></i>Sign Up</a>
                       </div>
                       @endguest
                   </div>
               </div>
           </div>
       </div>
   </div>