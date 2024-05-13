<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User2;
use Illuminate\Support\Facades\Hash;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Mail;



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

            $uploadResponse = $this->uploadImage();
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
        $names = $this->getBornTodayNames($day, $month);

        $html = '<table>';
        $html .= '<tr><td><h3>People born on the same day: <h3></td></tr>';
        foreach ($names as $name) {
            $html .= "<tr><td>$name</td></tr>";
        }
        $html .= '</table>';

        return $html;
    }

    
    // Helper functions

    function getBornTodayNames($day, $month)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/actors/list-born-today?month=$month&day=$day",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
            "X-RapidAPI-Key: 719c60ac3cmshd22f2140a7ad840p17befajsn63022bc4aaf6"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return [];
    } else {
        $pattern = '/\/name\/(nm\d+)\/"/';
        preg_match_all($pattern, $response, $IDs);
        $IDs = $IDs[1];
        $names = [];

        foreach ($IDs as $ID) {
            $name = $this->getUserName($ID);
            if ($name !== null) {
                $names[] = $name;
            }
        }

        return $names;
    }
}

function getUserName($id)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/actors/get-bio?nconst=$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
            "X-RapidAPI-Key: 719c60ac3cmshd22f2140a7ad840p17befajsn63022bc4aaf6"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return null; // Return null on error
    } else {
        $data = json_decode($response, true);
        return isset($data['name']) ? $data['name'] : null;
    }
}

function uploadImage()
{
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["user_image"]["name"]);

    $uploadresponse = "";


    if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $targetFile)) {
        $uploadresponse = "Ok";
    } else {
        $uploadresponse = "Not Ok";
    }

    return $uploadresponse;
}


}
