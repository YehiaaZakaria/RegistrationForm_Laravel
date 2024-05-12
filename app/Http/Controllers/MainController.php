<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User2;
use Illuminate\Support\Facades\Hash;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Mail;



require_once (app_path() . '/API_Ops.php');
require_once (app_path() . '/upload.php');

class MainController extends Controller
{

    public function __construct()
    {
        $locale = session('locale', 'en');
        app()->setLocale($locale);
    }
    public function index()
    {
        return view('index');
    }

    public function save_user(Request $request)
    {
        try {
            // Check if username already exists
            $existingUser = User2::where('user_name', $request->input('user_name'))->first();
            if ($existingUser) {
                echo "Username already exists.";
                return;
            }

            // Check if email already exists
            $existingEmail = User2::where('email', $request->input('email'))->first();
            if ($existingEmail) {
                echo "Email already exists.";
                return;
            }

            $uploadResponse = uploadImage();
            $user_image = $_FILES["user_image"]["name"];

            if ($uploadResponse == "Ok") {

                $user = new User2();
                $user->full_name = $request->input('full_name');
                $user->user_name = $request->input('user_name');
                $user->birthdate = $request->input('birthdate');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->address = $request->input('address');
                $user->password = Hash::make($request->input('password'));
                $user->user_image = $user_image;

                $user->save();

                Mail::to('yehiazakaria539@gmail.com')->send(new MailNotify($user->user_name));
                // return response()->json(['success' => 'User registered successfully.'], 200);
                echo "User registered successfully.";
            } else {
                // return response()->json(['error' => 'Error: uploadFailed'], 422);
            }
        } catch (\Exception $e) {
            // return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getBornToday(Request $request)
    {
        $day = $request->input('day');
        $month = $request->input('month');
        $names = getBornTodayNames($day, $month);

        $html = '<table>';
        $html .= '<tr><td><h3>People born on the same day: <h3></td></tr>';
        foreach ($names as $name) {
            $html .= "<tr><td>$name</td></tr>";
        }
        $html .= '</table>';

        return $html;
    }


}
