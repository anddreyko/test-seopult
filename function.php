<?php
    setlocale(LC_ALL, 'ru_RU.UTF-8');
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '120');
    $f = iconv('Windows-1251', 'UTF-8', file_get_contents('http://seopult.ru/uploads/File/war_and_peace.txt'));
    $result = array();
    
    if( $f ){
        $f = Preparing($f);
        $result['top20RuChar'] = ResultArray(Analysis(
            $f,
            //only 2 sub-array array for result
            array(
                'symbol' => array(),
                'count' => array()
            ),
            //regular expression for find words
            '/[а-яА-Я]/u'
        ));
        $result['timeExec20RuChar'] = $timeExec;
        $result['top20Word'] = ResultArray(Analysis(
            $f,
            //only 2 sub-array for result
            array(
                'word' => array(),
                'count' => array()
            ),
            //regular expression for find words
            '/\b\S+\b/u'
        ));
        $result['timeExec20Word'] = $timeExec;
        $result['status'] = true;
    } else {
        $result['res'] = 'Read Error';
        $result['status'] = false;
    }
    
    echo json_encode($result);
    die();
    
    function Preparing($e){
        //remove unnecessary spaces
        $e = trim(preg_replace('/[\s]+/is', ' ', $e));
        //remove footnote and mark first footnote
        $e = preg_replace('/ 1 \(См. сноски в конце части\)|СНОСКИ.*?(?=ЧАСТЬ)/u', '', $e);
        $e = preg_replace('/СНОСКИ.*/u', '', $e);
        //remove mark footnote to phrases in a foreign language
        $e = preg_replace('/\W*(?<=[a-zA-Z])\W*\d+/u', '', $e);
        return $e;
    }
    function Analysis($e, $result, $regexp) {
        //for output time execution query
        global $timeExec;
        $timeExec = mktime();
        //$i for calculate to rate
        $i=0;
        //get names of keys result array
        $keysname = array_keys($result);
        //run find regular expression for condition of the problem
        preg_match_all($regexp, $e, $arr);
        //sorting by ABC for optimized search
        natcasesort($arr[0]);
        //set first element for result
        array_push($result[$keysname[0]], array_shift($arr[0]));
        array_push($result[$keysname[1]], 1);
        //calculate count for result
        $arrLength = count($arr[0]);
        foreach($arr[0] as $k => $e) {
            $e = mb_strtolower($e);
            if( $e != $result[$keysname[0]][$i] ){
                array_push($result[$keysname[0]], $e);
                array_push($result[$keysname[1]], 1);
                $i++;
            } else $result[$keysname[1]][$i]++;
            //for early completion of the cycle when the remainder is too small
            if(
                isset($result[$keysname[1]][20])
             && ($arrLength-$k) < $result[$keysname[1]][20]  
            ) break;
        }
        array_multisort($result[$keysname[1]], SORT_NUMERIC, SORT_DESC, $result[$keysname[0]]);
        $timeExec = mktime() - $timeExec;
        return array(
            $keysname[0] => array_slice($result[$keysname[0]], 0, 20),
            $keysname[1] => array_slice($result[$keysname[1]], 0, 20)
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