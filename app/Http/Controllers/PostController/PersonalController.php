<?php

namespace App\Http\Controllers\PostController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'email',
            'phone' => 'required',
        ], [], [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'email' => 'Почта',
            'phone' => 'Телефон',
        ]);


        $errs = [];
        if (Auth::user()->email != $request->input('email') && User::where('email', $request->input('email'))->first()) $errs['email'] = 'Почта уже кем-то используется!';
        if (Auth::user()->phone != $request->input('phone') && User::where('phone', $request->input('phone'))->first()) $errs['phone'] = 'Телефон уже кем-то используется!';

        if ($errs) {
            return response([
                'errors' => [
                    'login' => '',
                    'passwd' => 'Пользователь не найден!',
                ]
            ], 422);
        };
        $user = User::find(Auth::user()->id);
        if ($user->surname != $request->surname) $user->surname = $request->surname;
        if ($user->name != $request->name) $user->name = $request->name;
        if ($user->email != $request->email) $user->email = $request->email;
        if ($user->phone != $request->phone) $user->phone = $request->phone;
        $user->save();

        return response([
            'status' => 'success',
            'data' => [
                'user' => $user,
            ]
        ], 200);
    }

    public function editPassword(Request $request)
    {
        $request->validate([
            'oldPasswd' => 'required',
            'newPasswd' => 'min:3',
            'confirmPasswd' => 'same:newPasswd',
        ], [], [
            'oldPasswd' => 'Старый пароль',
            'newPasswd' => 'Новый пароль',
            'confirmPasswd' => 'Подтверждение пароля',
        ]);

        if (!Hash::check($request->oldPasswd, Auth::user()->password)) {
            return response([
                'errors' => [
                    'oldPasswd' => 'Старый пароль не верный',
                ]
            ], 422);
        }
        
        $user = User::find(Auth::user()->id);
        $user->password = $request->newPasswd;
        $user->save();

        return response([
            'status' => 'success',
            'data' => [
                'user' => $user,
            ]
        ], 200);
    }
}
