<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

/**
 * EventTicket Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class EventTicket extends Booking
{
    /**
     * Returns event date
     *
     * @param BookingProduct $bookingProduct
     * @return string
     */
    public function getEventDate($bookingProduct)
    {
        $from = Carbon::createFromTimeString($bookingProduct->available_from)->format('d F, Y h:i A');

        $to = Carbon::createFromTimeString($bookingProduct->available_to)->format('d F, Y h:i A');

        return $from . ' - ' . $to;
    }

    /**
     * Returns tickets
     *
     * @param BookingProduct $bookingProduct
     * @return array
     */
    public function getTickets($bookingProduct)
    {
        if (! $bookingProduct->event_tickets()->count()) {
            return;
        }

        return $this->formatPrice($bookingProduct->event_tickets);
    }

    /**
     * Format ticket price
     *
     * @param array $tickets
     * @return array
     */
    public function formatPrice($tickets)
    {
        foreach ($tickets as $index => $ticket) {
            $tickets[$index]['id'] = $ticket->id;
            $tickets[$index]['converted_price'] = core()->convertPrice($ticket->price);
            $tickets[$index]['formated_price'] = $formatedPrice = core()->currency($ticket->price);
            $tickets[$index]['formated_price_text'] = trans('bookingproduct::app.shop.products.per-ticket-price', ['price' => $formatedPrice]);
        }

        return $tickets;
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param array $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        foreach ($products as $key => $product) {
            $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $product['product_id']);

            $ticket = $bookingProduct->event_tickets()->find(array_keys($product['additional']['booking']['qty'])[0]);

            $products[$key]['price'] += core()->convertPrice($ticket->price);
            $products[$key]['base_price'] += $ticket->price;
            $products[$key]['total'] += (core()->convertPrice($ticket->price) * $products[$key]['quantity']);
            $products[$key]['base_total'] += ($ticket->price * $products[$key]['quantity']);
        }

        return $products;
    }

    /**
     * Validate cart item product price
     *
     * @param CartItem $item
     * @return float
     */
    public function validateCartItem($item)
    {
        $price = $item->product->getTypeInstance()->getFinalPrice();

        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $item->product_id);

        $ticket = $bookingProduct->event_tickets()->find(array_keys($item->additional['booking']['qty'])[0]);

        $price += $ticket->price;

        if ($price == $item->base_price) {
            return;
        }

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}