<?php

namespace App\Http\Controllers\Newsletter;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newsletter_email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'newsletter')->withInput();
        }

        $email = strtolower(trim($validator->validated()['newsletter_email']));

        if (NewsletterSubscription::where('email', $email)->exists()) {
            return back()->with('newsletter_success', 'Ви вже підписані на розсилку.');
        }

        NewsletterSubscription::create([
            'email' => $email,
            'user_id' => Auth::id(),
        ]);

        return back()->with('newsletter_success', 'Дякуємо за підписку на розсилку!');
    }
}
