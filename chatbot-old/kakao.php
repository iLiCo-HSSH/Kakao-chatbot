<?php
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set("Asia/Seoul");
$function = $_GET['f'];

include('simple_html_dom.php');

function bus() {
  $up = upbus();
  $down = downbus();
  echo '{"version":"2.0","template":{"outputs": [{"carousel": {"type": "basicCard","items": [{"title": "독립문 공원역 7737 버스 정보","description": "'. $up .'"},{"title": "독립문 파크빌역 7737 버스 정보","description": "'. $down .'"}]}}]}}';
}

function downbus() {
    $url = 'http://ws.bus.go.kr/api/rest/arrive/getArrInfoByRoute';
    $key = 'HR15nrUmf2vUG%2FMNZD1cvS7KdyoGo8JxEASPTqr5iYC9dEJX9A%2BgCIWJBgoESxu1QuvFrzvq%2Bjs%2FbHDI8BF7KQ%3D%3D';
    $stID = '112000428';  # 정류소 ID
    $busRouteID = '100100363';  # 노선 ID
    $stID_ord = '32';  # 정류소 순번

    $data_url = $url.'?'.'serviceKey='.$key.'&stId='.$stID.'&busRouteId='.$busRouteID.'&ord='.$stID_ord;
    $bus_data = file_get_contents($data_url);
    $xml = simplexml_load_string($bus_data);
    $dataLink = $xml->msgBody->itemList;

    $arrmsg1 = $dataLink->arrmsg1;
    $arrmsg2 = $dataLink->arrmsg2;
    $reride_Num1 = $dataLink->reride_Num1;
    $reride_Num2 = $dataLink->reride_Num2;
    $full1 = $dataLink->full1;
    $full2 = $dataLink->full2;
    $full1 = ($full1==0)?'':'(만차)';
    $full2 = ($full2==0)?'':'(만차)';

    $message = '이번 버스: ' . $arrmsg1.' ('.$reride_Num1.'명)'.$full1.'\n다음 버스: ' . $arrmsg2.' ('.$reride_Num2.'명)'.$full2;
    return $message;
};

function upbus() {
    $url = 'http://ws.bus.go.kr/api/rest/arrive/getArrInfoByRoute';
    $key = 'HR15nrUmf2vUG%2FMNZD1cvS7KdyoGo8JxEASPTqr5iYC9dEJX9A%2BgCIWJBgoESxu1QuvFrzvq%2Bjs%2FbHDI8BF7KQ%3D%3D';
    $stID = '112000313';  # 정류소 ID
    $busRouteID = '100100363';  # 노선 ID
    $stID_ord = '30';  # 정류소 순번

    $dataUrl = $url.'?'.'serviceKey='.$key.'&stId='.$stID.'&busRouteId='.$busRouteID.'&ord='.$stID_ord;
    $busData = file_get_contents($dataUrl);
    $xml = simplexml_load_string($busData);
    $dataLink = $xml->msgBody->itemList;//xml 데이터 경로

    $arrmsg1 = $dataLink->arrmsg1;//첫번째 버스 도착 시간
    $arrmsg2 = $dataLink->arrmsg2;//두번쨰 버스 도착 시간
    $rerideNum1 = $dataLink->reride_Num1;//첫번째 버스 승차 인원
    $rerideNum2 = $dataLink->reride_Num2;//두번째 버스 승차 인원
    $full1 = $dataLink->full1;//만차 여부
    $full2 = $dataLink->full2;//만차 여부
    $full1 = ($full1==0)?'':'(만차)';
    $full2 = ($full2==0)?'':'(만차)';

    $message = '이번 버스: ' . $arrmsg1.' ('.$rerideNum1.'명)'.$full1.'\n다음 버스: ' . $arrmsg2.' ('.$rerideNum2.'명)'.$full2;
    return $message;
};

function mealToday() {
echo '{"version": "2.0","template": {"outputs": [{"carousel": {"type": "basicCard","items": [{"title": "오늘 조식","description": "'. meal(1,0) .'"},{"title": "오늘 중식","description": "'. meal(2,0) .'"},{"title": "오늘 석식","description": "'. meal(3,0) .'"}]}}]}}';
}

