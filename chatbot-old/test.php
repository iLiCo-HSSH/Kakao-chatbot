<?php
function bus(){
  $ch = curl_init();
  $url = 'http://ws.bus.go.kr/api/rest/arrive/getLowArrInfoByRoute'; /*URL*/
  $queryParams = '?' . urlencode('ServiceKey') . '=HR15nrUmf2vUG%2FMNZD1cvS7KdyoGo8JxEASPTqr5iYC9dEJX9A%2BgCIWJBgoESxu1QuvFrzvq%2Bjs%2FbHDI8BF7KQ%3D%3D'; /*Service Key*/
  $queryParams .= '&' . urlencode('stId') . '112000428' . urlencode(''); /**/
  $queryParams .= '&' . urlencode('busRouteId') . '100100363' . urlencode(''); /**/
  $queryParams .= '&' . urlencode('ord') . '32' . urlencode(''); /**/

  curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
  curl_close($ch);

  var_dump($response);

  }
  
 ?>
