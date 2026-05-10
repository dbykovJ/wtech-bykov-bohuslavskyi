<?php

namespace App\Enums;

enum DeliveryMethod: string {
    case truck = 'truck';
    case post = 'post';
    case dropbox = 'drop box';
}
