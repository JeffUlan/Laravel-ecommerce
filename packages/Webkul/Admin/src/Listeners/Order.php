<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\NewOrderNotification;
use Webkul\Admin\Mail\NewAdminNotification;
use Webkul\Admin\Mail\NewInvoiceNotification;
use Webkul\Admin\Mail\NewShipmentNotification;
use Webkul\Admin\Mail\NewInventorySourceNotification;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order {

    /**
     * @param mixed $order
     *
     * Send new shipment mail to the customer and inventory source
     */
    public function sendNewOrderMail($order)
    {
        try {
            Mail::send(new NewOrderNotification($order));
        } catch (\Exception $e) {

        }
    }


    /**
     * @param mixed $invoice
     *
     * Send new invoice mail to the customer
     */
    public function sendNewInvoiceMail($invoice)
    {
        try {
            Mail::send(new NewInvoiceNotification($invoice));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $shipment
     *
     * Send new shipment mail to the customer
     */
    public function sendNewShipmentMail($shipment)
    {
        try {
            Mail::send(new NewShipmentNotification($shipment));

            Mail::send(new NewInventorySourceNotification($shipment));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $shipment
     *
     * Send new shipment mail to the customer
     */
    public function updateProductInventory($order)
    {
    }
}