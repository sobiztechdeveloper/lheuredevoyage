   <div class="header-top">
       <div class="container">
           <div class="row align-items-center">
               <div class="col-md-7">
                   <div class="header-top-left">
                       <!-- <div class="top-social">
                           <a href="#"><i class="fab fa-facebook-f"></i></a>
                           <a href="#"><i class="fab fa-x-twitter"></i></a>
                           <a href="#"><i class="fab fa-instagram"></i></a>
                           <a href="#"><i class="fab fa-linkedin-in"></i></a>
                       </div> -->
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
                           <select name="currency" class="select">
                               <option value="1">USD</option>
                               <option value="2">EUR</option>
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