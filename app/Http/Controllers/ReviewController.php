<?php

namespace App\Http\Controllers;

use App\Review;

use Illuminate\Http\Request;

use Carbon\Carbon;

use Illuminate\Support\Facades\Input;

use Session;

class ReviewController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except(['index']);
    }

    public function index(){

        $reviews = Review::all();
       
        return view('reviews.index', compact('reviews'));
    }

    public function create(){

        return view('reviews.create');

    }

    public function store(Request $request){

        $review = new Review();
        $this->validate($request, [
            'title' => 'required|min:3|max:30',
            'body' => 'required',
            'image' => 'required'
        ]);

        $review->title = $request->title;
        $review->body = $request->body;

        if($request->hasFile('image')) {
            $file = Input::file('image');
            //getting timestamp
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            
            //$name = $timestamp. '-' .$file->getClientOriginalName();
            $name = $timestamp. '-img';
            
            $review->image = $name;

            $file->move(public_path().'/images/', $name);
        }

        $review->save();
        //return $this->create()->with('success', 'Отзыв добавлен');

        Session::flash('message', "Отзыв добавлен");
        return redirect('/reviews');
    }

    public function destroy(Review $review){

        $review = Review::findOrFail($review->id);

        $review->delete();

        Session::flash('message', "Отзыв удален");
        return redirect('/reviews');
    }


}
