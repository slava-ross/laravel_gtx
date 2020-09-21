<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
use App\User;
//use App\Http\Requests\PostRequest;
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

        /*$comments = Comment::join('users', 'id_author', '=', 'users.id')
            ->orderBy('comments.created_at', 'desc')
            ->paginate(4);
*/
        $comments = User::find(1)->comments();
        dump($comments);

        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comments.create');
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
        //$comment->short_title = Str::length($request->title) > 30 ? Str::substr($request->title, 0, 30) . '...' : $request->title;
        $comment->comment_text = $request->comment_text;

        //$comment->id_author = \Auth::user()->id;
        $comment->id_author = rand(1, 10);
        $comment->id_city = rand(1, 15);
        $comment->rating = rand(1, 10);

        if ($request->file('img')) {
            $path = Storage::putFile('public', $request->file('img'));
            $url = Storage::url($path);
            $comment->img = $url;
        }
        $comment->save();
        return redirect()->route('comment.index')->with('success', 'Отзыв успешно создан!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $comment = Comment::join('users', 'comments.id', '=', 'users.id')->find($id);
        if (!$comment) {
            return redirect()->route('comment.index')->withErrors('Что Вы пытаетесь этим доказать?');
        }
        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
