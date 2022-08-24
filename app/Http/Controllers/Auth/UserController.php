<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Queues;
use App\Enums\SnsEnum;
use App\Enums\VerificationTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetUserPasswordRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Jobs\SendResetPasswordMail;
use App\Models\SnsAccount;
use App\Models\Token;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    use AuthenticatesUsers;

    protected $userService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @param UserService $userService
     */

    public function __construct(UserService $userService)
    {
        $this->middleware([
            'guest:user',
        ])->except('logout');

        $this->userService = $userService;
    }

    /**
     * @return Factory|View
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.index', [
            'guard' => 'user',
            'screen' => 'login',
            'title' => trans('dashboard.login'),
            'redirect' => $request->input('redirect', '')
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect(route('auth.show_user_login_form'));
    }

    /**
     * @return Factory|View
     */
    public function showRegisterForm()
    {
        return view('auth.index', [
            'guard' => 'user',
            'screen' => 'register',
            'title' => trans('dashboard.register')
        ]);
    }

    /**
     * @param RegisterUserRequest $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function registerUser(RegisterUserRequest $request, UserService $userService)
    {
        $user = $userService->createUser($request->parameters());
        $this->guard()->login($user);

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    /**
     * @param $sns
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function snsLogin($snsProvider)
    {
        if (!in_array($snsProvider, SnsEnum::toArray(), true)) {
            abort(403);
        }
        return Socialite::driver($snsProvider)->redirect();
    }

    /**
     * @param $snsProvider
     * @param UserService $userService
     * @return RedirectResponse|Redirector|void
     */
    public function snsLoginCallback($snsProvider, UserService $userService)
    {
        if (!in_array($snsProvider, SnsEnum::toArray(), true)) {
            return abort(403, 'Cannot login to this provider.');
        }

        try {
            $snsUser = Socialite::driver($snsProvider)->user();
        } catch (Exception $e) {
            return abort(400, 'Cannot get user information.');
        }


        $snsAccount = SnsAccount::whereSns($snsProvider)
            ->whereSnsId($snsUser->getId())
            ->first();
        $user = null;

        if ($snsAccount === null) {
            $userAttributes = [
                'name' => $snsUser->getName(),
                'email' => $snsUser->getEmail(),
                'image' => $snsUser->getAvatar(),
            ];
            $user = $userService->createUser($userAttributes);

            $snsAccount = new SnsAccount([
                'sns' => SnsEnum::search($snsProvider),
                'user_id' => $user->id,
                'sns_id' => $snsUser->getId()
            ]);

            $snsAccount->save();

        } else {
            $user = $snsAccount->user()->first();
            $userService->updateUser($user, [
                'name' => $snsUser->getName(),
                'image' => $snsUser->getAvatar(),
            ]);
        }

        $this->guard()->login($user);

        return redirect(route('index'));
    }

    /**
     * @return Factory|View
     */
    public function showForgetPasswordForm()
    {
        return view('auth.index', [
            'guard' => 'user',
            'screen' => 'forget',
            'title' => trans('dashboard.forget_password')
        ]);
    }

    /**
     * @param ForgetUserPasswordRequest $request
     * @return JsonResponse|void
     */
    public function createResetPasswordToken(ForgetUserPasswordRequest $request)
    {
        try {
            $user = User::whereEmail($request->input('email'))->first();
            $token = new Token([
                'user_id' => $user->id,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+1 days')),
                'token' => Str::random(20) . uniqid(),
                'verification_type' => VerificationTypes::RESET_PASSWORD,
            ]);
            $token->save();
            SendResetPasswordMail::dispatch($token, $user, 'user')->onQueue(Queues::HIGH);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Cannot create reset password token for user: ' . $request->input('email'));
            Log::error($e->getMessage());
        }

        return abort(500);
    }

    /**
     * @param Token $token
     * @return RedirectResponse|Redirector
     */
    public function verifyEmail(Token $token)
    {
        if ($token->isExpired() || $token->verification_type !== VerificationTypes::EMAIL) {
            abort(403);
        }

        $user = $token->user()->first();

        if ($user === null) {
            abort(404);
        }

        $user->email_verified_at = now();
        $user->save();
        $this->guard()->login($user);

        return redirect(route('index'));
    }

    /**
     * @param Token $token
     * @return Factory|View
     */
    public function showResetPasswordForm(Token $token)
    {
        if ($token->isExpired() || $token->verification_type !== VerificationTypes::RESET_PASSWORD) {
            abort(403);
        }

        return view('auth.index', [
            'guard' => 'user',
            'screen' => 'reset',
            'token' => $token->token,
            'title' => trans('dashboard.reset_password')
        ]);
    }

    /**
     * @param Token $token
     * @param ResetPasswordRequest $request
     * @return RedirectResponse|Redirector
     */
    public function resetPassword(Token $token, ResetPasswordRequest $request)
    {
        if ($token->isExpired()
            || $token->verification_type !== VerificationTypes::RESET_PASSWORD
            || $token->user_id === null
        ) {
            abort(403);
        }

        $user = $token->user()->first();

        DB::transaction(function () use ($user, $request, $token) {
            $user->update($request->parameters());
            $token->delete();
        });

        $this->guard()->login($user);

        return redirect(route('index'));
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * @return mixed
     */
    public function guard()
    {
        return Auth::guard('user');
    }
}
