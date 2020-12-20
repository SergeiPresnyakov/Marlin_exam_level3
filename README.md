# Компоненты для создания сайта

Сами компоненты лежат в папке Components. Все остальное нужно для демонстрации и проверки работоспособности компонентов.

### 1. Connection

Компонент **Connection** создает подключение к базе данных, используя PDO.
Для работы нужно передать ассоциативный массив с параметрами подключения к базе данных.

```php
include 'Components/Connection.php';

$databaseConfig = [
		'host' => 'localhost',
		'database' => 'my_database',
		'charset' => 'utf8', // значение по-умолчанию utf8
		'username' => 'root',
		'password' => '' // значение по-умолчанию - без пароля
	];

/* Создание подключения к базе данных */
$pdo = Connection::make($databaseConfig);

/* Пример использования */
$statement = $pdo->prepare("SELECT * FROM table");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

```

### 2. QueryBuilder

Компонент для работы с базой данных. Формирует и исполняет SQL-запросы. В качестве аргумента конструктору нужно передать подключение к базе (PDO object).

```php
include 'Components/QueryBuilder.php';

$pdo = new PDO('mysql:host=localhost;dbname=my_database;charset=utf8', 'root', '');

/* Создание объекта QueryBuilder */
$query = new QueryBuilder($pdo);
```
или так 

```php
include 'Components/Connection.php';
include 'Components/QueryBuilder.php';

$databaseConfig = [
		'host' => 'localhost',
		'database' => 'my_database',
		'charset' => 'utf8',
		'username' => 'root',
		'password' => ''
	];

/* Создание объекта QueryBuilder */
$query = new QueryBuilder(Connection::make($databaseConfig));
```
Обрабатывает запросы 6 видов:

1. Получить все записи из таблицы (getAll)
2. Получить одну запись по id (getById)
3. Получить одну или несколько записей по условию (get)
4. Вставить в таблицу новую запись (insert)
5. Изменить существующую запись (update)
6. Удалить запись из таблицы (delete)

	##### getAll
	Получает все записи из указанной таблицы
	```php
	getAll(string $table) : array
	```

	```php
	/* получить все записи из таблицы users */
	$query = new QueryBuilder($pdo);
    $result = $query->getAll('users'); 
	```

	##### getById
	Получить одну запись по уникальному id
	```php
	getById(string $table, int $id) : array
	```

	```php
	/* получить одного пользователя c id = 1 из таблицы users */
	$query = new QueryBuilder($pdo);
    $user = $query->getById('users', 1);
	```

	##### get
	Получить одну или несколько записей по условию.
	```php
	get(string $table, array $where) : array
	```

	```php
	$query = new QueryBulder($pdo);

	/* получить все данные пользователя John Doe */
	$user = $query->get('users', ['username', '=', 'John Doe']);

	/* получить незабаненных пользователей */
	$activeUsers = $query->get('users', ['status', '<>', 'banned']);
	```
	Параметр where составляется по следующему принципу:
	['поле в таблице', 'отношение', 'значение']

	"отношение" может принимать следующие значения:
		* "=" - равно
		* "<" - меньше
		* ">" - больше
		* "<=" - меньше либо равно
		* ">=" - больше либо равно
		* "<>" - не равно

	##### insert
	Вставить новую запись в базу данных. Возвращает успешность выполнения запроса.
	```php
	insert(string $table, array $fields) : bool
	```

	```php
	/* вставить в таблицу пользователя c именем John Doe и email johndoe@gmail.com */
	$query = new QueryBuilder($pdo);

    $isInsertSuccessful = $query->insert('users', [
    	'name' => 'John Doe',
    	'email' => 'johndoe@gmail.com'
    ]);

    echo $isInsertSuccessful; // true
	```

	##### update
	Обновить существующую запись в базе данных. В качестве аргумента принимает имя таблицы, id записи и поля с новыми данными. В качестве результата возвращает успешность выполнения запроса.
	```php
	update(string $table, int $id, array $fields) : bool
	```

	```php
	$query = new QueryBuilder($pdo);

	/* Установить пользователю с id = 1 новое имя и email */
    $isUpdateSuccessful = $query->update('users', 1, [
    		'name' => 'Jane Smith',
    		'email' => 'janesmith@gmail.com'
    	]);

    echo $isUpdateSuccessful; // true
	```

	##### delete
	Удаляет запись из таблицы по id. Возвращает успешность удаления.
	```php
	delete(string $table, int $id) : bool
	```

	```php
	$query = new QueryBuilder($pdo);

	/* Удалить из таблицы users запись с id = 1 */
    $isDeleteSuccessful = $query->delete('users', 1);
    
    echo $isDeleteSuccessful; // true
	```
