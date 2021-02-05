Стартовая страница:
Если можно определить город пользователя по IP, то появляется запрос: “ХХХХХХХ” ваш город? Да/Нет.
Если определить невозможно, то отображается список всех городов для которых созданы отзывы с сортировкой от А к Я.
После выбора города в сессии запоминается выбор - время сессии 2 часа (показываются отзывы выбранного города).
После сессия очищается и появляется запрос выбора города.

Выбираем город и выводятся тексты отзывов относящиеся к этому городу, автор кто оставил отзыв,
не видно контакты автора, нельзя создать новый отзыв и редактировать свой отзыв, для этого нужно авторизоваться.

Чтобы иметь возможность авторизоваться, нужно сначала зарегистрироваться. При регистрации указываются:
ФИО, Email, Телефон, Пароль, Повтор пароля, Код из каптчи.
А также введенный email подтверждается через отправку на указанный email контрольной строки.
После этого можно авторизоваться.

Авторизованный пользователь видит:
Текст отзыва, ФИО автора, когда нажимает на ФИО автора, то всплывает окно, где мы выводится email и телефон автора, а также ссылка "Посмотреть все отзывы автора".
Перейдя по ссылке, можно получить страницу со всеми отзывами автора по всем городам, но с пометкой какой отзыв в какому городу относится или нескольким сразу.

Можно загрузить фото к отзыву, например скан-копию отзыва на бумаге.
Город выбирается из автокомплита, с возможностью выбрать несколько городов или не выбирать вовсе,
тогда отзыв будет относиться ко всем городам сразу.
Если при создании отзыва в базе нет города, который хочет указать клиент, то надо через стороннее АПИ подгрузить город и сохранить в БД городов.

Таблица Города (id, name, date_create)
Таблица Отзывы (id, id_city, title, text, rating, img, id_autor, date_create)
Таблица Пользователи (Авторы) (id, fio, email, phone, date_create, password)

Валидация:
Название отзыва (до 100 символов)
Текст отзыва (до 255 символов)
Рейтинг (целое число от 1 до 5)

Вёрстка - bootstrap
Прелоадер на создание, удаление, редактирование во время работы ajax.

Подготовлены миграции.
Реализованы шаблоны: Service для выполнения действий в контроллерах и Repository для получения данных из источников.
