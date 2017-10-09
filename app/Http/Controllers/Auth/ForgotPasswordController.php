<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\User;
use App\Utils\Helpers\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends ApiController {

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */
    use SendsPasswordResetEmails;

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        $email = 'email';
        $this->validate($request, [$email => 'required|email']);
        $user = User::where($email, $request->input($email))->first();
        if ( ! $user)
        {
            return $this->respondBadRequest();
        }
        $this->sendResetLinkEmail($request);
        return $this->respond(['data' => [0]]);
    }
}