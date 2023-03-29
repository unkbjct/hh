<?php

namespace App\Http\Controllers\PostController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'email|unique:users',
            'phone' => 'required|unique:users',
            'passwd' => 'min:3',
            'confirmPasswd' => 'same:passwd',
        ], [], [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'email' => 'Почта',
            'phone' => 'Телефон',
            'passwd' => 'Пароль',
            'confirmPasswd' => 'Подтверждение пароля',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = $request->passwd;
        $user->save();

        if (Auth::attempt(['password' => $request->passwd, 'email' => $request->email])) {
            return response([
                'status' => 'success',
                'data' => [
                    'url' => route('home'),
                    'user' => $user,
                ]
            ], 200);
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'passwd' => 'required',
        ], [], [
            'login' => '',
            'passwd' => '',
        ]);

        if (Auth::attempt(['password' => $request->passwd, 'email' => $request->login]) || Auth::attempt(['password' => $request->passwd, 'phone' => $request->login])) {
            return response([
                'status' => 'success',
                'data' => [
                    'url' => route('home'),
                    'user' => Auth::user(),
                ]
            ], 200);
        } else {
            return response([
                'errors' => [
                    'login' => '',
                    'passwd' => 'Пользователь не найден!',
                ]
            ], 403);
        }
    }
}
