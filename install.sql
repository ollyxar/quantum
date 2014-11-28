-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Вер 28 2014 р., 17:57
-- Версія сервера: 5.1.73-log
-- Версія PHP: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `dev`
--

-- --------------------------------------------------------

--
-- Структура таблиці `lang_details`
--

CREATE TABLE IF NOT EXISTS `lang_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `lang_details`
--

INSERT INTO `lang_details` (`id`, `name`, `description`, `picture`, `ordering`) VALUES
(1, 'en', 'English', '/upload/images/flags/en.png', 1),
(2, 'uk', 'Українська', '/upload/images/flags/ua.png', 2),
(3, 'ru', 'Русский', '/upload/images/flags/ru.png', 3);

-- --------------------------------------------------------

--
-- Структура таблиці `main_menu`
--

CREATE TABLE IF NOT EXISTS `main_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `caption_uk` varchar(255) NOT NULL,
  `caption_en` varchar(255) NOT NULL,
  `caption_ru` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп даних таблиці `main_menu`
--

INSERT INTO `main_menu` (`id`, `parent`, `path`, `ordering`, `enabled`, `caption_uk`, `caption_en`, `caption_ru`) VALUES
(1, 0, 'route=home', 1, 1, 'Домашня', 'Home', 'Главная'),
(2, 0, 'route=pages&page_id=3', 7, 1, 'Контакти', 'Contacts', 'Контакты'),
(3, 0, '#', 3, 1, 'Про нас', 'About', 'О нас'),
(4, 0, '#', 4, 1, 'Блок новин', 'News blog', 'Блок новостей'),
(5, 0, 'route=photo', 5, 1, 'Фото', 'Photos', 'Фото'),
(6, 3, 'route=pages&page_id=1', 0, 1, 'Про проект', 'About project', 'О проекте'),
(8, 4, 'route=materials&material_id=10', 2, 1, 'Бізнесові новини', 'Business news', 'Бизнесс новости'),
(10, 0, 'route=feedback', 7, 1, 'Зворотній зв''язок', 'Feedback', 'Обратная связь'),
(11, 0, 'route=reviews', 7, 1, 'Відгуки', 'Reviews', 'Отзывы'),
(12, 4, 'route=materials&material_id=9', 1, 1, 'Новини', 'News', 'Новости');

-- --------------------------------------------------------

