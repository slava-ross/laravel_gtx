<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Comment;
use App\City;
use App\User;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;
use JavaScript;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $cityName = $request->city_name;
        $cityId = $request->city_id;
        session(['city_chosen' => $cityName]);
        //dd('city_id=' . $cityId . ' city_name=' . $cityName);
        if (empty($cityId)) { // Пришли из модального окна только с именем города
            $city = City::getCityByName($cityName);
            if (empty($city)) { // Новый город
                $city = City::create([
                    'name' => $cityName,
                ]);
            }
            $cityId = $city->id;
        }
        //dd('city_id=' . $cityId . ' city_name=' . $cityName);

        $comments = Comment::getCommentsByCityId($cityId);
        /*JavaScript::put([
            'foo_token' => 'qwerty'
        ]);*/

            //return view('comments.index', compact('comments','cityId','cityName'));
        return view('comments.index', compact('comments','cityName'));
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
            $cityItems = City::all('id')->toArray();
            $cities = [];
            foreach($cityItems as $item) {
                $cities[] = $item['id'];
            }
        }
        //dd($cities);
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
