<section class="featured-products">
    <div class="featured-heading">
        Featured Products<br/>
        <span class="featured-seperator" style="color:lightgrey;">_____</span>
    </div>

    <div class="featured-grid product-grid-4">
        @for($i=0; $i<4; $i++)
        <div class="product-card">
            <div class="product-image">
                <img src="vendor/webkul/shop/assets/images/grid.png" />
            </div>
            <div class="product-name">
                <span>Red Black Tees</span>
            </div>
            <div class="product-price">
                <span>$65.00</span>
            </div>
            <div class="product-ratings mb-10">
                <span>
                    <img src="vendor/webkul/shop/assets/images/5star.svg" />
                </span>
            </div>
            <div class="cart-fav-seg">
                <button class="btn btn-md btn-primary addtocart">Add to Cart</button>
                <span><img src="vendor/webkul/shop/assets/images/wishadd.svg" /></span>
            </div>
        </div>
        @endfor
    </div>
</section>
