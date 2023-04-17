<?php

namespace App\Http\Controllers\PostControllers;

use App\Http\Controllers\Controller;
use App\Models\User_response;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SingleController extends Controller
{
    public function response(Vacancy $vacancy)
    {

        $oldResponse = User_response::where("user", Auth::user()->id)
            ->where("vacancy", $vacancy->id)
            ->where("created_at", ">", Carbon::now()->subDays(1))
            ->get();

        if ($oldResponse->isNotEmpty()) return response([
            'status' => 'timestamp',
            'data' => [
                'vacancy' => $vacancy,
            ]
        ], 422);

        $newResponse = new User_response();
        $newResponse->user = Auth::user()->id;
        $newResponse->vacancy = $vacancy->id;
        $newResponse->save();
        $vacancy->responses += 1;
        $vacancy->save();

        return response([
            'status' => 'success',
            'data' => [
                'vacancy' => $vacancy,
            ]
        ], 200);
    }


    public function favorite(Request $request)
    {

        $favorites = json_decode(Cookie::get($request->type));

        if ($favorites) {
            if (in_array($request->id, $favorites)) {
                for ($i = 0; $i < count($favorites); $i++) {
                    if ($favorites[$i] == $request->id) break;
                }
                unset($favorites[$i]);
                // array_values($favorites);
                $action = 'remove';
            } else {
                array_push($favorites, $request->id);
                $action = 'add';
            }
        } else {
            $favorites = [$request->id];
            $action = 'add';
        }

        $favorites = array_values($favorites);

        return response([
            'status' => 'success',
            'data' => [
                'action' => $action,
                'newList' => $favorites,
            ],
        ], 200)->withCookie(cookie()->forever($request->type, json_encode($favorites, JSON_UNESCAPED_UNICODE)));
    }
}
