<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Auth;

class SteamController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/auth/callback';

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return string
     */
    public function redirectToSteam()
    {
        return $this->steam->redirect();
    }

    public function handle()
    {
        session_start();

        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();
                $token = Str::random(60);

                if ($user) {
                    $user->update([
                        'username' => $info->personaname,
                        'avatar' => $info->avatarfull,
                        'api_token' => $token
                    ]);
                } else {
                    $ref = null;

                    if (isset($_SESSION['ref'])) {
                        $ref = $_SESSION['ref'];

                        if (!User::query()->where('referral_code', $ref)->first()) {
                            $ref = null;
                        }
                    }

                    User::create([
                        'steamid' => $info->steamID64,
                        'username' => $info->personaname,
                        'avatar' => $info->avatarfull,
                        'referral_code' => Str::random(10),
                        'referral_use' => $ref,
                        'api_token' => $token
                    ]);
                }

                return redirect($this->redirectURL . '?token=' . $token);
            }

            return redirect($this->redirectURL . '?token=null');
        }

        return redirect($this->redirectURL . '?token=null');
    }
}
