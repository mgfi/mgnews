========================
PL – POLSKI

INTERNACJONALIZACJA (i18n) – NAZEWNICTWO TŁUMACZEŃ

Projekt wykorzystuje system tłumaczeń Laravel oparty o katalogi lang/pl oraz lang/en.
Każdy widok i komponent posiada własny, dedykowany plik tłumaczeń.

STRUKTURA KATALOGÓW

lang/pl
lang/en

Każdy plik istnieje zawsze w obu językach i ma identyczną strukturę kluczy.

ZASADY NAZEWNICTWA PLIKÓW

Nazwy plików tłumaczeń są skrótami technicznymi i bezpośrednio odpowiadają widokom lub komponentom.

Format nazwy pliku:
typ + obszar + moduł + widok

PRZYKŁADY PREFIKSÓW:
liv – komponent Livewire
adm – panel administracyjny
lay – layout
auth – autoryzacja
mail – e-maile
dash – dashboard
home – strona główna

PRZYKŁADY MAPOWANIA:

Widok:
resources/views/livewire/admin/newsletter-index.blade.php

Plik tłumaczeń:
lang/pl/livAdmNewInd.php
lang/en/livAdmNewInd.php

STRUKTURA PLIKÓW TŁUMACZEŃ

Pliki tłumaczeń zawierają logicznie pogrupowane klucze, np.:

title

table.*

status.*

actions.*

empty

UŻYCIE W WIDOKACH

W widokach Blade i Livewire nie wolno używać tekstów na sztywno.

Poprawnie:
__('livAdmNewInd.title')
__('livAdmNewInd.table.subject')

Niepoprawnie:
"Newsletters"
"Edit"

ZASADY OBOWIĄZKOWE

• Brak tekstów hardcoded w widokach
• Każdy nowy widok = nowy plik tłumaczeń
• Identyczna struktura kluczy w PL i EN
• Brak wspólnych, ogólnych plików typu admin.php
• Tłumaczenia są powiązane z widokiem, nie z logiką biznesową

========================
EN – ENGLISH (US)

INTERNATIONALIZATION (i18n) – TRANSLATION NAMING

The project uses Laravel translations based on lang/en and lang/pl directories.
Each view or component has its own dedicated translation file.

DIRECTORY STRUCTURE

lang/en
lang/pl

Each file always exists in both languages and shares the same key structure.

FILE NAMING RULES

Translation file names are technical abbreviations directly mapped to views or components.

File name format:
type + area + module + view

PREFIX EXAMPLES:
liv – Livewire component
adm – admin panel
lay – layout
auth – authentication
mail – emails
dash – dashboard
home – home page

MAPPING EXAMPLES:

View:
resources/views/livewire/admin/newsletter-index.blade.php

Translation files:
lang/en/livAdmNewInd.php
lang/pl/livAdmNewInd.php

TRANSLATION FILE STRUCTURE

Translation files contain logically grouped keys such as:

title

table.*

status.*

actions.*

empty

USAGE IN VIEWS

Hardcoded text is not allowed in Blade or Livewire views.

Correct:
__('livAdmNewInd.title')
__('livAdmNewInd.table.subject')

Incorrect:
"Newsletters"
"Edit"

MANDATORY RULES

• No hardcoded text in views
• Each new view requires a new translation file
• Identical key structure in all languages
• No shared global files like admin.php
• Translations are tied to views, not business logic

========================
EN – ENGLISH (GB)

INTERNATIONALISATION (i18n) – TRANSLATION NAMING

The project uses Laravel translations organised in lang/en and lang/pl directories.
Each view or component has its own dedicated translation file.

DIRECTORY STRUCTURE

lang/en
lang/pl

Each file always exists in both languages and shares the same key structure.

FILE NAMING RULES

Translation file names are technical abbreviations directly mapped to views or components.

File name format:
type + area + module + view

PREFIX EXAMPLES:
liv – Livewire component
adm – admin panel
lay – layout
auth – authentication
mail – emails
dash – dashboard
home – home page

MAPPING EXAMPLES:

View:
resources/views/livewire/admin/newsletter-index.blade.php

Translation files:
lang/en/livAdmNewInd.php
lang/pl/livAdmNewInd.php

TRANSLATION FILE STRUCTURE

Translation files contain logically grouped keys such as:

title

table.*

status.*

actions.*

empty

USAGE IN VIEWS

Hard-coded text must not be used in Blade or Livewire views.

Correct:
__('livAdmNewInd.title')
__('livAdmNewInd.table.subject')

Incorrect:
"Newsletters"
"Edit"

MANDATORY RULES

• No hard-coded text in views
• Each new view requires a new translation file
• Identical key structure across languages
• No shared global files such as admin.php
• Translations are bound to views, not business logic