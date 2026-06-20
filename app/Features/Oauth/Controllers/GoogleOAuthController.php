<?php
namespace App\Features\Oauth\Controllers;

use App\Features\Customer\Models\Customer;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class GoogleOAuthController extends Controller
{
   public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $customer = Customer::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => str()->random(16),
            ]);
        }

        if (!$customer->google_id) {
            $customer->google_id = $googleUser->id;
            $customer->save();
        }

        $token = $customer->createToken('google-login')->plainTextToken;

        $url = config('app.frontend_url');

        return redirect($url . "/auth/callback?token=" . $token);
    }
}