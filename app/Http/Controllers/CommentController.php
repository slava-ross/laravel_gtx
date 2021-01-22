<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ServicesInterface;
use App\Http\Requests\CommentRequest;
use App\Services\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @var Services
     */
    private $service;

    /**
     * Конструктор
     * @param ServicesInterface $service
     */
    public function __construct(ServicesInterface $service)
    {
        $this->middleware('auth')->except('index','show');
        $this->service = $service;
    }

    /**
     * Метод отображения списка отзывов
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $cityName = $request->city_name;
        $cityId = $request->city_id;
        // --- Пришли из модального окна только с именем города или из автокомплита с выбором имени города или взяли из сессионной переменной ---
        if (empty($cityId)) {
            $city = $this->service->takeCityByName($cityName);
            if (empty($city)) { // Новый город
                $city = $this->service->rememberNewCity(['name' => $cityName]);
            }
            $cityId = $city->id;
        // --- Пришли со страницы выбора города только с id города  ---
        } elseif (empty($cityName)) {
            $city = $this->service->takeCityById($cityId);
            if (!$city) {
                return redirect()->route('/')->withErrors('Попытка выбрать несуществующий город!');
            }
            $cityName = $city->name;
        }
        // --- Сохранение имени города в сессионной переменной ---
        if (!$request->session()->has('city_chosen')) {
            session(['city_chosen' => $cityName]);
        }
        $comments = $this->service->takeCommentsByCityId($cityId);
        $title = "Отзывы по городу $cityName";
        return view('comments.index', compact('comments','cityName','title'));
    }

    /**
     * Отображение формы создания нового отзыва
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
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
     * Сохранение нового отзыва в хранилище
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommentRequest $request)
    {
        $comment = $this->service->createNewComment($request);
        session(['success' => 'Отзыв успешно создан!']);
        return \Response::json(['success' => 'true']);
    }

    /**
     * Отобразить определённый отзыв
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        $comment = $this->service->takeCommentById($id);
        if (!$comment) {
            return redirect()->route('/')->withErrors('Попытка посмотреть несуществующий отзыв!');
        }
        $user = $comment->user;
        return view('comments.show', compact('comment', 'user'));
    }

    /**
     * Отображение формы редактирования определённого отзыва
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $comment = $this->service->takeCommentById($id);
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
     * Обновление отредактированного отзыва в хранилище
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CommentRequest $request, $id)
    {
        $comment = $this->service->updateComment($request, $id);

        return \Response::json([
            'success' => 'Отзыв успешно изменён!',
            'title' => $comment->title,
            'comment_text' => $comment->comment_text,
            'rating' => $comment->rating,
            'img' => $comment->img
        ]);
    }
    /**
     * Удаление определённого отзыва из хранилища
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = $this->service->takeCommentById($id);
        if ($comment->user_id != \Auth::user()->id) {
            return \Response::json(['errors' => ['Вы не можете удалить данный отзыв!']], 403);
        }
        $this->service->deleteComment($comment);

        session(['success' => 'Отзыв успешно удалён!']);
        return \Response::json(['success' => 'true']);
    }
    /**
     * Получение всех отзывов определённого автора
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getAuthorsComments($id)
    {
            $comments = $this->service->takeCommentsByAuthor($id);
            if ($comments->isEmpty()) {
                return redirect()->route('/')->withErrors('Попытка посмотреть отзывы несуществующего автора!');
            }
            $fio = $comments->first()->fio;
            $title = "Отзывы автора $fio";
            return view('comments.index', compact('comments', 'fio', 'title'));
    }
}
