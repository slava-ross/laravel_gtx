<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Comment;
use App\City;
use App\User;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Str;
//use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $city = $request->city_chosen;
        if ($city) {
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
                ->where('c.id', '=', $city)
                ->orderBy('comments.created_at', 'desc')
                ->get();
        } else {
            return redirect()->route('/');
        }
        return view('comments.index', compact('comments', 'city'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all('id', 'name');
        $new_comment = true;
        return view('comments.create', compact('cities', 'new_comment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentRequest $request)
    {
        $comment = new Comment;
        $comment->fill($request->all());
        $comment->user_id = \Auth::user()->id;
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
        if (!$comment) {
            return redirect()->route('/')->withErrors('Что Вы задумали?');
        }
        $user = $comment->user;

        /* Не ORM
        $comment = Comment::join('users', 'comments.user_id', '=', 'users.id as user_id')->find($id);
        */

        return view('comments.show', compact('comment', 'user'));
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
        if ($comment->user_id != \Auth::user()->id) {
            return redirect()->route('/')->withErrors('Вы не можете редактировать данный отзыв!');
        }
        $new_comment = false;
        return view('comments.edit', compact('comment','new_comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment->user_id != \Auth::user()->id) {
            return redirect()->route('/')->withErrors('Вы не можете редактировать данный отзыв!');
        }
        $comment->fill($request->all());
        if ($request->file('img')) {
            $path = Storage::putFile('public', $request->file('img'));
            $url = Storage::url($path);
            $comment->img = $url;
        }
        else {
            $comment->img = null;
        }
        $comment->update();

        /* thinking about
        $comment->fill($request->except('bla-bla-bla'));
        $comment->save();
        $comment->city()->sync($request->city);
        */

        return redirect()->route('comment.show', ['comment' => $comment->id])->with('success', 'Отзыв успешно отредактирован!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment->user_id != \Auth::user()->id) {
            return redirect()->route('/')->withErrors('Вы не можете удалить данный отзыв!');
        }
        $comment->delete();
        return redirect()->route('/')->with('success', 'Отзыв успешно удалён!');
    }
}
