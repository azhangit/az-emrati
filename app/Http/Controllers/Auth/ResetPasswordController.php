<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
        {
            if (auth()->user()->can('admin_dashboard')) {
                return redirect()->route('admin.dashboard')
                                ->with('status', trans($response));
            }

            if (
                auth()->user()->can('view_all_orders') ||
                auth()->user()->can('view_inhouse_orders') ||
                auth()->user()->can('view_seller_orders') ||
                auth()->user()->can('view_pickup_point_orders')
            ) {
                return redirect()->route('all_orders.index')
                                ->with('status', trans($response));
            }

            return redirect()->route('profile.index')
                            ->with('status', trans($response));
        }

        return redirect()->route('home')
                            ->with('status', trans($response));
    }
}
