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
        if (!$request->session()->has('city_chosen')) {
            session(['city_chosen' => $cityName]);
        }
        if (empty($cityId)) { // Пришли из модального окна только с именем города
            $city = City::getCityByName($cityName);
            if (empty($city)) { // Новый город
                $city = City::create([
                    'name' => $cityName,
                ]);
            }
            $cityId = $city->id;
        }

        $comments = Comment::getCommentsByCityId($cityId);
        /*JavaScript::put([
            'foo_token' => 'qwerty'
        ]);*/

        $title = "Отзывы по городу $cityName";
        return view('comments.index', compact('comments','cityName','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $new_comment = true;
        $modal_title = 'Новый отзыв';
        $button_id = 'new-comment-create';
        $button_text = 'Создать отзыв';

        $viewHTML = view('comments.create', compact('new_comment', 'modal_title','button_id','button_text'))->render();
        return \Response::json(['success' => 'true', 'html' => $viewHTML]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentRequest $request)
    {
        $cities = $request->cities;
        $comment = new Comment;
        $comment->fill($request->all());
        $comment->user_id = \Auth::user()->id;
        if ($request->file('img')) {
            $path = Storage::putFile('public', $request->file('img'));
            $url = Storage::url($path);
            $comment->img = $url;
        }
        $citiesIdArray = []; // Массив для хранения id городов

        if (!empty($cities)) {
            foreach ($cities as $cityName){
                $city = City::getCityByName($cityName);
                if (empty($city)) { // Новый город
                    $city = City::create([
                        'name' => $cityName,
                    ]);
                }
                $citiesIdArray[] = $city->id;
            }
        }
        else { // Если пустой список городов - сохраняем комментарий для всех
            $cityItems = City::all('id')->toArray();
            foreach($cityItems as $item){
                $citiesIdArray[] = $item['id'];
            }
        }

        foreach($citiesIdArray as $city_id) {
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
        return view('comments.show', compact('comment', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $comment = Comment::find($id);
        if ($comment->user_id != \Auth::user()->id) {
            return redirect()->route('/')->withErrors('Вы не можете редактировать данный отзыв!');
            //return \Response::json(['error' => 'Вы не можете удалить данный отзыв!'], 403);
        }
        $new_comment = false;
        $modal_title = "Редактирование отзыва №$comment->id";
        $button_id = 'comment-edit';
        $button_text = 'Сохранить отзыв';

        $viewHTML = view('comments.edit', compact('comment','new_comment','modal_title','button_id','button_text'))->render();
        return \Response::json(['success' => 'true', 'html' => $viewHTML]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment->user_id != \Auth::user()->id) {
            return \Response::json(['error' => 'Вы не можете удалить данный отзыв!'], 403);
            //return redirect()->route('/')->withErrors('Вы не можете удалить данный отзыв!');
        }
        $comment->delete();
        return \Response::json(['success' => 'true']);
        //return redirect()->route('/')->with('success', 'Отзыв успешно удалён!');
    }
    /**
     * Get all comments of a certain author.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAuthorsComments($id)
    {
            $comments = Comment::getCommentsByAuthor($id);
            $fio = $comments->first()->fio;
            $title = "Отзывы автора $fio";
            return view('comments.index', compact('comments', 'fio', 'title'));
    }
}
