<?php
namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index()
    {
        // 如果用户未登录，auth 中间件会自动跳转到登录页面
        return view('welcome');
    }
}