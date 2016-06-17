<?php
    setlocale(LC_ALL, 'ru_RU.UTF-8');
    ini_set('memory_limit', '-1');
    //ini_set('max_execution_time', '3000');
    /*
[\b\S|\b]{2,}
(\(?сноск(а|и)\s*\d*\)?.*)+
[^СНОСКИ.*(?=ЧАСТЬ)]
[\b\S|\b]{2,}(?!СНОСКИ.*(?=ЧАСТЬ))


            /*foreach($result['word'] as $k => $r)
                if( $r == $el ){
                    
            /*foreach($result['word'] as $k => $r)
                if( $r == $el ){
                    $result['count'][$k]++;
                    $isThere = false;
                    break;
                }
            if( $isThere ){*/
            /*}
                    $isThere = false;
                    break;
                }
            if( $isThere ){
            /*}*/

    $f = iconv('Windows-1251', 'UTF-8', file_get_contents('http://seopult.ru/uploads/File/war_and_peace.txt'));
    /*$f = 'Тестовое задание:

1. Сделать скрипт для обработки текста "Войны и мира", который бы на выходе выдавал две таблицы:

- ТОП20 самых популярных букв русского афавита (без учета сносок в тексте) в виде массива "буква" => "кол-во упоминаний";
- ТОП20 самых популярных слов в книге (без учета сносок в тексте), язык значения не имеет. Словом считать любую последовательность цифр,
  букв и символов длиннее 2 знаков и отделенных пробелами. На выходе должен получится массив вида "слово" => "количество упоминаний"

Текст "Войны и мира" брать тут: http://seopult.ru/uploads/File/war_and_peace.txt

2. Рисование текстовых таблиц в консоли/браузере для двумерных массивов:

- сделать функцию, которая на вход принимает двумерный массив и выводит его в консоли или в браузере в виде ASCII-таблицы. Ключи массива - названия столбцов, значения - ячейки
Пример:

+-------+-------+
| Key1  | Key2  |
+-------+-------+
| Val1  | Val3  |
+-------+-------+
| Val2  | Val4  |
+-------+-------+

P.S. Очень важно учитывать краевые случаи, код желательно писать "боевой", т.е. с нормальной струкрутой, названиями переменных, проверками различных условий и т.п.';*/
    
    $result = array();
    
    if( $f ){
        //$result['top20RuChar'] = ResultArray(AnalysisTop20CyrillicChar($f));
        //$result['top20Word'] = ResultArray(AnalysisTop20Word($f));
        $result['top20Word'] = ResultArray(Analysis($f));
        $result['status'] = true;
    } else {
        $result['res'] = 'Read Error';
        $result['status'] = false;
    }
    
    echo json_encode($result);
    die();
    
    function Analysis($e) {
        $result = array(
            'word' => array(),
            'count' => array()
        );
        preg_match_all('/\b\S+\b/u', $e, $arr);
        natcasesort($arr[0]);
        $i=0;
        array_push($result['word'], $arr[0][0]);
        array_push($result['count'], 1);
        foreach(array_slice($arr[0], 1) as $k => $e) {
            $e = mb_strtolower($e);
            echo $k;
            if( $e != $arr[0][$k-1] ){
                array_push($result['word'], $e);
                array_push($result['count'], 1);
                $i++;
            } else {
                $result['count'][$i]++;
            }
        }
        array_multisort($result['count'], SORT_NUMERIC, SORT_DESC, $result['word']);
        return array(
            'word' => array_slice($result['word'], 0, 20),
            'count' => array_slice($result['count'], 0, 20)
        );
    }
    function AnalysisTop20Word($e) {
        $result = array(
            'word' => array(),
            'count' => array()
        );
        preg_match_all('/[\S]+[\d]{0,0}/u', $e, $arr);
        foreach($arr[0] as $el) {
            $isThere = true;
            $el = mb_strtolower($el);
            foreach($result['word'] as $k => $r)
                if( $r == $el ){
                    $result['count'][$k]++;
                    $isThere = false;
                    break;
                }
            if( $isThere ){
                array_push($result['word'], $el);
                array_push($result['count'], 1);
            }
        }
        array_multisort($result['count'], SORT_NUMERIC, SORT_DESC, $result['word']);
        return array(
            'word' => array_slice($result['word'], 0, 20),
            'count' => array_slice($result['count'], 0, 20)
        );
    }
    function AnalysisTop20CyrillicChar($e) {
        $result = array(
            'symbol' => array(),
            'count' => array()
        );
        preg_match_all('/[а-яА-Я]/u', $e, $arr);
        foreach($arr[0] as $el) {
            $isThere = true;
            $el = mb_strtolower($el);
            foreach($result['symbol'] as $k => $r)
                if( $r == $el ){
                    $result['count'][$k]++;
                    $isThere = false;
                    break;
                }
            if( $isThere ){
                array_push($result['symbol'], $el);
                array_push($result['count'], 1);
            }
        }
        array_multisort($result['count'], SORT_NUMERIC, SORT_DESC, $result['symbol']);
        return array(
            'symbol' => array_slice($result['symbol'], 0, 20),
            'count' => array_slice($result['count'], 0, 20)
        );
    }
    function ResultArray($e) {
        $result = '';
        $border = '';
        $widthCols = array();
        foreach($e as $k => $c){
            $widthCols[$k] = mb_strlen($k);
            foreach($c as $el){
                $currentWidthCols = mb_strlen($el);
                if( $currentWidthCols > $widthCols[$k] )
                    $widthCols[$k] = $currentWidthCols;
            }
        }
        
        foreach($e as $k => $c){
            $border .= '+';
            for($i=0; $i<$widthCols[$k]+2; $i++)
                $border .= '-';
        }
        $border .= '+'."\n";
        $result .= $border;
        foreach($e as $k => $c){
            $result .= '| '.$k;
            for($i=0; $i < $widthCols[$k]-mb_strlen($k)+1; $i++)
                $result .= ' ';
        }
        $result .= '|'."\n".$border;
        for( $i = 0; $i < count($e, COUNT_RECURSIVE) / count($e)-1; $i++ ){
            foreach($e as $k => $el){
                $result .= '| '.$el[$i];
                for( $j = 0; $j < $widthCols[$k] - mb_strlen($el[$i])+1; $j++ )
                    $result .= ' ';
            }
            $result .= '|'."\n".$border;
        }
        return $result;
    }
?>