function mealTomorrow() {
  echo '{"version": "2.0","template": {"outputs": [{"carousel": {"type": "basicCard","items": [{"title": "내일 조식","description": "'. meal(1,1) .'"},{"title": "내일 중식","description": "'. meal(2,1) .'"},{"title": "내일 석식","description": "'. meal(3,1) .'"}]}}]}}';
}function meal($mealcode, $i){                        switch ($mealcode) {                case '1' : $meal = '조식';                                    break;                case '2' : $meal = '중식';                    break;                case '3' : $meal = '석식';                break;            }                        $schYmd = date("Ymd", mktime(0,0,0,date("m")  , date("d")+$i, date("Y")));            $schYmd1 = date("Y.m.d", mktime(0,0,0,date("m")  , date("d")+$i, date("Y")));                        $day = date('w', strtotime($schYmd));            $yoil = array("일","월","화","수","목","금","토");                        $url = 'https://open.neis.go.kr/hub/mealServiceDietInfo?KEY=e77c8447cce64754b960a4c73244dcd1&Type=json&pIndex=1&pSize=10&ATPT_OFCDC_SC_CODE=B10&SD_SCHUL_CODE=7010115&MLSV_YMD='.$schYmd;                        while (strlen($text)==0) {                $text = @file_get_contents($url);            }            if ($meal == '조식'){                                if (strpos($text, $meal)){                    $explode1 = explode('"DDISH_NM":"', $text);                    $a = explode('","ORPLC_INFO"', $explode1[1]);                    $meal1 = $a[0];                    $meal1 = str_replace('<br/>', '\n', $meal1);                                        return $schYmd1 . ' (' . $yoil[$day].') '. $meal . '\n' . $meal1 ;                }                else{                    return $schYmd1 . ' (' . $yoil[$day].') '. $meal . '\n급식이 없습니다.' ;                }            }                        if ($meal == '중식'){                                if (strpos($text, $meal)){                                        $explod_before2 = explode($meal, $text);                    $explode2 = explode('"DDISH_NM":"', $explod_before2[1]);                    $b = explode('","ORPLC_INFO"', $explode2[1]);                    $meal2 = $b[0];                    $meal2 = str_replace('<br/>', '\n', $meal2);                                    return $schYmd1 . ' (' . $yoil[$day].') '. $meal . '\n' . $meal2 ;                }                else{                    return $schYmd1 . ' (' . $yoil[$day].') '. $meal . '\n급식이 없습니다.' ;                }                        }                        if ($meal == '석식'){                                if (strpos($text, $meal)){                                        $explod_before3 = explode($meal, $text);                    $explode3 = explode('"DDISH_NM":"', $explod_before3[1]);                    $c = explode('","ORPLC_INFO"', $explode3[1]);                    $meal3 = $c[0];                    $meal3 = str_replace('<br/>', '\n', $meal3);                                    return $schYmd1 . ' (' . $yoil[$day].') '. $meal . '\n' . $meal3 ;                                    }                else{                    return $schYmd1 . ' (' . $yoil[$day].') '. $meal . '\n급식이 없습니다.' ;                }            }        }

function snack(){
    $indexT = date("md", mktime(0,0,0,date("m")  , date("d")+1, date("Y")));
    $monthT = date("m", mktime(0,0,0,date("m")  , date("d")+1, date("Y")));
    $dateT = date("d", mktime(0,0,0,date("m")  , date("d")+1, date("Y")));

    $index = date("md", mktime(0,0,0,date("m")  , date("d"), date("Y")));
    $month = date("m", mktime(0,0,0,date("m")  , date("d"), date("Y")));
    $date = date("d", mktime(0,0,0,date("m")  , date("d"), date("Y")));



    $snack = array
    (
        '1201'=>"간식이 없습니다.",
        '1202'=>"간식이 없습니다.",
        '1203'=>"간식이 없습니다.",
        '1204'=>"간식이 없습니다.",
        '1205'=>"간식이 없습니다.",
        '1206'=>"간식이 없습니다.",
        '1207'=>"간식이 없습니다.",
        '1208'=>"간식이 없습니다.",
        '1209'=>"간식이 없습니다.",
        '1210'=>"간식이 없습니다.",
        '1211'=>"간식이 없습니다.",
        '1212'=>"간식이 없습니다.",
        '1213'=>"간식이 없습니다.",
        '1214'=>"간식이 없습니다.",
        '1215'=>"간식이 없습니다.",
        '1216'=>"간식이 없습니다.",
        '1217'=>"간식이 없습니다.",
        '1218'=>"간식이 없습니다.",
        '1219'=>"간식이 없습니다.",
        '1220'=>"간식이 없습니다.",
        '1221'=>"간식이 없습니다.",
        '1222'=>"간식이 없습니다.",
        '1223'=>"간식이 없습니다.",
        '1224'=>"간식이 없습니다.",
        '1225'=>"간식이 없습니다!!",
        '1226'=>"간식이 없습니다.",
        '1227'=>"간식이 없습니다.",
        '1228'=>"간식이 없습니다.",
        '1229'=>"간식이 없습니다.",
        '1230'=>"간식이 없습니다.",
        '1231'=>"간식이 없습니다."
    );

    echo '{"version":"2.0","template":{"outputs": [{"carousel": {"type": "basicCard","items": [{"title": "오늘의 간식","description": "'. $month.'월 '.$date.'일\n'. $snack[$index] .'"},{"title": "내일의 간식","description": "'. $monthT.'월 '.$dateT.'일\n'. $snack[$indexT] .'"}]}}]}}';
}

