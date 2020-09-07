# API для создания и открутки рекламных объявлений.

### Реализованные запросы

- Создание  
    Принимаются поля  
    `text` - текст объявления  
    `price` - стоимость показа  
    `amount` - лимит показов  
    `banner` - Картинка в виде файла `multipart/form-data`  
- Обновление  
    Тоже самое, что и при создании, плюс айди объявления, которое обновляется.  
- Получение  
    Возвращает текст и ссылку на картинку.  
    Условия выбора:  
    - Самая высокая цена
    - Кол-во показов не превысило лимит

### Установка

- Установите библиотеки через композер
- Поднимите сервер `nginx/apache` и укажите путь до `project_folder/dir`, там находится начальный `index.php`.  Ну либо встроенный сервер в пхп.
- В папке `config` создайте копию app.php.example в app.php. 
    В нем нужно прописать настройки соединения до базы данных. Если надо, можно заменить пути до миграции и папки сохранения картинок.
- Запустите `php console.php app:install-migrate` для установки таблицы миграции.   
    Да, сейчас только одна таблица, но мы смотрим в гипотетическое будущее проекта.
- Запустите `php console.php app:migrate` для создания таблиц проекта.


### Тестирование

Чтобы запустить тестирование, нужно выполнить  
```
./vendor/bin/phpunit --testdox
```  

Обратите внимание, что используются данные для соединения с бд из `config/app.php`.  
Поэтому тестовый элемент создается в той же таблице.  

## Использованные библиотеки

### [symfony/http-kernel](https://github.com/symfony/http-kernel)

Используется как ядро для преобразования `Request` в `Response`.

### [symfony/routing](https://github.com/symfony/routing)

Взял для создания роутинга и для гипотетического расширения в будущем.  
Вместе с предыдущим компонентом используется и для резолва, определения, контроллера для работы с запросом.  

### [symfony/config](https://github.com/symfony/config)

Используется для подгрузки файлов настроек.  
Хотя настройки настолько просты, что можно было и без него обойтись.  

### [symfony/validator]()

Валидация входящих параметров.  
Плюс обернул это в свой валидатор для передачи простого массива.

### [symfony/mime](https://github.com/symfony/validator)

Чисто для возможности валидации файлов.

### [illuminate/database](https://github.com/illuminate/database)

Работа с базой данных. Возможно слишком большая для текущих задач.

### [illuminate/console](https://github.com/illuminate/console)

Т.к. реализовал миграции из либы с бд, а это нужны консольные команды - поэтому они тоже были подгружены.

### [illuminate/filesystem](https://github.com/illuminate/filesystem)

Чтобы консольная команда миграции могла работать с файлами.


## Что еще можно сделать

### DI
Сейчас при резолве контроллера в него передается просто `Request`, лучше сделать по крутому и внедрять зависимости, которые указаны как параметры метода.

### Миграции, откат
Сейчас у миграций нет отката или обновления. Но это команды такие в библиотеке есть. Нужны обертки в рамках текущего проекта,
т.к. напрямую добавлять в объект консольного приложения нет возможности.

### Middleware
В данный момент, чтобы как то проверить запрос или трансформировать ответ - нужно изменять `\Core\App.php`, что не хорошо для последующей разработки.
Поэтому нужны посредники, через которые проходит запрос и трансформирует его. Таким образом можно настроить проверку прав, проверку типа запроса, автоматически трансформировать в json, логирование и т.п.

### Работа с ФС
Сейчас файлы сохраняются просто в public.
Для лучшей работы лучше вынести это и использовать другие места для хранения папок.

### Файлы конфига
Сейчас есть просто один пхп файл для настроек. Правильнее вынести переменные в тот же .env файл.

### Мелочи

- Вынести некоторые части `\Core\App.php` в отдельные классы. Например, настройка соединения для бд.
- Сделать трансформацию `Request` и `Response` в объекты по интерфейсам из PSR для дальнейшей совместимости. 
    У Symfony есть для этого нужный мост

