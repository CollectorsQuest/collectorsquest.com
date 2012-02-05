<?php

class YahooTermExtraction
{
 	public static function exract($context, $query = null)
 	{
 	  $parameters = array(
 	    'appid' => sfConfig::get('app_credentials_yahoo'),
 	    'output' => 'php'
 	  );
 	  $post = array('context' => utf8_encode($context), 'query' => $query);

 	  $curl = curl_init();
 	  curl_setopt($curl, CURLOPT_URL, 'http://api.search.yahoo.com/ContentAnalysisService/V1/termExtraction?'. http_build_query($parameters, null, '&'));
 	  curl_setopt($curl, CURLOPT_POST, 1);
 	  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 	  curl_setopt($curl, CURLOPT_TIMEOUT, 5);
 	  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post, null, '&'));

 	  $tries = 0;
 	  do
 	  {
	     $response = curl_exec($curl);

	     if ($tries > 5)
 	    {
 	      curl_setopt($curl, CURLOPT_PROXY, false);
 	      curl_setopt($curl, CURLOPT_PROXYPORT, false);
 	      $response = curl_exec($curl);
 	    }

 	    $tries++;
 	  }
 	  while(curl_errno($curl) != 0 || empty($response));

 	  $ResultSet = @array_shift(unserialize($response));

 	  return (is_array($ResultSet)) ? (array) array_shift($ResultSet) : array();
 	}
}