--
-- Структура таблиці `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `is_category` tinyint(1) NOT NULL DEFAULT '0',
  `preview` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `caption_uk` varchar(255) NOT NULL,
  `description_uk` varchar(255) NOT NULL,
  `title_uk` text NOT NULL,
  `descr_uk` text NOT NULL,
  `kw_uk` text NOT NULL,
  `text_uk` text NOT NULL,
  `caption_en` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL,
  `title_en` text NOT NULL,
  `descr_en` text NOT NULL,
  `kw_en` text NOT NULL,
  `text_en` text NOT NULL,
  `caption_ru` varchar(255) NOT NULL,
  `description_ru` varchar(255) NOT NULL,
  `title_ru` text NOT NULL,
  `descr_ru` text NOT NULL,
  `kw_ru` text NOT NULL,
  `text_ru` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп даних таблиці `materials`
--

INSERT INTO `materials` (`id`, `parent_id`, `is_category`, `preview`, `date_added`, `enabled`, `caption_uk`, `description_uk`, `title_uk`, `descr_uk`, `kw_uk`, `text_uk`, `caption_en`, `description_en`, `title_en`, `descr_en`, `kw_en`, `text_en`, `caption_ru`, `description_ru`, `title_ru`, `descr_ru`, `kw_ru`, `text_ru`) VALUES
(1, 9, 0, '/upload/images/odfde47kf.jpg', '2013-07-02 08:14:23', 1, 'Новий день', '', '', '', '', '', 'New day', '', '', '', '', '', 'New day', '', '', '', '', ''),
(2, 9, 0, '', '2013-07-06 16:32:23', 1, 'Новина 1', '', '', '', '', '', 'New 1', '', '', '', '', '', 'New 1', '', '', '', '', ''),
(3, 9, 0, '', '2013-07-06 16:32:51', 1, 'Новина 2', '', '', '', '', '', 'New 2', '', '', '', '', '', 'New 2', '', '', '', '', ''),
(4, 9, 0, '', '2013-07-06 16:33:11', 1, 'Новина 3', '', '', '', '', '', 'New 3', '', '', '', '', '', 'New 3', '', '', '', '', ''),
(5, 9, 0, '', '2013-07-06 16:33:24', 1, 'Новина 4', '', '', '', '', '', 'New 4', '', '', '', '', '', 'New 4', '', '', '', '', ''),
(6, 9, 0, '', '2013-07-06 16:33:36', 1, 'Новина 5', '', '', '', '', '', 'New 5', '', '', '', '', '', 'New 5', '', '', '', '', ''),
(7, 10, 0, '', '2013-07-06 16:33:50', 1, 'Новина 6', '', '', '', '', '', 'new 6', '', '', '', '', 'Some text in new 6', 'new 6', '', '', '', '', 'Some text in new 6'),
(9, 0, 1, '', '2013-08-03 14:07:46', 1, 'Новини', '', 'Новини', '', '', '', 'News', '', 'News', 'News', '', '', 'News', '', 'News', 'News', '', ''),
(10, 9, 1, '/upload/images/olia.png', '2013-08-04 13:50:58', 1, 'Бізнесові новини', 'Короткий опис', 'Бізнесові новини', '', '', '', 'Business news', 'Small description', 'Business news', '', '', '<p abp="1176">You can find some news from business here</p>\r\n', 'Business news', 'Краткое описание', 'Business news', '', '', '<p abp="1179">You can find some news from business here</p>\r\n'),
(11, 10, 0, '/upload/images/Clipboard01.png', '2013-09-02 11:26:33', 1, 'Гроші ростуть', '', '', '', '', '', 'Money growing up', '', '', '', '', '', 'Money growing up', '', '', '', '', ''),
(12, 9, 0, '/upload/images/photos/0412.jpg', '2013-09-02 20:20:10', 1, 'hjhgj', '', '', '', '', '', 'jhgjgh', '', '', '', '', '', 'jhgjgh', '', '', '', '', ''),
(13, 9, 0, '/upload/images/photos/3172.jpeg', '2014-08-10 17:02:10', 1, 'Поплаваємо сьогодні!', 'Читайте більше всередині', '', '', '', '<p abp="67"><strong abp="68">Lorem Ipsum</strong> - це текст-&quot;риба&quot;, що використовується в друкарстві та дизайні. Lorem Ipsum є, фактично, стандартною &quot;рибою&quot; аж з XVI сторіччя, коли невідомий друкар взяв шрифтову гранку та склав на ній підбірку зразків шрифтів. &quot;Риба&quot; не тільки успішно пережила п&#39;ять століть, але й прижилася в електронному верстуванні, залишаючись по суті незмінною. Вона популяризувалась в 60-их роках минулого сторіччя завдяки виданню зразків шрифтів Letraset, які містили уривки з Lorem Ipsum, і вдруге - нещодавно завдяки програмам комп&#39;ютерного верстування на кшталт Aldus Pagemaker, які використовували різні версії Lorem Ipsum.</p>\r\n', 'Lets swim today!', 'Read more inside', '', '', '', '<p abp="68"><strong abp="69">Lorem Ipsum</strong> &egrave; un testo segnaposto utilizzato nel settore della tipografia e della stampa. Lorem Ipsum &egrave; considerato il testo segnaposto standard sin dal sedicesimo secolo, quando un anonimo tipografo prese una cassetta di caratteri e li assembl&ograve; per preparare un testo campione. &Egrave; sopravvissuto non solo a pi&ugrave; di cinque secoli, ma anche al passaggio alla videoimpaginazione, pervenendoci sostanzialmente inalterato. Fu reso popolare, negli anni &rsquo;60, con la diffusione dei fogli di caratteri trasferibili &ldquo;Letraset&rdquo;, che contenevano passaggi del Lorem Ipsum, e pi&ugrave; recentemente da software di impaginazione come Aldus PageMaker, che includeva versioni del Lorem Ipsum.</p>\r\n', 'Lets swim today!', 'Читайте больше внутри', '', '', '', '<p abp="67"><strong abp="68">Lorem Ipsum</strong> - это текст-&quot;рыба&quot;, часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной &quot;рыбой&quot; для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.</p>\r\n');

-- --------------------------------------------------------

--
-- Структура таблиці `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `rr` int(2) NOT NULL DEFAULT '2',
  `rw` int(2) NOT NULL DEFAULT '2',
  `has_ui` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп даних таблиці `modules`
--

