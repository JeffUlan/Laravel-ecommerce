<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

/**
 * RentalSlot Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RentalSlot extends Booking
{
    /**
     * Returns slots for a perticular day
     *
     * @param BookingProduct $bookingProduct
     * @param string         $date
     * @return array
     */
    public function getSlotsByDate($bookingProduct, $date)
    {
        $bookingProductSlot = $this->typeRepositories[$bookingProduct->type]->findOneByField('booking_product_id', $bookingProduct->id);

        if (! is_array($bookingProductSlot->slots) || ! count($bookingProductSlot->slots))
            return [];

        $requestedDate = Carbon::createFromTimeString($date . " 00:00:00");

        $currentTime = Carbon::now();

        $availableFrom = ! $bookingProductSlot->available_every_week
                ? Carbon::createFromTimeString($bookingProductSlot->available_from . ' 00:00:00')
                : Carbon::createFromTimeString($currentTime->format('Y-m-d') . ' 00:00:00');

        $availableTo = ! $bookingProductSlot->available_every_week
                ? Carbon::createFromTimeString($bookingProductSlot->available_to . ' 23:59:59')
                : Carbon::createFromTimeString('2080-01-01 00:00:00');

        $timeDurations = $bookingProductSlot->same_slot_all_days
                ? $bookingProductSlot->slots
                : $bookingProductSlot->slots[$requestedDate->format('w')];

        $slots = [];

        foreach ($timeDurations as $index => $timeDuration) {
            $fromChunks = explode(':', $timeDuration['from']);
            $toChunks = explode(':', $timeDuration['to']);

            $startDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $startDayTime->addMinutes(($fromChunks[0] * 60) + $fromChunks[1]);
            $tempStartDayTime = clone $startDayTime;

            $endDayTime = Carbon::createFromTimeString($requestedDate->format('Y-m-d') . ' 00:00:00');
            $endDayTime->addMinutes(($toChunks[0] * 60) + $toChunks[1]);

            while (1) {
                $from = clone $tempStartDayTime;
                $tempStartDayTime->addMinutes(60);

                $to = clone $tempStartDayTime;

                if (($startDayTime <= $from && $from <= $availableTo)
                    && ($availableTo >= $to && $to >= $startDayTime)
                    && ($startDayTime <= $from && $from <= $endDayTime)
                    && ($endDayTime >= $to && $to >= $startDayTime)) {
                        
                    // Get already ordered qty for this slot
                    $orderedQty = 0;

                    $qty = isset($timeDuration['qty']) ? ( $timeDuration['qty'] - $orderedQty ) : 1;

                    if ($qty && $currentTime <= $from) {
                        if (! isset($slots[$index]))
                            $slots[$index]['time'] = $startDayTime->format('h:i A') . ' - ' . $endDayTime->format('h:i A');

                        $slots[$index]['slots'][] = [
                            'from' => $from->format('h:i A'),
                            'to' => $to->format('h:i A'),
                            'from_timestamp' => $from->getTimestamp(),
                            'to_timestamp' => $to->getTimestamp(),
                            'qty' => $qty
                        ];
                    }
                } else {
                    break;
                }
            }
        }

        return $slots;
    }

    /**
     * Add booking additional prices to cart item
     *
     * @param array $products
     * @return array
     */
    public function addAdditionalPrices($products)
    {
        $bookingProduct = $this->bookingProductRepository->findOneByField('product_id', $products[0]['product_id']);

        $rentingType = $products[0]['additional']['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::createFromTimeString($products[0]['additional']['booking']['date_from'] . " 00:00:00");
            $to = Carbon::createFromTimeString($products[0]['additional']['booking']['date_to'] . " 24:00:00");

            $price = $bookingProduct->rental_slot->daily_price * $to->diffInDays($from);
        } else {
            $from = Carbon::createFromTimestamp($products[0]['additional']['booking']['slot']['from']);
            $to = Carbon::createFromTimestamp($products[0]['additional']['booking']['slot']['to']);

            $price = $bookingProduct->rental_slot->hourly_price * $to->diffInHours($from);
        }

        $products[0]['price'] += core()->convertPrice($price);
        $products[0]['base_price'] += $price;
        $products[0]['total'] += (core()->convertPrice($price) * $products[0]['quantity']);
        $products[0]['base_total'] += ($price * $products[0]['quantity']);

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

        $rentingType = $item->additional['booking']['renting_type'] ?? $bookingProduct->rental_slot->renting_type;

        if ($rentingType == 'daily') {
            $from = Carbon::createFromTimeString($item->additional['booking']['date_from'] . " 00:00:00");
            $to = Carbon::createFromTimeString($item->additional['booking']['date_to'] . " 24:00:00");

            $price += $item->product->getTypeInstance()->getFinalPrice() + $bookingProduct->rental_slot->daily_price * $to->diffInDays($from);
        } else {
            $from = Carbon::createFromTimestamp($item->additional['booking']['slot']['from']);
            $to = Carbon::createFromTimestamp($item->additional['booking']['slot']['to']);

            $price += $bookingProduct->rental_slot->hourly_price * $to->diffInHours($from);
        }

        if ($price == $item->base_price)
            return;

        $item->base_price = $price;
        $item->price = core()->convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = core()->convertPrice($price * $item->quantity);

        $item->save();
    }
}