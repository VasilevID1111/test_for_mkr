-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 14 2022 г., 18:15
-- Версия сервера: 10.4.24-MariaDB
-- Версия PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `neighbours`
--

-- --------------------------------------------------------

--
-- Структура таблицы `adverts`
--

CREATE TABLE `adverts` (
  `id` int(5) NOT NULL,
  `userid` int(5) NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `adverts`
--

INSERT INTO `adverts` (`id`, `userid`, `type`, `categories`, `name`, `description`, `image_url`, `cost`) VALUES
(1, 5, 'Заявки', 'Компьютерная помощь', 'Компьютер перестал включаться и странно дымится!!!', 'Друзья! Случилась беда: установила я ведьмака нового на свой старый компьютер, а он сначала запустил его с чёрным экраном, потом звуки очень странные были пугающие, а потом он вовсе вырубился и задымился!!! Помогите, пожалуйста, очень хочется нового ведьмака пройти...', 'pictures/unhappypc.jpg', 1500),
(2, 4, 'Заявки', 'Услуги сантехника', 'Сломалась ванна…', 'Пожалуйста, без лишних вопросов. Сломалась ванна напополам, и срочно нужно её поменять. Ванну я уже купил и мне её привезли, предложили установить - но мне кажется, что они цену слишком накручивают. Поможете?', 'pictures/brokenbath.jpg', 3000),
(3, 5, 'Объявления', 'Швейные услуги', 'Штопаю, шью, подшиваю.', 'Только давайте без шуток: ваше пулевое ранение я штопать не буду - работаю только с тканью. Всё, что касается этого - я сделать смогу. Нитки есть практически всех цветов, так что о них я позабочусь.', 'pictures/sewingmachine.jpg', 500),
(4, 3, 'Объявления', 'Автомобильная помощь', 'Начал копаться в машинах с 10-ти лет…', 'Мой батя учил меня копаться в машинах ещё в 10 лет, и я знаю в них практически всё: от свечей и карбюраторов до наличия запчастей в нашем городе на твою старенькую иномарку. Держу свой автомобильный сервисный центр, но услуги, подразумевающие не выходить за пределы дома обходятся дешевле, согласитесь?))', 'pictures/servicecenter.jpg', 441),
(5, 4, 'Заявки', 'Швейные услуги', 'Порвалась рубаха - вылетили все пуговицы.', 'Я не помню, что было прошлой ночью после бара, но в баре у меня рубашка была ещё целой. Просыпаюсь, а тут такое… Завтра важная встреча, а это моя любимая рубашка. Срочно нужно заштопать!!!', 'pictures/tornshirt.jpg', 1000),
(6, 2, 'Заявки', 'Автомобильная помощь', 'Нужно поменять резину.', 'Начались холода, и чтобы не попасть в неприятную ситуацию - мне нужно поменять шины. Зимнюю резину уже купила, но времени поменять совсем не хватает. Поможете?', 'pictures/wintertires.jpg', 500),
(7, 2, 'Объявления', 'Компьютерная помощь', 'Предлагаю свои услуги компьютерного мастера!', 'Нужно переустановить винду? Надоели все эти однотипные объявления возле подъезда о том, что у вас по соседству якобы живёт компьютерный мастер, а на деле это человек из центра, знающий компьютеры чуть лучше вас? У вас есть выбор! Пишите мне - сделаю всё честно и качественно, а главное - по реальным ценам!', 'pictures/reinstallingwindows.jpg', 300);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int(5) NOT NULL,
  `services_userid` int(5) NOT NULL,
  `recipient_userid` int(5) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` int(1) NOT NULL,
  `date` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agree` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `services_userid`, `recipient_userid`, `text`, `rate`, `date`, `agree`) VALUES
(1, 2, 5, 'Переустановила мне винду! Давно хотела обновиться с Windows 3 до Windows XP. Спасибо!!!', 5, '05.09.2022', 2),
(2, 3, 4, 'Арендовал машину для съёмок. Спасибо! Но она не совсем прошла краш-тест…', 4, '13.09.2022', 0),
(3, 5, 3, 'Шьёт просто божественно, золотые руки!', 5, '14.09.2022', 2),
(4, 2, 3, 'Написала прогу для моего сервисного центра. Такая молодец прям вообще!', 5, '07.10.2022', 3),
(5, 2, 1, 'Сказала, что версию винды, которую я хочу, ещё не выпустили. Ужас, пришлось довольствоваться 11. Хоть и на этом спасибо.', 3, '15.10.2022', 0),
(6, 3, 2, 'Вау, машина как до седьмого неба полетела после его ремонта. Какой умничка!', 5, '31.10.2022', 1),
(7, 2, 4, 'Очень приятно видеть компетентную девушку в области IT.', 5, '03.11.2022', 4),
(8, 5, 2, 'Сшила мне классный чехол для моего ноутбука. Теперь чувствую себя крутой, когда иду на работу!', 5, '08.11.2022', 2),
(9, 5, 4, 'Зашила рубашку и дала пару ценных советов! Спасибо!', 5, '10.11.2022', 3),
(10, 2, 5, 'Оказалось, что у меня компьютер больше не подлежит восстановлению. Ну мы не отчаялись, она принесла свой ноут с ведьмаком, и пока жарили шашлычки на системнике - проходили игру.', 5, '11.11.2022', 3),
(11, 3, 1, 'Мгновенно поменял резину, даже домкратом не дёрнулся! Какой молодец, золотые руки… Ещё бы на права за меня сдал, цены бы ему не было.', 5, '11.11.2022', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `image_url`, `description`, `specialization`, `skills`, `phone`, `email`) VALUES
(1, 'Дамиан Евгеньевич Марлей', 'pictures/DamianMarley.jpg', 'Ваш сосед из 420.', 'Нет', 'Нет', '+73533354248', 'welcometojamrock@gmail.com'),
(2, 'Екатерина Панкратьевна Лель', 'pictures/KateLel.jpg', 'Ваш компьютерный мастер.', 'IT', 'Знаю всё о компьютерах, кроме того, откуда появляется windows.', '+71509308561', 'sevensky@yandex.ru'),
(3, 'Добромир Святославович Иванов', 'pictures/Dobromir.jpg', 'Первоклассный автомеханик ждёт ваших заявок.', 'Автомеханик', 'Знаю всё о машинах. Машины - это моя страсть. Даже девушку я не называю своей ласточкой.', '+75497182445', 'mirpay@mail.ru'),
(4, 'Александр Андреевич Петров', 'pictures/AlexanderPetrov.jpg', 'Я и тут появился!', 'Нет', 'Нет', '+77902404663', 'allserialsandfilms@yandex.ru'),
(5, 'Трисс Меригольд', 'pictures/TrissMerigold.jpg', 'Магия нынче под запретом, и я решила стать швеёй.', 'Швея', 'Штопаю, шью, подшиваю.', '+70330596097', 'witch3@novigrad.leave');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `adverts`
--
ALTER TABLE `adverts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