INSERT INTO `modules` (`id`, `name`, `route`, `position`, `description`, `params`, `rr`, `rw`, `has_ui`, `enabled`, `ordering`) VALUES
(1, 'languagebox', '', 'top-right', 'Language menu', '', 2, 2, 0, 1, 1),
(2, 'mainmenu', '', 'top-menu', 'Main menu', 'a:1:{s:2:"ha";b:1;}', 3, 3, 1, 1, 2),
(3, 'materials', 'materials', 'content', 'Materials', 'a:4:{s:14:"count_per_page";s:1:"4";s:10:"details_en";s:7:"Details";s:10:"details_uk";s:20:"Детальніше";s:10:"details_ru";s:7:"Details";}', 4, 3, 1, 1, 2),
(4, 'staticpages', 'pages', 'content', 'Static pages', '', 3, 3, 1, 1, 2),
(5, 'slider', 'home', 'slider', 'Slider', 'a:1:{s:6:"slides";a:2:{i:0;a:2:{s:3:"src";s:24:"/upload/images/12t10.jpg";s:4:"link";s:11:"/about.html";}i:1;a:2:{s:3:"src";s:32:"/upload/images/30bridge-ipad.jpg";s:4:"link";s:1:"/";}}}', 2, 2, 1, 1, 1),
(6, 'photos', 'photo', 'content', 'Photos', 'a:10:{s:8:"title_en";s:21:"Photographic pictutes";s:5:"kw_en";s:0:"";s:8:"descr_en";s:0:"";s:8:"title_uk";s:20:"Фотознімки";s:5:"kw_uk";s:0:"";s:8:"descr_uk";s:0:"";s:4:"page";N;s:8:"title_ru";s:21:"Photographic pictutes";s:5:"kw_ru";s:0:"";s:8:"descr_ru";s:0:"";}', 3, 3, 1, 1, 1),
(7, 'feedback', 'feedback', 'content', 'Feedback', 'a:44:{s:8:"title_en";s:8:"Feedback";s:5:"kw_en";s:0:"";s:8:"descr_en";s:0:"";s:7:"info_en";s:825:"<div abp="1317" class="embed-responsive embed-responsive-4by3"><iframe class="embed-responsive-item" frameborder="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=%D1%83%D0%BB%D0%B8%D1%86%D0%B0+%D0%93%D0%B0%D0%B9%D0%B4%D0%B0%D1%80%D0%B0,+%D0%A1%D1%96%D0%BC%D1%84%D0%B5%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C,+%D0%9A%D1%80%D0%B8%D0%BC,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0&amp;aq=0&amp;oq=%D0%A1%D1%96%D0%BC%D1%84%D0%B5%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C,+%D0%9A%D1%80%D0%B8%D0%BC,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0+%D0%93%D0%B0%D0%B9%D0%B4%D0%B0&amp;sll=44.951466,34.100704&amp;sspn=0.026848,0.066047&amp;ie=UTF8&amp;hq=&amp;hnear=ulitsa+Gaydara,+Simferopol'',+Avtonomnaya+Respublika+Krym,+Ukraine&amp;t=m&amp;z=14&amp;ll=44.968647,34.096409&amp;output=embed"></iframe></div>\r\n";s:10:"caption_en";s:11:"Contact us!";s:7:"send_en";s:4:"Send";s:7:"sent_en";s:67:"Message has been sent successfully! We''ll contact you soon! Thanks!";s:15:"message_fail_en";s:53:"Error ocurred while sending message! Please try later";s:13:"empty_vars_en";s:43:"Empty data. Please fill all required fields";s:20:"email_placeholder_en";s:16:"Enter your email";s:19:"name_placeholder_en";s:15:"Enter your name";s:20:"phone_placeholder_en";s:27:"Enter your phone (optional)";s:22:"message_placeholder_en";s:17:"Type your message";s:8:"title_uk";s:25:"Зв''язок з нами";s:5:"kw_uk";s:0:"";s:8:"descr_uk";s:0:"";s:7:"info_uk";s:825:"<div abp="1320" class="embed-responsive embed-responsive-4by3"><iframe class="embed-responsive-item" frameborder="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=%D1%83%D0%BB%D0%B8%D1%86%D0%B0+%D0%93%D0%B0%D0%B9%D0%B4%D0%B0%D1%80%D0%B0,+%D0%A1%D1%96%D0%BC%D1%84%D0%B5%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C,+%D0%9A%D1%80%D0%B8%D0%BC,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0&amp;aq=0&amp;oq=%D0%A1%D1%96%D0%BC%D1%84%D0%B5%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C,+%D0%9A%D1%80%D0%B8%D0%BC,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0+%D0%93%D0%B0%D0%B9%D0%B4%D0%B0&amp;sll=44.951466,34.100704&amp;sspn=0.026848,0.066047&amp;ie=UTF8&amp;hq=&amp;hnear=ulitsa+Gaydara,+Simferopol'',+Avtonomnaya+Respublika+Krym,+Ukraine&amp;t=m&amp;z=14&amp;ll=44.968647,34.096409&amp;output=embed"></iframe></div>\r\n";s:10:"caption_uk";s:31:"Зв''яжіться з нами";s:7:"send_uk";s:18:"Надіслати";s:7:"sent_uk";s:109:"Повідомлення надіслано! Ми з''яжемся з вами найближчим часом";s:15:"message_fail_uk";s:138:"Виникла помилка під час надсилання повідомлення. Повторіть спробу пізніше.";s:13:"empty_vars_uk";s:32:"Дані не заповнені";s:20:"email_placeholder_uk";s:37:"Введіть свою е-пошту";s:19:"name_placeholder_uk";s:31:"Введіть своє ім''я";s:20:"phone_placeholder_uk";s:67:"Введіть свій телефон (не обов''язково)";s:22:"message_placeholder_uk";s:50:"Напишіть своє повідомлення";s:8:"title_ru";s:30:"Связаться с нами";s:5:"kw_ru";s:0:"";s:8:"descr_ru";s:0:"";s:7:"info_ru";s:825:"<div abp="1323" class="embed-responsive embed-responsive-4by3"><iframe class="embed-responsive-item" frameborder="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=%D1%83%D0%BB%D0%B8%D1%86%D0%B0+%D0%93%D0%B0%D0%B9%D0%B4%D0%B0%D1%80%D0%B0,+%D0%A1%D1%96%D0%BC%D1%84%D0%B5%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C,+%D0%9A%D1%80%D0%B8%D0%BC,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0&amp;aq=0&amp;oq=%D0%A1%D1%96%D0%BC%D1%84%D0%B5%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C,+%D0%9A%D1%80%D0%B8%D0%BC,+%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0+%D0%93%D0%B0%D0%B9%D0%B4%D0%B0&amp;sll=44.951466,34.100704&amp;sspn=0.026848,0.066047&amp;ie=UTF8&amp;hq=&amp;hnear=ulitsa+Gaydara,+Simferopol'',+Avtonomnaya+Respublika+Krym,+Ukraine&amp;t=m&amp;z=14&amp;ll=44.968647,34.096409&amp;output=embed"></iframe></div>\r\n";s:10:"caption_ru";s:31:"Свяжитесь с нами!";s:7:"send_ru";s:18:"Отправить";s:7:"sent_ru";s:65:"Спасибо! Ваше сообщение отправлено.";s:15:"message_fail_ru";s:121:"Произошла ошибка во время отправки данных. Повторите запрос позже";s:13:"empty_vars_ru";s:82:"Пожалуйста, заполните все обязательные поля.";s:20:"email_placeholder_ru";s:27:"Введите ваш email";s:19:"name_placeholder_ru";s:30:"Введите ваше имя";s:20:"phone_placeholder_ru";s:66:"Введите ваш телефон (не обязательно)";s:22:"message_placeholder_ru";s:42:"Введите ваше сообщение";s:16:"captcha_required";s:1:"1";s:14:"email_required";s:1:"1";s:13:"name_required";s:1:"1";s:14:"phone_required";s:1:"0";s:16:"message_required";s:1:"1";}', 3, 3, 1, 1, 1),
(8, 'sitereviews', 'reviews', 'content', 'Site reviews', 'a:37:{s:4:"page";s:0:"";s:8:"title_en";s:12:"Site reviews";s:8:"descr_en";s:0:"";s:5:"kw_en";s:0:"";s:19:"leave_review_btn_en";s:17:"Leave your review";s:15:"form_caption_en";s:13:"Send a review";s:19:"name_placeholder_en";s:9:"Your name";s:20:"email_placeholder_en";s:10:"Your email";s:21:"review_placeholder_en";s:11:"Your review";s:11:"post_btn_en";s:4:"Post";s:13:"cancel_btn_en";s:6:"Cancel";s:13:"error_name_en";s:21:"Please type your name";s:13:"error_text_en";s:22:"Please leave some text";s:8:"title_uk";s:29:"Вiдгуки про сайт";s:8:"descr_uk";s:0:"";s:5:"kw_uk";s:0:"";s:19:"leave_review_btn_uk";s:28:"Залиште відгук!";s:15:"form_caption_uk";s:33:"Відправка відгука";s:19:"name_placeholder_uk";s:16:"Ваше ім''я";s:20:"email_placeholder_uk";s:12:"Ваш Email";s:21:"review_placeholder_uk";s:12:"Відгук";s:11:"post_btn_uk";s:18:"Надіслати";s:13:"cancel_btn_uk";s:18:"Відмінити";s:13:"error_name_uk";s:31:"Ім''я не заповнене";s:13:"error_text_uk";s:49:"Текст відгуку не заповнено";s:8:"title_ru";s:26:"Отзывы о сайте";s:8:"descr_ru";s:0:"";s:5:"kw_ru";s:0:"";s:19:"leave_review_btn_ru";s:27:"Оставить отзыв";s:15:"form_caption_ru";s:29:"Отправка отзыва";s:19:"name_placeholder_ru";s:15:"Ваше имя";s:20:"email_placeholder_ru";s:12:"Ваш Email";s:21:"review_placeholder_ru";s:23:"Текст отзыва";s:11:"post_btn_ru";s:18:"Отправить";s:13:"cancel_btn_ru";s:12:"Отмена";s:13:"error_name_ru";s:21:"Введите имя";s:13:"error_text_ru";s:38:"Введите текст отзыва";}', 3, 3, 1, 1, 1),
(9, 'reviewswidget', '', 'reviews-w', 'Reviews widget', '', 3, 3, 0, 0, 1),
(10, 'account', 'account', 'content', 'User account', 'a:101:{s:19:"placeholder_name_en";s:4:"Name";s:20:"placeholder_email_en";s:5:"Email";s:23:"placeholder_password_en";s:8:"Password";s:11:"old_pass_en";s:12:"Old password";s:11:"new_pass_en";s:12:"New password";s:21:"title_registration_en";s:17:"User registration";s:16:"title_account_en";s:10:"My account";s:14:"title_login_en";s:5:"Login";s:9:"log_in_en";s:6:"Log in";s:10:"log_out_en";s:7:"Log out";s:10:"confirm_en";s:7:"Confirm";s:7:"save_en";s:4:"Save";s:8:"agree_en";s:87:"I agree with &lt;a href=&quot;%s&quot; target=&quot;_blank&quot;&gt;Agreement&lt;/a&gt;";s:14:"remember_me_en";s:28:"Remember me for next 14 days";s:17:"account_exists_en";s:22:"Account already exists";s:18:"not_valid_email_en";s:18:"Email is not valid";s:17:"not_valid_name_en";s:17:"Name is not valid";s:21:"not_valid_password_en";s:21:"Password is not valid";s:20:"not_valid_captcha_en";s:20:"Captcha is not valid";s:24:"registration_finished_en";s:38:"Registration was finished successfully";s:18:"additional_text_en";s:97:"We have been sent instructions to your email. Please follow instructions to confirm your account.";s:20:"confirmation_mail_en";s:308:"&lt;h1&gt;Congratulations! You have successfully registered on  MySite!&lt;/h1&gt;\r\n&lt;p&gt;To finish your registration just follow the link.&lt;/p&gt;\r\n&lt;p&gt;&lt;a href=&quot;{confirm_link}&quot;&gt;Confirm registration&lt;/a&gt;&lt;/p&gt;\r\n&lt;p&gt;If it was not You just ignore this message.&lt;/p&gt;";s:20:"account_confirmed_en";s:53:"Congratulations! You have been confirmed your account";s:17:"data_incorrect_en";s:14:"Data incorrect";s:22:"email_not_confirmed_en";s:22:"Email is not confirmed";s:16:"login_success_en";s:16:"Successful login";s:16:"unknown_error_en";s:13:"Unknown error";s:19:"restore_password_en";s:16:"Restore password";s:20:"instructions_sent_en";s:26:"Instructions has been sent";s:17:"change_my_pass_en";s:18:"Change my password";s:9:"cancel_en";s:6:"Cancel";s:18:"changes_applied_en";s:15:"Changes applied";s:15:"restore_mail_en";s:118:"&lt;p&gt;To restore your password please follow &lt;a href=&quot;{restore_link}&quot;&gt;this&lt;/a&gt; link&lt;/p&gt;";s:19:"placeholder_name_uk";s:7:"Ім''я";s:20:"placeholder_email_uk";s:5:"Email";s:23:"placeholder_password_uk";s:12:"Пароль";s:11:"old_pass_uk";s:25:"Старий пароль";s:11:"new_pass_uk";s:23:"Новий пароль";s:21:"title_registration_uk";s:43:"Реєстрація користувача";s:16:"title_account_uk";s:19:"Мій акаунт";s:14:"title_login_uk";s:8:"Вхід";s:9:"log_in_uk";s:12:"Увійти";s:10:"log_out_uk";s:10:"Вийти";s:10:"confirm_uk";s:22:"Підтвердити";s:7:"save_uk";s:16:"Зберегти";s:8:"agree_uk";s:98:"Я згоден зі &lt;a href=&quot;%s&quot; target=&quot;_blank&quot;&gt;Згодою&lt;/a&gt;";s:14:"remember_me_uk";s:47:"Запам''ятати мене на 14 днів";s:17:"account_exists_uk";s:30:"Акаунт вже існує";s:18:"not_valid_email_uk";s:29:"Не коректний email";s:17:"not_valid_name_uk";s:29:"Не коректне ім''я";s:21:"not_valid_password_uk";s:36:"Не коректний пароль";s:20:"not_valid_captcha_uk";s:29:"Не коректна captcha";s:24:"registration_finished_uk";s:54:"Реєстрацію успішно завершено";s:18:"additional_text_uk";s:180:"Ми вислали Вам інструкції на Ваш Email. Дотримуйтесь інструкцій щоб підтвердити Ваш обліковий запис.";s:20:"confirmation_mail_uk";s:444:"&lt;h1&gt;Вітаємо! Ви успішно зареєструвалися на MySite!&lt;/h1&gt;\r\n&lt;p&gt;Для підтвердження акаунту перейдіть за посиланням нижче.&lt;/p&gt;\r\n&lt;p&gt;&lt;a href=&quot;{confirm_link}&quot;&gt;Підтвердити мій акаунт&lt;/a&gt;&lt;/p&gt;\r\n&lt;p&gt;Якщо то були не Ви, просто проігноруйте листа.&lt;/p&gt;";s:20:"account_confirmed_uk";s:63:"Вітаємо! Ви підтведили свій акаунт";s:17:"data_incorrect_uk";s:23:"Невірні дані";s:22:"email_not_confirmed_uk";s:35:"Email не підтверджено";s:16:"login_success_uk";s:25:"Успішний вхід";s:16:"unknown_error_uk";s:31:"Невідома помилка";s:19:"restore_password_uk";s:31:"Відновити пароль";s:20:"instructions_sent_uk";s:48:"Інструкції було надіслано";s:17:"change_my_pass_uk";s:34:"Змінити мій пароль";s:9:"cancel_uk";s:18:"Скасувати";s:18:"changes_applied_uk";s:33:"Зміни застосовано";s:15:"restore_mail_uk";s:160:"&lt;p&gt;Щоб відновити пароль перейдіть за &lt;a href=&quot;{restore_link}&quot;&gt;цим&lt;/a&gt; посиланням&lt;/p&gt;";s:19:"placeholder_name_ru";s:6:"Имя";s:20:"placeholder_email_ru";s:5:"Email";s:23:"placeholder_password_ru";s:12:"Пароль";s:11:"old_pass_ru";s:25:"Старый пароль";s:11:"new_pass_ru";s:23:"Новый пароль";s:21:"title_registration_ru";s:47:"Регистрация пользователя";s:16:"title_account_ru";s:21:"Мой аккаунт";s:14:"title_login_ru";s:8:"Вход";s:9:"log_in_ru";s:8:"Вход";s:10:"log_out_ru";s:10:"Выход";s:10:"confirm_ru";s:22:"Подтвердить";s:7:"save_ru";s:18:"Сохранить";s:8:"agree_ru";s:111:"Я согласен с  &lt;a href=&quot;%s&quot; target=&quot;_blank&quot;&gt;Соглашением&lt;/a&gt;";s:14:"remember_me_ru";s:44:"Запомнить меня на 14 дней";s:17:"account_exists_ru";s:42:"Аккаунт уже существует";s:18:"not_valid_email_ru";s:31:"Не корректный email";s:17:"not_valid_name_ru";s:32:"Не корректное имя";s:21:"not_valid_password_ru";s:38:"Не корректный пароль";s:20:"not_valid_captcha_ru";s:33:"Не корректная captcha";s:24:"registration_finished_ru";s:56:"Регистрация успешно завершена";s:18:"additional_text_ru";s:169:"Мы выслали на Вашу почту инструкцию. Для подтверждения Вашего аккаунта следуйте инструкции.";s:20:"confirmation_mail_ru";s:461:"&lt;h1&gt;Поздравляем! Вы успешно зарегистрировались на  MySite!&lt;/h1&gt;\r\n&lt;p&gt;Для завершения регистрации перейдите по ссылке ниже.&lt;/p&gt;\r\n&lt;p&gt;&lt;a href=&quot;{confirm_link}&quot;&gt;Подтвердить аккаунт&lt;/a&gt;&lt;/p&gt;\r\n&lt;p&gt;Если это были не Вы, просто проигнорируйте это письмо.&lt;/p&gt;";s:20:"account_confirmed_ru";s:75:"Поздравляем! Вы подтвердили свой аккаунт";s:17:"data_incorrect_ru";s:29:"Неверные данные";s:22:"email_not_confirmed_ru";s:33:"Email не подтвержден";s:16:"login_success_ru";s:25:"Успешный вход";s:16:"unknown_error_ru";s:35:"Неизвестная ошибка";s:19:"restore_password_ru";s:37:"Восстановить пароль";s:20:"instructions_sent_ru";s:41:"Инструкции отправлены";s:17:"change_my_pass_ru";s:36:"Изменить мой пароль";s:9:"cancel_ru";s:16:"Отменить";s:18:"changes_applied_ru";s:37:"Изменения применены";s:15:"restore_mail_ru";s:164:"&lt;p&gt;Для восстановления пароля перейдите по &lt;a href=&quot;{restore_link}&quot;&gt;этой&lt;/a&gt; ссылке&lt;/p&gt;";s:16:"captcha_required";s:1:"1";s:9:"agreement";s:21:"route=pages&page_id=1";}', 3, 3, 1, 1, 1),
(11, 'accountwidget', '', 'top-right', 'Account widget', '', 3, 3, 0, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблиці `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `settings`
--

INSERT INTO `settings` (`id`, `data`) VALUES
(1, 'a:9:{s:12:"site_name_en";s:10:"Ollyxar en";s:13:"site_descr_en";s:34:"This is sample description of site";s:10:"site_kw_en";s:13:"Some keywords";s:12:"site_name_uk";s:10:"Ollyxar uk";s:13:"site_descr_uk";s:34:"Зразок опису сайту";s:10:"site_kw_uk";s:24:"Ключовi слова";s:12:"site_name_ru";s:10:"Ollyxar ru";s:13:"site_descr_ru";s:42:"Простое описание сайта";s:10:"site_kw_ru";s:27:"Ключевые слова";}');

-- --------------------------------------------------------

--
-- Структура таблиці `site_reviews`
--

CREATE TABLE IF NOT EXISTS `site_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `post` varchar(1000) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `rating` smallint(6) NOT NULL DEFAULT '4',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `site_reviews`
--

INSERT INTO `site_reviews` (`id`, `name`, `email`, `post`, `photo`, `enabled`, `rating`) VALUES
(1, 'Paul', '', 'Hello! This is my first review', '/upload/images/photos/98%20270913%20Yes%20(2).jpg', 1, 5),
(2, 'David', '', 'Hello World! :)', '/upload/images/image(2).png', 1, 4),
(3, 'Ivan', 'ivan@mail.com', 'Nice page!', '/upload/images/photos/98%20270913%20Yes%20(2).jpg', 1, 4);

-- --------------------------------------------------------

--
-- Структура таблиці `static_pages`
--

CREATE TABLE IF NOT EXISTS `static_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `caption_uk` varchar(255) NOT NULL,
  `text_uk` text NOT NULL,
  `title_uk` text NOT NULL,
  `kw_uk` text NOT NULL,
  `descr_uk` text NOT NULL,
  `caption_en` varchar(255) NOT NULL,
  `text_en` text NOT NULL,
  `title_en` text NOT NULL,
  `kw_en` text NOT NULL,
  `descr_en` text NOT NULL,
  `caption_ru` varchar(255) NOT NULL,
  `text_ru` text NOT NULL,
  `title_ru` text NOT NULL,
  `kw_ru` text NOT NULL,
  `descr_ru` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп даних таблиці `static_pages`
--

INSERT INTO `static_pages` (`id`, `enabled`, `caption_uk`, `text_uk`, `title_uk`, `kw_uk`, `descr_uk`, `caption_en`, `text_en`, `title_en`, `kw_en`, `descr_en`, `caption_ru`, `text_ru`, `title_ru`, `kw_ru`, `descr_ru`) VALUES
(1, 1, 'Про нас', '<p><img alt="" src="/upload/images/www.jpg" style="float:left;margin-right:20px" />QUANTUM це швидкий та легкий фреймворк для розробки сайтів та веб-застосунків.</p>\r\n\r\n<p>З QUANTUM Ви можете розробити сайт з будь-яким рівнем складності. Це може бути Персональний сайт, E-commerce сайт, або Високонавантажений проект.</p>\r\n', 'Про нас', '', 'Дескрипшон про нас', 'About', '<p><img alt="" src="/upload/images/www.jpg" style="float:left;margin-right:20px" />QUANTUM is a fast and light framework for building websites or web-applications.</p>\r\n\r\n<p>With QUANTUM You can build site with any difficulty level. It may Personal website, E-commerce website or Highload project.</p>\r\n', 'About', '', 'About description', 'О нас', '<p><img alt="" src="/upload/images/www.jpg" style="float:left;margin-right:20px" />QUANTUM это быстрый и лекгий фреймворк для разработки сайтов и веб-приложений.</p>\r\n\r\n<p>С QUANTUM Вы можете разработать сайт с любым уровнем сложности. Это может быть Персональный сайт, E-commerce сайт, или Высоконагруженный проект.</p>\r\n', 'О нас', '', 'Некоторое описание о нас'),
(2, 1, 'Головна', '<p abp="1163">Це домашня сторінка</p>\r\n\r\n<p abp="1164"><b abp="1165">Головна́ сторі́нка, домашня сторінка</b> (англ. <i abp="1166">home page</i>)&nbsp; початкова сторінка веб-сайту, яка надає відомості про тематику веб-сайту та матеріали, які можна побачити на подальших сторінках (дозволяє переглянути зміст веб-сайту). Як правило, посилання робляться саме на головну (домашню) сторінку веб-сайту. Іноді домашня сторінка може бути першою і єдиною сторінкою на сайті.</p>\r\n', 'Ollyxar - Ніколи не зупинятися на досягнутому', '', 'Description of home page українською мовою', 'Welcome', '<p abp="1148">This is home page!</p>\r\n\r\n<p abp="1149">A <b abp="1150">home page</b>, <b abp="1151">index page</b>, or <b abp="1152">main page</b> is a page on a website. A home page usually refers to:</p>\r\n\r\n<ul abp="1153">\r\n	<li abp="1154">The initial or main web page of a website, sometimes called the &quot;front page&quot; (by analogy with newspapers).</li>\r\n	<li abp="1155">The first page that appears upon opening a web browser program, which is also sometimes called the <i abp="1156">start page</i> This &#39;start page&#39; can be a website or it can be a page with various browser functions such as the visual display of websites that are often visited in the web browser.</li>\r\n	<li abp="1157">The web page or local file that automatically loads when a web browser starts or when the browser&#39;s &quot;home&quot; button is pressed; this is also called a &quot;home page&quot;. The user can specify the URL of the page to be loaded, or alternatively choose e.g. to re-load the most recent web page browsed.</li>\r\n	<li abp="1158">A personal web page, for example at a web hosting service or a university web site, that typically is stored in the home directory of the user.</li>\r\n	<li abp="1159">In the 1990s the term was also used to refer to a whole web site, particularly a personal web site.</li>\r\n</ul>\r\n\r\n<p abp="1160">A home page can also be used outside the context of websites, such as to refer to the principal screen of a user interface, which is also referred to as a <b abp="1161">home screen</b> on mobile devices such as cell phones.</p>\r\n', 'Ollyxar - Never rest on laurels', '', '', 'Главная', '<p abp="1168">Это домашняя страница!</p>\r\n\r\n<p abp="1169"><b abp="1170">Стартовая (домашняя) страница</b> (Home&nbsp;Page) страница, загружаемая в окно браузера по умолчанию при каждом его запуске или при нажатии кнопки <i abp="1171">Домой</i> или выделенного сочетания клавиш (<i abp="1172">Alt+Home</i> в Internet Explorer и Mozilla Firefox, <i abp="1173">Ctrl+пробел</i> в Opera).</p>\r\n\r\n<p abp="1174">Обычно в качестве стартовой применяются специализированные страницы, содержащие набор наиболее часто используемых ссылок на каталоги ресурсов, веб-почту, новостные издания и др., а также ряд популярных служб: поиск в интернете, проверка на вирусы, онлайн-перевод, отправка SMS. Эти страницы значительно облегчают жизнь рядовых пользователей интернета. Сушествуют несколько типов стартовых страниц, это или специализированные сайты, которые содержат ссылки на наиболее популярные сайты и сервисы, или стартовые страницы, которые встроены непосредственно в браузер (например: Opera или Chrome)</p>\r\n', 'Ollyxar - Никогда не останавливаться на достигнутом', '', 'Описание на русском'),
(3, 1, 'Контакти', '<p>Сторінка контактів</p>\r\n\r\n<p>&nbsp;</p>\r\n', 'Контакти', '', '', 'Contacts', '<p>Contacts page content</p>\r\n', 'Contacts', '', '', 'Контакты', '<p>Содержание страницы &quot;Контакты&quot;</p>\r\n', 'Контакты', '', ''),
(4, 1, 'Сторінка не знайдена', '<p>Вибачте, такої сторінки не існує</p>\r\n', 'Сторінка не знайдена', '', '', 'Page not found', '<p>Sorry. There is no content.</p>\r\n', 'Page not found', '', '', 'Страница не найдена', '<p>Извините. Такой страницы нет</p>\r\n', 'Страница не найдена', '', '');

-- --------------------------------------------------------

--
-- Структура таблиці `url_alias`
--

CREATE TABLE IF NOT EXISTS `url_alias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `is_directory` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`),
  UNIQUE KEY `query` (`query`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп даних таблиці `url_alias`
--

INSERT INTO `url_alias` (`id`, `query`, `keyword`, `is_directory`) VALUES
(1, 'route=pages&page_id=1', 'about', 0),
(2, 'route=pages&page_id=3', 'contacts', 0),
(3, 'route=photo', 'photo', 0),
(4, 'route=feedback', 'feedback', 0),
(5, 'route=reviews', 'reviews', 0),
(6, 'route=materials&material_id=9', 'news', 1),
(7, 'route=materials&material_id=10', 'business-news', 1),
(8, 'route=materials&material_id=7', 'new-6', 0),
(9, 'route=materials&material_id=13', 'lets-swim', 1),
(10, 'route=404', 'not-found', 0),
(11, 'route=account', 'account', 0);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `country` int(11) NOT NULL,
  `region` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `address` text NOT NULL,
  `user_group` int(11) NOT NULL,
  `joined` int(11) NOT NULL,
  `birth` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL,
  `adm_last_login` int(11) NOT NULL,
  `confirm` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `name`, `description`, `password`, `email`, `photo`, `phone`, `country`, `region`, `city`, `address`, `user_group`, `joined`, `birth`, `enabled`, `last_login`, `adm_last_login`, `confirm`) VALUES
(1, 'admin', 'Site administrator', '2f7b52aacfbf6f44e13d27656ecb1f59', 'user@example.com', '/upload/images/users/me.jpg', '0', 0, 0, 0, '0', 1, 0, -10800, 1, 1411331337, 1411326322, ''),
(2, 'moder', 'Site moderator (password = pas1238)', 'b7db217f31d96690363c3f41191eb2a8', '', '/upload/images/no-image.jpg', '0', 0, 1, 1, '1', 3, 1, -3600, 0, 0, 1384287396, '');

-- --------------------------------------------------------

--
-- Структура таблиці `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп даних таблиці `user_group`
--

INSERT INTO `user_group` (`id`, `description`) VALUES
(1, 'Superuser'),
(2, 'Administrator'),
(3, 'Moderator'),
(4, 'Trusted user'),
(5, 'User'),
(6, 'Guest');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
