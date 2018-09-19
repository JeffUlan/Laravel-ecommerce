<div class="header" id="header">
    <div class="header-top">
        <div class="left-content">

            <ul class="logo-container">
                <li>
                    <a href="{{ route('store.home') }}">
                        <img class="logo" src="{{ asset('vendor/webkul/shop/assets/images/logo.svg') }}" />
                    </a>
                </li>
            </ul>

            <ul class="search-container">
                <li class="search-group">
                    <input type="search" class="search-field" placeholder="Search for products">
                    <div class="search-icon-wrapper">
                        <span class="icon search-icon"></span>
                    </div>
                </li>
            </ul>

        </div>

        <div class="right-content">

            {{-- Triggered on responsive mode only --}}
            <ul class="search-dropdown-container">
                <li class="search-dropdown">

                </li>
            </ul>

            <ul class="account-dropdown-container">

                <li class="account-dropdown">

                    <span class="icon account-icon"></span>

                    <div class="dropdown-toggle">

                        <div style="display: inline-block; cursor: pointer;">
                            <span class="name">Account</span>
                        </div>

                        <i class="icon arrow-down-icon active"></i>

                    </div>

                    @guest
                        <div class="dropdown-list bottom-right" style="display: none;">

                            <div class="dropdown-container">

                                <label>Account</label>

                                <ul>
                                    <li><a href="{{ route('customer.session.index') }}">Sign In</a></li>

                                    <li><a href="{{ route('customer.register.index') }}">Sign Up</a></li>
                                </ul>

                            </div>

                        </div>
                    @endguest
                    @auth('customer')
                        <div class="dropdown-list bottom-right" style="display: none;">
                            <div class="dropdown-container">
                                <label>Account</label>
                                <ul>
                                    <li><a href="{{ route('customer.account.index') }}">Account</a></li>

                                    <li><a href="{{ route('customer.profile.index') }}">Profile</a></li>

                                    <li><a href="{{ route('customer.address.index') }}">Address</a></li>

                                    <li><a href="{{ route('customer.wishlist.index') }}">Wishlist</a></li>

                                    {{-- <li><a href="{{ route('customer.cart') }}">Cart</a></li> --}}
                                    <li><a href="{{ route('customer.orders.index') }}">Orders</a></li>

                                    <li><a href="{{ route('customer.session.destroy') }}">Logout</a></li>
                                </ul>

                            </div>

                        </div>
                    @endauth

                </li>

            </ul>

            <cart-dropdown @if(isset($cart)) :items='@json($cart)' @endif></cart-dropdown>

            {{-- Meant for responsive views only --}}
            <ul class="ham-dropdown-container">

                <li class="ham-dropdown">
                    {{-- use this section for the dropdown of the hamburger menu --}}
                </li>

            </ul>

        </div>
        <div class="right-responsive">

            <ul class="right-wrapper">
                <li class="search-box"><span class="icon search-icon" id="search"></span></li>
                <li class="account-box"><span class="icon account-icon"></span></li>
                <li class="cart-box"><span class="icon cart-icon"></span></li>
                <li class="menu-box" ><span class="icon sortable-icon" id="sortable"></span></li>
            </ul>
        </div>
    </div>


    {{-- Triggered on responsive mode only --}}

    <div class="search-suggestion">
        <div class="search-content">
            <span class="icon search-icon"></span>
            <span> Sarees India  </span>
            <span class="icon search-icon right"></span>
        </div>

        <div class="suggestion">
            <span>Designer sarees</span>
        </div>
        <div class="suggestion">
            <span>India patter sarees</span>
        </div>
        <div class="suggestion">
            <span>Border Sarees</span>
        </div>
    </div>

    <div class="header-bottom">
    @include('shop::layouts.header.nav-menu.navmenu')
    </div>

</div>

@push('scripts')
    <script>

    </script>
@endpush