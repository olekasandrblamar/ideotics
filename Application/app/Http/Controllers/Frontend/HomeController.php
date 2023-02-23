<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use App\Models\Faq;
use App\Models\Feature;

class HomeController extends Controller
{
    public function index()
    {
        $features = Feature::where('lang', getLang())->get();
        $blogArticles = BlogArticle::where('lang', getLang())->with(['blogCategory', 'admin'])->orderbyDesc('id')->limit(3)->get();
        $faqs = Faq::where('lang', getLang())->limit(10)->get();
        return view('frontend.home', ['features' => $features, 'blogArticles' => $blogArticles, 'faqs' => $faqs]);
    }

    public function dashboard(){
        return view('frontend.dashboard');
    }
    // public function dashboard2(){
    //     return view('frontend.dashboard2');
    // }
    // public function dashboard3(){
    //     return view('frontend.dashboard3');
    // }
    // public function dashboard4(){
    //     return view('frontend.dashboard4');
    // }
}