function weather() {
  $atmosphere = atmosphere();
  $dust = dust();
  echo '{"version":"2.0","template":{"outputs": [{"carousel": {"type": "basicCard","items": [{"title": "날씨","description": "'. $atmosphere .'"},{"title": "미세먼지","description": "'. $dust .'"}]}}]}}';
}

function atmosphere() {
    function makeWeather($i, $xxml)
    {
        $weatherHour = (int)$xxml->channel->item->description->body->data[$i]->hour;//날짜
        $weatherTemp = (int)$xxml->channel->item->description->body->data[$i]->temp;//기온
        $weatherKor = $xxml->channel->item->description->body->data[$i]->wfKor;//날씨 상태 설명
        $weatherDay = (int)$xxml->channel->item->description->body->data[$i]->day;//오늘,내일,모레

        //weatherday를 단어로 바꿈
        switch($weatherDay)
        {
            case 0: $flag = '오늘'; break;
            case 1: $flag = '내일'; break;
            case 2: $flag = '모레'; break;
            default: $flag = '오류'; break;
        }

        return $flag.' '.$weatherHour .'시: '.$weatherTemp.'°C, '.$weatherKor;
    };
    $url = 'http://www.kma.go.kr/wid/queryDFSRSS.jsp?zone=1141052000';
    $xml = file_get_contents($url);
    $xml = simplexml_load_string($xml);
    return makeWeather(0, $xml).'\n'.makeWeather(1, $xml).'\n'.makeWeather(2, $xml).'\n'. makeWeather(3, $xml).'\n'.makeWeather(4, $xml).'\n'.makeWeather(5, $xml).'\n'.makeWeather(6, $xml);
}

function dust() {
    function trans($grade)
    {
        switch((int)$grade)
        {
            case 1: return '좋음';
            case 2: return '보통';
            case 3: return '나쁨';
            case 4: return '매우 나쁨';
            default: return '오류';
        }
    }
    $url = 'http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty?serviceKey=7CxyK2MY%2FOJbyTEfTOos3h%2BoMnDcNzRuV7treNzPIu7pEdlJ3KIi4BsM7E9egEaUtb%2ByhVu68PB8c%2F4pabWsWQ%3D%3D&numOfRows=1&pageNo=1&stationName=%EC%84%9C%EB%8C%80%EB%AC%B8%EA%B5%AC&dataTerm=DAILY&ver=1.3';
    $xml = file_get_contents($url);
    $xml = simplexml_load_string($xml);
    $dir = $xml->body->items->item;
    $pm10 = trans($dir->pm10Grade1h);
    $pm25 = trans($dir->pm25Grade1h);
    $date = $dir->dataTime;
    $co = trans($dir->coGrade);
    $o3 = trans($dir->o3Grade);

    $month = (int)substr($date, 0,4);
    $day = (int)substr($date,5,2);
    $time = (int)substr($date, 11,2);

    $date = $month.'월 '.$day.'일 '.$time.'시 기준';
    return $date.'\n미세먼지: '.$pm10.'\n초미세먼지: '.$pm25.'\nCO: '.$co.'\n오존: '.$o3;
}

$function();
?>
