<?php
// app/Http/Controllers/AuthController.php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Validator;
 
class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }
 
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
        $user->save();
 
        return response()->json([
            'status' => 'success',
        ]);
    }
 
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }
 
        $credentials = request(['email', 'password']);
 
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'fails',
                'message' => 'Unauthorized'
            ], 401);
        }
 	
 	{"Authorization":"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZDY4YjI0ZjU1ZTQzNzVjOTFlYmVlMzhiZGQ0MTM1MjFhNzg2ZTJiZmUxYmJiZmFhZDIyMGZmMTQwOTU3MjgwNDQyYjhlZDlkYjNjYjUwOWMiLCJpYXQiOjE2MDMzODEzNjksIm5iZiI6MTYwMzM4MTM2OSwiZXhwIjoxNjM0OTE3MzY5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.QvWDBUxNjb-rtChtMPGoLCZV7il253lMG0BdABNt10XQkHh0G-kVa4CwiybcDe0okYQsEiTWxBZG_XmfrFlI7YS7hu6kVEPSYmyX-Kk0yk7B0g7_EoF9ZSrsDZwNEKgPYaps3zz4Cer5aRq-KeFPMhVpMfz1apXSMZpLF82ZAHYP3F-9YP4hPJIZVTjvrB3WdEJKuyPnthOuzRrM6pWjTyW7yxLxirdndLttcFPnBLzraUaenlXvcZmNdUHOpWZfb9-o4nNWx8osorqYOW-XDeXlPErHN-CBJyV7sgAzbhrSiGRSitKzwCmCYs9QBZ9uMQYnxdJJS7h-bL3Hp8JhoU0q5sjPGpEpALY0biLhq3qbdq4wupiX6vSsxl1dgAD6o42plRmKEuacGVfXNQYIc0kBLJHaDOYykD5fmXcKJ86CEAKl5gdo_n6f2eGEGyZSYAMhnk36d59maOK0tszMuQGbawE2lsDiKKcw0JZoTgrJ_aiyx45bcsJPlRfI17gQKV6JU2oCCirmbg3_qXgBGHwlt53ozRrRndU04HuKNkQ6q7SAeH9OnhtRLGGYlUgUqGwR1DtjGIGvu5Ud4Nf_2pvOwcHO7jClzhSfu-toTNHJV2RJAGET96hrGOYT2fntB5euLaDNwRz0Gp665JhPPGEC8_OSwGvDox7ayr2zWhs","X-Requested-With":"XMLHttpRequest"}


        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
 
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
 
        $token->save();
 
        return response()->json([
            'status' => 'success',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
 
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => 'success',
        ]);
    }
 
    public function user(Request $request)
    {
    	$user = new User([
            'name' => 'd',
            'email' => 'd',
            'password' => bcrypt('d')
        ]);
 
        $user->save();
 
        return response()->json([
            'status' => $user,
        ]);
        // return response()->json($request->user());
    }
}