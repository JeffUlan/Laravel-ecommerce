<div class="footer">
    <div class="footer-content">
        <div class="footer-list-container">
            <div class="list-container">
                <span class="list-heading">Categories</span>

                <ul class="list-group">
                    @foreach($categories as $key => $category)
                    <li>{{ $category['name'] }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="list-container">
                <span class="list-heading">Quick Links</span>

                <ul class="list-group">
                    <li>About Us</li>
                    <li>Return Policy</li>
                    <li>Refund Policy</li>
                    <li>Terms and conditions</li>
                    <li>Terms of Use</li>
                    <li>Contact Us</li>
                </ul>
            </div>

            <div class="list-container">
                <span class="list-heading">Connect With Us</span>

                <ul class="list-group">
                    <li><span class="icon-wrapper"><span class="icon icon-dashboard"></span></span>Facebook</li>
                    <li><span class="icon-wrapper"><span class="icon icon-dashboard"></span></span>Twitter</li>
                    <li><span class="icon-wrapper"><span class="icon icon-dashboard"></span></span>Instagram</li>
                    <li><span class="icon-wrapper"><span class="icon icon-dashboard"></span></span>Google+</li>
                    <li><span class="icon-wrapper"><span class="icon icon-dashboard"></span></span>LinkedIn</li>

                </ul>
            </div>

            <div class="list-container">
                <span class="list-heading">{{ __('shop::app.footer.subscribe-newsletter') }}</span>
                <div class="form-container">
                    <div class="control-group">
                        <input type="text" class="control subscribe-field" placeholder="Email Address"><br/>
                        <button class="btn btn-md btn-primary">{{ __('shop::app.footer.subscribe') }}</button>
                    </div>
                </div>

                <span class="list-heading">{{ __('shop::app.footer.locale') }}</span>
                <div class="form-container">
                    <div class="control-group">
                        <select class="control locale-switcher" onchange="window.location.href = this.value">

                            @foreach (core()->getCurrentChannel()->locales as $locale)
                                <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
