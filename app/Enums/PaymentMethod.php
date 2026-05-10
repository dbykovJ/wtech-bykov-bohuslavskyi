<?php

namespace App\Enums;

enum PaymentMethod: string {
    case card = 'Credit Card';
    case apple_pay = 'Apple Pay';
    case google_pay = 'Google Pay';
}
