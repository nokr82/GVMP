<?php
/**
 * 
 * ArraySearch
 * 
 * Created for to do search inside of array.
 * if $all is 1, all results will return by array 
 * 
 * Example: ArraySearch( array $dizi, string "key1 = 'bunaesitolmali1' and key2 >= 'bundanbuyukolmali' or key3 != 'bunaesitolmasin3'", int $all = 0 );
 * [ㄱ-ㅎㅏ-ㅣ가-힣]+
 */
function ArraySearch($SearchArray, $query, $all = 0, $Return = 'direct')
    {
//        $SearchArray = json_decode(json_encode($SearchArray), true);
        $ResultArray = array();
		$DATA = array();
        if (is_array($SearchArray)) {
			$desen = "@[\s*]?[\'{1}]?([ㄱ-ㅎㅏ-ㅣ가-힣a-zA-Z\ç\Ç\ö\Ö\ş\Ş\ı\İ\ğ\Ğ\ü\Ü[:space:]0-9]*)[\'{1}]?[\s*]?(\<\=|\>\=|\=|\!\=|\<|\>)\s*\'([ㄱ-ㅎㅏ-ㅣ가-힣a-zA-Z\ç\Ç\ö\Ö\ş\Ş\ı\İ\ğ\Ğ\ü\Ü[:space:]0-9:]*)\'[\s*]?(and|or|\&\&|\|\|)?@si";
            $DonenSonuc = preg_match_all($desen, $query, $Result);
            if ($DonenSonuc) {
				$mk = 0;
                foreach ($SearchArray as $i => $ArrayElement) {
                    if (is_array($ArrayElement)) {
                        $SearchStatus = 0;
                        $EvalString = "";
                        for ($r = 0; $r < count($Result[1]); $r++) {
                            if ($Result[2][$r] == '=') {
                                $Operator = "==";
                            } elseif ($Result[2][$r] == '!=') {
                                $Operator = "!=";
                            } elseif ($Result[2][$r] == '>=') {
                                $Operator = ">=";
                            } elseif ($Result[2][$r] == '<=') {
                                $Operator = "<=";
                            } elseif ($Result[2][$r] == '>') {
                                $Operator = ">";
                            } elseif ($Result[2][$r] == '<') {
                                $Operator = "<";
                            } else {
                                $Operator = "==";
                            }
                            $AndOperator = "";
                            if ($r != count($Result[1]) - 1) {
                                $AndOperator = $Result[4][$r] ?: 'and';
                            }
							
							if (isset($ArrayElement[$Result[1][$r]])&&$ArrayElement[$Result[1][$r]]!=""){
	                            $EvalString .= '("' . $ArrayElement[$Result[1][$r]] . '"' . $Operator . '"' . $Result[3][$r] . '") ' . $AndOperator . ' ';
							}else{
	                            $EvalString .= '("NO"' . $Operator . '"' . $Result[3][$r] . '") ' . $AndOperator . ' ';
							}
                        }
						eval('if( ' . $EvalString . ' ) $SearchStatus = 1;');

                        if ($SearchStatus === 1) {
                            if ($all === 1) {
                                if ($Return == 'direct') {
                                    $ResultArray[$i] = (isset($ResultArray[$i])&&is_array($ResultArray[$i])) ? $ResultArray[$i] : [];
                                    $ResultArray[$i] = array_merge($ResultArray[$i], $ArrayElement);
                                } else if ($Return == 'array') {
                                    $ResultArray['index'][] = $i;
                                    $ResultArray['array'] = is_array($ResultArray['array']) ? $ResultArray['array'] : [];
                                    $ResultArray['array'] = array_merge($ResultArray['array'], $ArrayElement);
                                }
                            } else {
                                if ($Return == 'direct') {
                                    $ResultArray = $i;
                                } else if ($Return == 'array') {
                                    $ResultArray['index'] = $i;
                                }
                                return $ResultArray;
                            }
                        }

                        if ($all === 1 && is_array($ArrayElement)) {
                            if ($Return == 'direct') {
                                $args = func_get_args();
                                $ChildResult = ArraySearch($ArrayElement, $args[1], $args[2], $args[3]);
                                if (count($ChildResult) > 0) {
									$ResultArray[$i] = (isset($ResultArray[$i])&&is_array($ResultArray[$i])) ? $ResultArray[$i] :[];
									$ResultArray[$i] = array_merge($ResultArray[$i], $ChildResult);
                                }
                            }
                        }
                    }
                }
                if ($all === 1) {
                    return $ResultArray;
                }
            }
        }
        return false;
    }
?>