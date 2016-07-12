<?php
namespace App\Http\Controllers\Auth;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use App\Mailers\UserMailer as Mailer;
use App\Models\User;
use App\Repository\UsersRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\SocialRegister;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * @param Mailer $mailer
     * @param UsersRepositoryInterface $user
     */
    public function __construct(Mailer $mailer, UsersRepositoryInterface $user)
    {
        $this->mailer = $mailer;
        $this->user = $user;
    }

    /**
     * @param $username
     * @param $code
     * @return mixed
     */
    public function validateUser($username, $code)
    {
        $user = $this->user->activate($username, $code);

        if (!$user) {
            return redirect()->route('home')->with('flashError', t('You are not registered with us'));
        }
        auth()->loginUsingId($user->id);

        return redirect()->route('home')->with('flashSuccess', t('Congratulations your account is created and activated'));
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        $title = t('Registration');

        return view('auth.registraion', compact('title'));
    }

    /**
     * @param RegisterRequest $request
     * @return mixed
     */
    public function postIndex(RegisterRequest $request)
    {
        if (!$this->user->createNew($request)) {
            return redirect()->route('registration')->with('flashError', 'Please try again, enable to create user');
        }

        return redirect()->route('login')->with('flashSuccess', t('A confirmation email is sent to your mail'));
    }

    public function getSocialRegister(SocialRegister $request)
    {
        $title = t('Registration');

        return view('auth.' . $request->route('provider'), compact('title'));
    }

    /**
     * @param SocialRegister $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSocialRegister(SocialRegister $request)
    {
        if ($request->session()->has('user_email')) {
            $this->validate($request, [
                'username'              => 'required|unique:users',
                'password'              => 'required|between:4,25|confirmed',
                'password_confirmation' => 'required|between:4,25',
            ]);
            if ($this->user->registerViaSocial($request)) {
                return redirect()->route('home')->with('flashSuccess', t('A confirmation email is sent to your mail'));
            }
        }
        if ($request->session()->has('site_user') && $request->get('password')) {
            $user = User::whereId($request->session()->get('site_user')->id)->firstOrFail();
            if (Hash::check($request->get('password'), $user->password)) {
                $user->twid = $request->session()->get('twitter_register')->getId();
                $user->save();
                auth()->loginUsingId($user->id);
                return redirect()->route('home')->with('flashSuccess', t('Your account is now activated'));
            } else {
                return redirect()->back()->with('flashError', t('Invalid Password'));
            }
        }

        if ($request->route('provider') == 'twitter') {
            $user = User::whereEmail($request->get('email'))->first();
            if ($user) {
                $request->session()->put('site_user', $user);
                return redirect()->to('registration/twitter');
            }
            $request->session()->put('user_email', $request->get('email'));
            return redirect()->to('registration/twitter');
        }

        $this->validate($request, [
            'username'              => 'required|unique:users',
            'password'              => 'required|between:4,25|confirmed',
            'password_confirmation' => 'required|between:4,25',
        ]);

        if ($this->user->registerViaSocial($request) instanceof User) {
            return redirect()->route('home')->with('flashSuccess', t('Your account is now activated'));
        }
    }
}
