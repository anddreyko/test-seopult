<?php
    mb_internal_encoding('UTF-8');
    setlocale(LC_ALL, 'ru_RU.UTF-8');
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '120');
    $file = iconv('Windows-1251', 'UTF-8', file_get_contents('http://seopult.ru/uploads/File/war_and_peace.txt'));
    $result = array();
    
    if( $file ){
        $timeExecTotal = time();
        $file = Preparing($file);
        $timeExecQuery = time();
        $result['top20RuChar'] = ResultArray(Analysis(
            $file,
            '/[а-я]/ui',
            20
        ), array('symbol', 'count'));
        $result['timeExecQuery20RuChar'] = time() - $timeExecQuery;
        $result['timeExecAnalysis20RuChar'] = $timeExecAnalysis;
        $result['timeExecBuildTable20RuChar'] = $timeExecBuildTable;
        $timeExecQuery = time();
        $result['top20Word'] = ResultArray(Analysis(
            $file,
            '/\b\S{2,}\b/u',
            20
        ), array('word', 'count'));
        $result['timeExecQuery20Word'] = time() - $timeExecQuery;
        $result['timeExecAnalysis20Word'] = $timeExecAnalysis;
        $result['timeExecBuildTable20Word'] = $timeExecBuildTable;
        $result['timeExecTotal'] = time() - $timeExecTotal;
        $result['status'] = true;
    } else {
        $result['res'] = 'Read Error';
        $result['status'] = false;
    }
    
    echo json_encode($result);
    die();
    
    function Preparing($e = ''){
        //remove unnecessary spaces
        $e = trim(preg_replace('/[\s]+/is', ' ', $e));
        //remove footnote and mark first footnote
        $e = preg_replace('/ 1 \(См. сноски в конце части\)|СНОСКИ.*?(?=ЧАСТЬ)/u', '', $e);
        $e = preg_replace('/СНОСКИ.*/u', '', $e);
        //remove mark footnote to phrases in foreign language
        $e = preg_replace('/\W*(?<=[a-zA-Z])\W*\d+/u', '', $e);
        return $e;
    }
    
    /*
     *  $e - input string
     *  $reqexp - string for regular expression for find
     *  $top - count of seats, integer
     *  this return first $top elements in array from results,
     *  where key is items found, value is number of matches
     */
    function Analysis($e = '', $regexp = '//', $top = 20) {
        //for output time execution query
        global $timeExecAnalysis;
        $timeExecAnalysis = time();
        //run find regular expression for condition of the problem
        preg_match_all($regexp, $e, $arr);
        //convert all elements array to lower case for caseless to processing
        //calculate count for result
        $result = array_count_values(array_map('mb_strtolower', $arr[0]));
        //sortung anf return top
        arsort($result);
        $timeExecAnalysis = time() - $timeExecAnalysis;
        return array_slice($result, 0, $top);
    }
    
    //$e - input array, $cols is name for header table result
    function ResultArray($e = array(), $cols = array()) {
        global $timeExecBuildTable;
        $timeExecBuildTable = time();
        $result = '';
        $border = '';
        $widthCols = array(mb_strlen($cols[0]), mb_strlen($cols[1]));
        
        //calculate width cols
        foreach($e as $k => $c){
            $currentWidthCols0 = mb_strlen($k);
            if($currentWidthCols0 > $widthCols[0])
                $widthCols[0] = $currentWidthCols0;
            $currentWidthCols1 = mb_strlen($c);
            if($currentWidthCols1 > $widthCols[1])
                $widthCols[1] = $currentWidthCols1;
        }
        
        //build horizontal border
        foreach($widthCols as $w){
            $border .= '+';
            for($i = 0; $i < $w+2; $i++)
                $border .= '-';
        }
        $border .= '+'."\n";
        $result .= $border;
        
        //build tables header
        foreach($cols as $k => $c){
            $result .= '| '.$c;
            for($i=0; $i < $widthCols[$k]-mb_strlen($c)+1; $i++)
                $result .= ' ';
        }
        $result .= '|'."\n".$border;
        
        //build tables body
        foreach($e as $k => $c){
            $result .= '| '.$k;
            for($i=0; $i < $widthCols[0]-mb_strlen($k)+1; $i++)
                $result .= ' ';
            $result .= '| '.$c;
            for($i=0; $i < $widthCols[1]-mb_strlen($c)+1; $i++)
                $result .= ' ';
            $result .= '|'."\n".$border;
        }
        
        $timeExecBuildTable = time() - $timeExecBuildTable;
        return $result;
    }
?>