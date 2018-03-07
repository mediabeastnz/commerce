<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\controllers;

use craft\commerce\elements\Order;

/**
 * Class BaseFrontEndController
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 2.0
 */
class BaseFrontEndController extends BaseController
{
    // Properties
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected $allowAnonymous = true;

    // Protected Methods
    // =========================================================================

    /**
     * @param Order $cart
     * @return array
     */
    protected function cartArray(Order $cart): array
    {
        $data = [];
        $data['id'] = $cart->id;
        $data['number'] = $cart->number;
        $data['couponCode'] = $cart->couponCode;
        $data['itemTotal'] = $cart->itemTotal;
        $data['totalPrice'] = $cart->totalPrice;
        $data['totalPaid'] = $cart->totalPaid;
        $data['email'] = $cart->email;
        $data['isCompleted'] = $cart->isCompleted;
        $data['dateOrdered'] = $cart->dateOrdered;
        $data['datePaid'] = $cart->datePaid;
        $data['currency'] = $cart->currency;
        $data['paymentCurrency'] = $cart->paymentCurrency;
        $data['lastIp'] = $cart->lastIp;
        $data['message'] = $cart->message;
        $data['returnUrl'] = $cart->returnUrl;
        $data['cancelUrl'] = $cart->cancelUrl;
        $data['orderStatusId'] = $cart->orderStatusId;
        $data['shippingMethod'] = $cart->shippingMethodHandle;
        $data['shippingMethodId'] = $cart->getShippingMethodId();
        $data['paymentMethodId'] = $cart->gatewayId;
        $data['customerId'] = $cart->customerId;
        $data['isPaid'] = $cart->getIsPaid();
        $data['totalQty'] = $cart->totalQty;
        $data['pdfUrl'] = $cart->getPdfUrl() ? $cart->getPdfUrl('ajax') : '';
        $data['isEmpty'] = $cart->isEmpty();
        $data['itemSubtotal'] = $cart->getItemSubtotal();
        $data['totalWeight'] = $cart->getTotalWeight();
        $data['totalTax'] = $cart->getAdjustmentsTotalByType('Tax');
        $data['totalTaxIncluded'] = $cart->getAdjustmentsTotalByType('Tax', true);
        $data['totalShippingCost'] = $cart->getAdjustmentsTotalByType('Shipping');
        $data['totalDiscount'] = $cart->getAdjustmentsTotalByType('Discount');

        $data['availableShippingMethods'] = $cart->getAvailableShippingMethods();

        $data['shippingAddressId'] = $cart->shippingAddressId;
        if ($cart->getShippingAddress()) {
            $data['shippingAddress'] = $cart->shippingAddress->attributes;
        } else {
            $data['shippingAddress'] = null;
        }

        $data['billingAddressId'] = $cart->billingAddressId;
        if ($cart->getBillingAddress()) {
            $data['billingAddress'] = $cart->billingAddress->attributes;
        } else {
            $data['billingAddress'] = null;
        }

        $lineItems = [];
        foreach ($cart->lineItems as $lineItem) {
            $lineItemData = [];
            $lineItemData['id'] = $lineItem->id;
            $lineItemData['price'] = $lineItem->price;
            $lineItemData['saleAmount'] = $lineItem->saleAmount;
            $lineItemData['salePrice'] = $lineItem->salePrice;
            $lineItemData['qty'] = $lineItem->qty;
            $lineItemData['subtotal'] = $lineItem->getSubtotal();
            $lineItemData['tax'] = $lineItem->tax;
            $lineItemData['shippingCost'] = $lineItem->shippingCost;
            $lineItemData['discount'] = $lineItem->discount;
            $lineItemData['total'] = $lineItem->total;
            $lineItemData['weight'] = $lineItem->weight;
            $lineItemData['length'] = $lineItem->length;
            $lineItemData['height'] = $lineItem->height;
            $lineItemData['width'] = $lineItem->width;
            $lineItemData['total'] = $lineItem->total;
            $lineItemData['qty'] = $lineItem->qty;
            $lineItemData['snapshot'] = $lineItem->snapshot;
            $lineItemData['note'] = $lineItem->note;
            $lineItemData['orderId'] = $lineItem->orderId;
            $lineItemData['purchasableId'] = $lineItem->purchasableId;
            $lineItemData['taxCategoryId'] = $lineItem->taxCategoryId;
            $lineItemData['shippingCategoryId'] = $lineItem->shippingCategoryId;
            $lineItemData['onSale'] = $lineItem->getOnSale();
            $lineItemData['options'] = $lineItem->options;
            $lineItemData['optionsSignature'] = $lineItem->optionsSignature;
            $lineItems[$lineItem->id] = $lineItemData;
        }
        $data['lineItems'] = $lineItems;
        $data['totalLineItems'] = count($lineItems);

        $adjustments = [];
        foreach ($cart->adjustments as $adjustment) {
            $adjustmentData = [];
            $adjustmentData['id'] = $adjustment->id;
            $adjustmentData['type'] = $adjustment->type;
            $adjustmentData['name'] = $adjustment->name;
            $adjustmentData['description'] = $adjustment->description;
            $adjustmentData['amount'] = $adjustment->amount;
            $adjustmentData['sourceSnapshot'] = $adjustment->sourceSnapshot;
            $adjustmentData['orderId'] = $adjustment->orderId;
            $adjustments[$adjustment->type][] = $adjustmentData;
        }
        $data['adjustments'] = $adjustments;
        $data['totalAdjustments'] = count($adjustments);

        if ($cart->getErrors()) {
            $data['errors'] = $cart->getErrors();
        }

        // remove un-needed base element attributes
        $remove = ['archived', 'cancelUrl', 'lft', 'level', 'rgt', 'slug', 'uri', 'root'];
        foreach ($remove as $r) {
            unset($data[$r]);
        }

        return $data;
    }
}
