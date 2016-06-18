**Время, потраченное на выполнение ТЗ: 24 часа**

Содержание:

- чтение файла

- функция по подготовке (исключение сносок)

- общая функция анализа текста

- функция вывода в виде текстовой таблицы.


Работа состоит из файлов:

- index.html - клиентская часть запуска кода и вывода результата

- script.js - клиентская часть, функциональность, выводящая текстовую таблицу-результат

- function.php - основная функцональность, серверная часть


Тестовое задание:

1. Сделать скрипт для обработки текста "Войны и мира", который бы на выходе выдавал две таблицы:

- ТОП20 самых популярных букв русского афавита (без учета сносок в тексте) в виде массива "буква" => "кол-во упоминаний";
- ТОП20 самых популярных слов в книге (без учета сносок в тексте), язык значения не имеет. Словом считать любую последовательность цифр,
  букв и символов длиннее 2 знаков и отделенных пробелами. На выходе должен получится массив вида "слово" => "количество упоминаний"

Текст "Войны и мира" брать тут: http://seopult.ru/uploads/File/war_and_peace.txt

2. Рисование текстовых таблиц в консоли/браузере для двумерных массивов:

- сделать функцию, которая на вход принимает двумерный массив и выводит его в консоли или в браузере в виде ASCII-таблицы. Ключи массива - названия столбцов, значения - ячейки
Пример:

```
#!html
+-------+-------+
| Key1  | Key2  |
+-------+-------+
| Val1  | Val3  |
+-------+-------+
| Val2  | Val4  |
+-------+-------+
```

P.S. Очень важно учитывать краевые случаи, код желательно писать "боевой", т.е. с нормальной струкрутой, названиями переменных, проверками различных условий и т.п.