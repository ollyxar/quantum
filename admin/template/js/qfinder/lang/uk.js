/**
 * @fileOverview Defines the {@link QFinder.lang} object for the Ukrainian
 *		language.
 */

/**
 * Contains the dictionary of language entries.
 * @namespace
 */
QFinder.lang['uk'] =
{
	appTitle : 'QFinder',

	// Common messages and labels.
	common :
	{
		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, недоступно</span>',
		confirmCancel	: 'Внесені вами зміни буде втрачено. Ви впевнені?',
		ok				: 'OK',
		cancel			: 'Скасувати',
		confirmationTitle	: 'Підтвердження',
		messageTitle	: 'Інформація',
		inputTitle		: 'Питання',
		undo			: 'Скасувати',
		redo			: 'Повторити',
		skip			: 'Пропустити',
		skipAll			: 'Пропустити все',
		makeDecision	: 'Що потрібно зробити?',
		rememberDecision: 'Запам\'ятати мій вибір'
	},


	// Language direction, 'ltr' or 'rtl'.
	dir : 'ltr',
	HelpLang : 'en',
	LangCode : 'uk',

	// Date Format
	//		d    : Day
	//		dd   : Day (padding zero)
	//		m    : Month
	//		mm   : Month (padding zero)
	//		yy   : Year (two digits)
	//		yyyy : Year (four digits)
	//		h    : Hour (12 hour clock)
	//		hh   : Hour (12 hour clock, padding zero)
	//		H    : Hour (24 hour clock)
	//		HH   : Hour (24 hour clock, padding zero)
	//		M    : Minute
	//		MM   : Minute (padding zero)
	//		a    : Firt char of AM/PM
	//		aa   : AM/PM
	DateTime : 'dd.mm.yyyy H:MM',
	DateAmPm : ['AM', 'PM'],

	// Folders
	FoldersTitle	: 'Папки',
	FolderLoading	: 'Завантаження...',
	FolderNew		: 'Будь ласка, введіть нове ім\'я папки: ',
	FolderRename	: 'Будь ласка, введіть нове ім\'я папки: ',
	FolderDelete	: 'Ви впевнені, що хочете видалити папку "%1"?',
	FolderRenaming	: ' (Переіменовую...)',
	FolderDeleting	: ' (Видаляю...)',
	DestinationFolder	: 'Папка призначення',

	// Files
	FileRename		: 'Будь ласка, введіть нове ім\'я файлу: ',
	FileRenameExt	: 'Ви впевненні, що хочете змінити розширення файлу? Файл може стати недоступним.',
	FileRenaming	: 'Переіменовую...',
	FileDelete		: 'Ви впевнені, що хочете видалити файл "%1"?',
	FilesDelete	    : 'Ви впевнені, що хочете видалити %1 файли?',
	FilesLoading	: 'Завантаження...',
	FilesEmpty		: 'порожня папка',
	DestinationFile	: 'Файл призначення',
	SkippedFiles	: 'Список пропускаємих файлів:',

	// Basket
	BasketFolder		: 'Кошик',
	BasketClear			: 'Вичистити кошик',
	BasketRemove		: 'Прибрати з кошика',
	BasketOpenFolder	: 'Перейти до папки цього файлу',
	BasketTruncateConfirm : 'Дійсно вичистити кошик?',
	BasketRemoveConfirm	: 'Ви дійсно хочете прибрати файл "%1" з кошика?',
	BasketRemoveConfirmMultiple	: 'Ви дійсно хочете видалити %1 файли з кошика?',
	BasketEmpty			: 'В кошику поки що немає файлів, додайте нові за допомогою драг-н-дропа (перетягніть файл до кошика).',
	BasketCopyFilesHere	: 'Скопіювати файл з кошика',
	BasketMoveFilesHere	: 'Перемістити файл з кошика',

	// Global messages
	OperationCompletedSuccess	: 'Операцію завершено успішно.',
	OperationCompletedErrors	: 'Операцію завершено з помилками',
	FileError				    : '%s: %e',

	// Move and Copy files
	MovedFilesNumber	: 'Число переміщених файлів: %s.',
	CopiedFilesNumber	: 'Чисто скопійованих файлів: %s.',
	MoveFailedList		: 'Наступні файли не можуть бути переміщені:<br />%s',
	CopyFailedList		: 'Наступні файли не можуть бути скопійовані:<br />%s',

	// Toolbar Buttons (some used elsewhere)
	Upload		: 'Завантажити файл',
	UploadTip	: 'Завантажити новый файл',
	Refresh		: 'Оновити список',
	Settings	: 'Налаштування',
	Help		: 'Допомога',
	HelpTip		: 'Допомога',

	// Context Menus
	Select			: 'Вибрати',
	SelectThumbnail : 'Вибрати мініатюру',
	View			: 'Переглянути',
	Download		: 'Зберегти',

	NewSubFolder	: 'Нова папка',
	Rename			: 'Переіменувати',
	Delete			: 'Видалити',
	DeleteFiles		: 'Видалити файли',

	CopyDragDrop	: 'Копіювати',
	MoveDragDrop	: 'Перемістити',

	// Dialogs
	RenameDlgTitle		: 'Переіменувати',
	NewNameDlgTitle		: 'Нове ім\'я',
	FileExistsDlgTitle	: 'Файл вже існує',
	SysErrorDlgTitle    : 'Системна помилка',

	FileOverwrite	: 'Замінити файл',
	FileAutorename	: 'Автоматично переіменовувати',
	ManuallyRename	: 'Переіменування вручну',

	// Generic
	OkBtn		: 'ОК',
	CancelBtn	: 'Скасувати',
	CloseBtn	: 'Закрити',

	// Upload Panel
	UploadTitle			: 'Завантажити новий файл',
	UploadSelectLbl		: 'Обрати файл для завантаження',
	UploadProgressLbl	: '(Завантаження в процесі, будь ласка, зачекайте...)',
	UploadBtn			: 'Завантажити обраний файл',
	UploadBtnCancel		: 'Скасувати',

	UploadNoFileMsg		: 'Будь ласка, оберіть файл на вашому комп\'ютері.',
	UploadNoFolder		: 'Будь ласка, оберіть папку, до якої потрібно завантажити файл.',
	UploadNoPerms		: 'Завантаження файлів заборонено.',
	UploadUnknError		: 'Помилка при передачі файлу.',
	UploadExtIncorrect	: 'В цю папку неможна завантажувати файли з таким розширенням.',

	// Flash Uploads
	UploadLabel			: 'Файли для завантаження',
	UploadTotalFiles	: 'Всього файлів:',
	UploadTotalSize		: 'Загальний розмір:',
	UploadSend			: 'Завантажити файл',
	UploadAddFiles		: 'Додати файли',
	UploadClearFiles	: 'Очистити',
	UploadCancel		: 'Скасувати завантаження',
	UploadRemove		: 'Прибрати',
	UploadRemoveTip		: 'Прибрати !f',
	UploadUploaded		: 'Завантажено !n%',
	UploadProcessing	: 'Завантажую...',

	// Settings Panel
	SetTitle		: 'Налаштування',
	SetView			: 'Зовнішній вигляд:',
	SetViewThumb	: 'Мініатюри',
	SetViewList		: 'Список',
	SetDisplay		: 'Показувати:',
	SetDisplayName	: 'Ім\'я файлу',
	SetDisplayDate	: 'Дата',
	SetDisplaySize	: 'Розмір файлу',
	SetSort			: 'Сортувати:',
	SetSortName		: 'за іменем файлу',
	SetSortDate		: 'за датою',
	SetSortSize		: 'за розміром',
	SetSortExtension: 'за розширенням',

	// Status Bar
	FilesCountEmpty : '<Порожня папка>',
	FilesCountOne	: '1 файл',
	FilesCountMany	: '%1 файлів',

	// Size and Speed
	Kb				: '%1 KБ',
	Mb				: '%1 MB',
	Gb				: '%1 GB',
	SizePerSecond	: '%1/s',

	// Connector Error Messages.
	ErrorUnknown	: 'Неможливо завершити запрос. (Ошибка %1)',
	Errors :
	{
	 10 : 'Невірна команда.',
	 11 : 'Тип ресурсу не вказан у запросі.',
	 12 : 'Невірний запитаний тип ресурсу.',
	102 : 'Невірне ім\'я файлу або папки.',
	103 : 'Неможливо завершити запрос через обмеження авторизації.',
	104 : 'Неможливо завершити запрос через обмеження дозволів файлової системи.',
	105 : 'Невірне розширення файлу.',
	109 : 'Невірний запрос.',
	110 : 'Невідома помилка.',
	111 : 'Неможливо завершити запрос через розмір файлу.',
	115 : 'Файл або папка з таким ім\'ям вже існує.',
	116 : 'Папка не знайдена. Будь ласка, оновіть вигляд папок і спробуйте ще раз.',
	117 : 'Файл не знайдено. Будь ласка, оновіть список файлів і спробуйте ще раз.',
	118 : 'Вихідне розташування файлу співпадає зі вказаним.',
	201 : 'Файл з таким іменем вже існує. Завантажений файл було переіменовано в "%1".',
	202 : 'Невірний файл.',
	203 : 'Невірний файл. Розмір файлу занадто великий.',
	204 : 'Завантажений файл пошкоджено.',
	205 : 'Недоступна тимчасова папка для завантаження файлів на сервер.',
	206 : 'Завантаження скасовано через міркування безпеки. Файл містить схожі на HTML дані.',
	207 : 'Завантажений файл було переіменовано в "%1".',
	300 : 'Виникла помилка при переміщенні файлу(ів).',
	301 : 'Виникла помилка при копіюванні файлу(ів).',
	500 : 'Браузер файлів відключено через міркування безпеки. Будь ласка, сообщіть вашому системному адміністратру і перевірте конфігураційний файл QFinder.',
	501 : 'Підтримка мініатюр вимкнена.'
	},

	// Other Error Messages.
	ErrorMsg :
	{
		FileEmpty		: 'Ім\'я файлу не може бути порожнім.',
		FileExists		: 'Файл %s вже існує.',
		FolderEmpty		: 'Ім\'я папки не може бути порожнім.',
		FolderExists	: 'Папка %s вже існує.',
		FolderNameExists: 'Папка вже існує.',

		FileInvChar		: 'Ім\'я файлу не може містити будь-який з цих символів: \n\\ / : * ? " < > |',
		FolderInvChar	: 'Ім\'я папки не може містити будь-який з цих символов: \n\\ / : * ? " < > |',

		PopupBlockView	: 'Неможливо відкрити файл у новому вікні. Будь ласка, перевірте налаштування браузера і вимкніть блокування виринаючих вікон для цього сайту.',
		XmlError		: 'Помилка при разборі XML-відповіді сервера.',
		XmlEmpty		: 'Неможливо прочитати XML-відповідь сервера, отримано порожню строку.',
		XmlRawResponse	: 'Необроблена відповідь сервера: %s'
	},

	// Imageresize plugin
	Imageresize :
	{
		dialogTitle		: 'Змінити розміри %s',
		sizeTooBig		: 'Неможна вказувати розміри більш, ніж у оригінального файлу (%size).',
		resizeSuccess	: 'Розміри успішно змінені.',
		thumbnailNew	: 'Створити мініатюру(и)',
		thumbnailSmall	: 'Маленька (%s)',
		thumbnailMedium	: 'Середня (%s)',
		thumbnailLarge	: 'Велика (%s)',
		newSize			: 'Встановити нові розміри',
		width			: 'Довжина',
		height			: 'Висота',
		invalidHeight	: 'Висота повинна бути числом більше нуля.',
		invalidWidth	: 'Довжина повинна бути числом більше нуля.',
		invalidName		: 'Невірне ім\'я файлу.',
		newImage		: 'Зберегти як новий файл',
		noExtensionChange : 'Не вдалося змінити розширення файлу.',
		imageSmall		: 'Вихідне зображення занадто маленьке.',
		contextMenuName	: 'Змінити розмір',
		lockRatio		: 'Зберігати пропорції',
		resetSize		: 'Повернути звичайні розміри'
	},

	// Fileeditor plugin
	Fileeditor :
	{
		save			: 'Зберегти',
		fileOpenError	: 'Не вдалося відкрити файл.',
		fileSaveSuccess	: 'Файл успешно збережено.',
		contextMenuName	: 'Редагувати',
		loadingFile		: 'Файл завантажується, будь ласка, зачекайте...'
	},

	Maximize :
	{
		maximize : 'Розгорнути',
		minimize : 'Згорнути'
	},

	Gallery :
	{
		current : 'Зображення {current} з {total}'
	},

	Zip :
	{
		extractHereLabel	: 'Розпакувати сюди',
		extractToLabel		: 'Розпакувати до...',
		downloadZipLabel	: 'Скачати як zip',
		compressZipLabel	: 'Стиснути до zip',
		removeAndExtract	: 'Видалити розпаковаті та розпакувати',
		extractAndOverwrite	: 'Розпакувати з заміною',
		extractSuccess		: 'Файл успіно розпаковано.'
	},

	Search :
	{
		searchPlaceholder : 'Пошук'
	}
};
