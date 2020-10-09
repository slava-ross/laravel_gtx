<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\VerifyMail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fio' => ['required', 'string', 'max:128'],
            'email' => ['required', 'string', 'email', 'max:128', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        return redirect()
            ->route('login')
            ->with('success', 'Проверьте ваш e-mail и перейдите по ссылке в письме для завершения регистрации');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'fio' => $data['fio'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'verify_token' => Str::random(),
            'status' => User::STATUS_INACTIVE,
        ]);
        //dd($user);
        Mail::to($user->email)->send(new VerifyMail($user));

        return $user;
    }

    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')
                ->with('error', 'Извините, пользователь не может быть идентифицирован');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Ваш почтовый ящик успешно подтверждён. Можете выполнить вход');
    }
}
