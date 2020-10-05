<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\City;
use App\User;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Str;
//use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->city_chosen) {
            $comments = Comment::join('users as u', 'user_id', '=', 'u.id')
                ->join('city_comment as cc', 'comments.id', '=', 'cc.comment_id')
                ->leftJoin('cities as c', 'city_id', '=', 'c.id')
                ->select(
                    'comments.id',
                    'title',
                    'comment_text',
                    'rating',
                    'img',
                    'comments.created_at',
                    'user_id',
                    'u.fio',
                    'u.email',
                    'u.phone',
                    'c.id as city_id',
                    'c.name'
                )
                ->where('c.name', '=', $request->city_chosen)
                ->orderBy('comments.created_at', 'desc')
                ->get();
        } else {
            $comments = Comment::join('users', 'user_id', '=', 'users.id')
                ->orderBy('comments.created_at', 'desc')
                ->paginate(4);
        }
        //dd($comments);
        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('comments.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->title = $request->title;
        $comment->comment_text = $request->comment_text;
        $comment->rating = $request->rating;
        //$comment->user_id = Auth::user()->id;
        //$comment->city_id = rand(1, 10);
        $comment->user_id = rand(1, 5);
        if ($request->file('img')) {
            $path = Storage::putFile('public', $request->file('img'));
            $url = Storage::url($path);
            $comment->img = $url;
        }

        if (!empty($request->cities)) {
            $cities = $request->cities;
        }
        else {
            $cities = City::all('id');
        }
        foreach($cities as $city_id) {
            $city = City::find($city_id);
            $city->comments()->save($comment);
        }
/*
        $comments = App\Comment::all();
        App\City::all()->each(function ($city) use ($comments) {
            $city->comments()->attach(
                $comments->random(rand(1, 20))->pluck('id')->toArray()
            );
        });
*/
        //$comment->save();
        return redirect()->route('/')->with('success', 'Отзыв успешно создан!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $comment = Comment::find($id);
        $user = $comment->user;
        //dd($user, $comment);
        /*$comment = Comment::join('users', 'comments.user_id', '=', 'users.id as user_id')
            ->find($id);*/
        if (!$comment) {
            return redirect()->route('comment.index')->withErrors('Что Вы задумали?');
        }
        return view('comments.show', ['comment'=>$comment,'user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $comment = Comment::find($id);
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
