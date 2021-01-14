<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Comment;
use App\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $cityName = $request->city_name;
        $cityId = $request->city_id;
        // Пришли из модального окна только с именем города
        if (empty($cityId)) {
            $city = City::getCityByName($cityName);
            if (empty($city)) { // Новый город
                $city = City::create([
                    'name' => $cityName,
                ]);
            }
            $cityId = $city->id;
        // Пришли со страницы выбора города только с id города
        } elseif (empty($cityName)) {
            $city = City::find($cityId);
            if (!$city) {
                return redirect()->route('/')->withErrors('Попытка выбрать несуществующий город!');
            }
            $cityName = $city->name;
        }
        // Сохранение имени города в сессионной переменной
        if (!$request->session()->has('city_chosen')) {
            session(['city_chosen' => $cityName]);
        }
        $comments = Comment::getCommentsByCityId($cityId);
        $title = "Отзывы по городу $cityName";
        return view('comments.index', compact('comments','cityName','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        if (Auth::check()) {
            $new_comment = true;
            $modal_title = 'Новый отзыв';
            $button_id = 'new-comment-create';
            $button_text = 'Создать отзыв';

            $viewHTML = view('comments.create', compact('new_comment', 'modal_title','button_id','button_text'))->render();
            return \Response::json(['success' => 'true', 'html' => $viewHTML]);
        }
        return \Response::json(['success' => 'false'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
        session(['success' => 'Отзыв успешно создан!']);
        return \Response::json(['success' => 'true']);
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
            return redirect()->route('/')->withErrors('Попытка посмотреть несуществующий отзыв!');
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
            return \Response::json(['errors' => ['Вы не можете редактировать данный отзыв!']], 403);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CommentRequest $request, $id)
    {
        $comment = Comment::find($id);

        $comment->fill($request->all());
        if (empty($request->img_leave)) {
            if ($request->file('img')) {
                $path = Storage::putFile('public', $request->file('img'));
                $url = Storage::url($path);
                $comment->img = $url;
            }
            else {
                $comment->img = null;
            }
        }
        $comment->update();
        return \Response::json([
            'success' => 'Отзыв успешно изменён!',
            'title' => $comment->title,
            'comment_text' => $comment->comment_text,
            'rating' => $comment->rating,
            'img' => $comment->img
        ]);
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
            return \Response::json(['errors' => ['Вы не можете удалить данный отзыв!']], 403);
        }
        $comment->delete();
        session(['success' => 'Отзыв успешно удалён!']);
        return \Response::json(['success' => 'true']);
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
            if ($comments->isEmpty()) {
                return redirect()->route('/')->withErrors('Попытка посмотреть отзывы несуществующего автора!');
            }
            $fio = $comments->first()->fio;
            $title = "Отзывы автора $fio";
            return view('comments.index', compact('comments', 'fio', 'title'));
    }
}