### 2. Router
Простой роутер, позволяющий настроить маршрутизацию на вашем сайте и использовать ЧПУ (человекопонятные URL). Как рекомендуется с ним работать:
	1. Перенести файл index.php из корня проекта в отдельную папку. Например public
	2. Настроить сервер таким образом чтобы новый корень проекта сменился с '%rootdirectory%' на '%rootdirectory%/public'.
	3. Прописать массив с конфигом роутера
	4. Передать массив компоненту Router
	5. Вызвать метод, отвечающий за маршрутизацию (page)

Примерная структура проекта:

	/
	Components/
		Router.php
	public/
		index.php
	pages/ - страницы разрешенные для посещения пользователю
		mainpage.php
		about.php
		create.php
		edit.php
	config.php
	404.php

файл config.php
```php
<?php
return [
    // конфиг для роутера
    'router' => [
        '/' => '../pages/mainpage.php',
        '/about' => '../pages/about.php',
        '/create' => '../pages/create.php',
        '/edit' => '../pages/edit.php',
    ]
];
/*
Если переданный роутеру адрес есть в этом конфиге,
то пользователь будет перенаправлен по соответствующему адресу.
В противном случае он будет перенаправлен на страницу с ошибкой 404
*/
```
файл pages/index.php
```php
<?php
include '../Components/Router.php';

/* получим конфиг для роутера */
$config = include '../config.php';

/* передадим конфиг роутеру */
Router::config($config['router']);

/* вызовем маршрутизацию */
Router::page($_SERVER['REQUEST_URI']);
```
Этого уже достаточно, чтобы роутер заработал.
В случае, если возникнет такая надобность, можно просмотреть полученные роутером конфиги, чтобы убедиться что они передались корректно:
```php
var_dump(Router::showConfig());
```
### 4. Input
Простой комонент для работы с отправленными через форму или get-параметры в URL данными.
Включает всего два метода:

	1. Проверка того, отправлены ли данные. (exists)
	2. Получение отправленных данных (get)

чтобы проверить наличие данных, отправленных через метод GET, нужно передать 'get' в качестве аргумента.
```php
exists([string $type = 'post']) : bool
```

```php
<form method="post">
	<input type="text" name="some_input">
	<button type="submit">Submit</button>
</form>

/* при отправке формы */
echo Input::exists(); // true
```
Метод **get** получает введённые данные.
```php
get(string $item) : string
```

```php
/* Получить данные, введённые в поле some_input */
echo Input::get('some_input');
```
### 5. Flash
Компонент для работы с флеш-сообщениями. Использует сессии, так что для работы нужно использовать session_start() в начале каждого файла, где будут использоваться флеш-сообщения.

Содержит три метода:
	
	1. Установить флеш-сообщение (set)
	2. Проверка наличия установленого флеш-сообщения на заданную тему (exists)
	3. Возврат установленного флеш-сообщения на заданную тему (display)

Установить флеш-сообщение
```php
Flash::set(string $theme, string $message) : null
```
Компонент создавался с прицелом на использование в связке с bootstap. Параметр theme для сообщения может принимать любое строковое значение, но рекомендуется использовать
названия классов alert из bootstap (primary, success, warning, danger, info ...)

