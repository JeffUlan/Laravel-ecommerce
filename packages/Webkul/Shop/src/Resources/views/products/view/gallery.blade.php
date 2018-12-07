@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')
<?php $images = $productImageHelper->getGalleryImages($product); ?>

{!! view_render_event('bagisto.shop.products.view.gallery.before', ['product' => $product]) !!}

<div class="product-image-group">

    <div class="cp-spinner cp-round" id="loader">
    </div>

    <product-gallery></product-gallery>

    @include ('shop::products.view.product-add')

</div>

{!! view_render_event('bagisto.shop.products.view.gallery.after', ['product' => $product]) !!}

@push('scripts')

    <script type="text/x-template" id="product-gallery-template">
        <div>

            <ul class="thumb-list">
                <li class="gallery-control top" @click="moveThumbs('top')" v-if="thumbs.length > 4">
                    <span class="overlay"></span>
                    <i class="icon arrow-up-white-icon"></i>
                </li>

                <li class="thumb-frame" v-for='(thumb, index) in thumbs' @click="changeImage(thumb)" :class="[thumb.large_image_url == currentLargeImageUrl ? 'active' : '']">
                    <img :src="thumb.small_image_url" />
                </li>

                <li class="gallery-control bottom" @click="moveThumbs('bottom')" v-if="thumbs.length > 4">
                    <span class="overlay"></span>
                    <i class="icon arrow-down-white-icon"></i>
                </li>
            </ul>

            <div class="product-hero-image" id="product-hero-image">
                <img :src="currentLargeImageUrl" id="pro-img"/>

                {{-- Uncomment the line below for activating share links --}}
                {{-- @include('shop::products.sharelinks') --}}
                @auth('customer')
                    <a class="add-to-wishlist" href="{{ route('customer.wishlist.add', $product->id) }}">
                    </a>
                @endauth
            </div>
        </div>
    </script>

    <script>
        var galleryImages = @json($images);

        Vue.component('product-gallery', {

            template: '#product-gallery-template',

            data: () => ({
                images: galleryImages,

                thumbs: [],

                currentLargeImageUrl: ''
            }),

            watch: {
                'images': function(newVal, oldVal) {
                    this.changeImage(this.images[0])

                    this.prepareThumbs()
                }
            },

            created () {
                this.changeImage(this.images[0])

                this.prepareThumbs()
            },

            methods: {
                prepareThumbs () {
                    var this_this = this;

                    this_this.thumbs = [];

                    this.images.forEach(function(image) {
                        this_this.thumbs.push(image);
                    });
                },

                changeImage (image) {
                    this.currentLargeImageUrl = image.large_image_url;
                },

                moveThumbs(direction) {
                    let len = this.thumbs.length;

                    if (direction === "top") {
                        const moveThumb = this.thumbs.splice(len - 1, 1);

                        this.thumbs = [moveThumb[0], ...this.thumbs];
                    } else {
                        const moveThumb = this.thumbs.splice(0, 1);

                        this.thumbs = [...this.thumbs, moveThumb[0]];
                    }
                },
            }
        });

    </script>

@endpush