Проверить, установлено ли флеш-сообщение заданной темы
```php
Flash::exists(string $theme) : bool
```

Вывести флеш-сообщение заданной темы
```php
Flash::display(string $theme) : string
```

Применение
```php
session_start();

/* Задать флеш-сообщение */
Flash::set('success', 'Регистрация прошла успешно!');

echo Flash::exists('success');  // true
```
Вывод флеш-сообщения
```html
<?php session_start();?>
... html code

<!-- сообщение об успешном действии -->
<?php if (Flash::exists('success')):?>
	<div class="alert alert-success">
		<?php echo Flash::display('success');?>
	</div>
<?php endif;?>

<!-- сообщение об ошибке -->
<?php if (Flash::exists('danger')):?>
	<div class="alert alert-danger">
		<?php echo Flash::display('danger');?>
	</div>
<?php endif;?>
```
### 6. Validator
Компонент **Validator** занимается валидацией данных полученных из отправленной формы. Для работы требует подключение к базе данных (QueryBuilder object)

Создание объекта Validator
```php
include 'Components/QueryBuilder.php';
include 'Components/Validator.php';

$pdo = new PDO('mysql:host=localhost;dbname=my_database;charset=utf8', 'root', '');
$db = new QueryBuilder($pdo);
$validation = new Validator($db);
```

Компонент содержит следующие методы:

* passed() - возвращает успешность прохождения валидации (bool)
* errors() - возвращает массив с ошибками валидации
* errors_ul_html() - возвращает ошибки валидации в виде маркированного списка для вставки в HTML-код
* check() - непосредственно выполнение валидации

```php
errors_ul_html() : string (HTML code)
```

```php
check(array $source, array $items) : Validator object
```
Аргумент source это массив, куда попадают данные после отправки формы. Чаще всего это будет глобальный массив $_POST

Аргумент items содержит правила для валидации данных.
Предположим у нас есть форма
```html
<form method='post'>
	<input type='text' name='username'>
	<input type='text' name='email'>
	<input type='password' name='password'>
	<input type='password' name='password_confirm'>
	<button type='submit'>Submit</button>
</form>
```
Валидация данных из этой формы будет выглядеть примерно так
```php
include 'Components/QueryBuilder.php';
include 'Components/Validator.php';

$query = new QueryBuilder($pdo);
$validation = new Validator($query);

$validation->check($_POST, [
		'username' => [
			'required' => true,
			'min' => 2,
			'max' => 30
		],
		'email' => [
			'required' => true,
			'email' => true,
			'unique' => true,
			'max' => 50
		],
		'password' => [
			'required' => true
			'min' => 4
		],
		'password_confirm' => [
			'matches' => 'password'
		]
	]);

echo $validation->passed(); // true
```
Ключи в массиве items совпадают c именами полей в форме. Под каждым ключем хранится массив с правилами для этого элемента. Вот что означают эти правила:

	* required - поле должно быть заполнено
    * min - введённые данные не должны быть короче чем это значение
    * max - не должно быть длиннее чем это значение
    * matches - данные должны совпадать с данными в указанном поле
    * unique - эта запись должна быть уникальна в БД
    * email - данные должны соответствовать формату email

Если валидация не пройдена

```html
<form method='post'>
	<input type='text' name='email'>
	<button type='submit'>Submit</button>
</form>
```

```php
include 'Components/Flash.php'

$validation->check($_POST, [
		'email' => [
			'required' => true,
			'email' => true,
			'max' => 50
		]
	]);
if ($validation->passed()) {
	/* успешная валидация */
	Flash::set('success', 'Это подходящий email');
} else {
	/* сообщение об ошибке и список ошибок */
	Flash::set('danger', 'С этим email что-то не так:' . $validation->errors_ul_html());
}
```
