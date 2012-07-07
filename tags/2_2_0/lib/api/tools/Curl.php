<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2.0
 * @package    WoWRoster
 */

class Curl {
	public $errno = CURLE_OK;
	public $error = '';

	/**
	 * Executes a curl request.
	 *
	 * @param string $url URL to make the request
	 * @param string $method Method to make (GET, POST, etc)
	 * @param array $options Various options for the request (including data)
	 * @return array Array containing the 'response' and the 'code'
	 */
	 public function pulldata($url)
	 {
				$file_handle = fopen($url, 'r');
				$response = fread($file_handle, filesize($url));
				fclose($file_handle);
				$headers = array('http_code'=>303);
				return array(
				'response'		    => stripslashes($response),
				'response_headers'  => $headers,
			);
	 }

	 /*
	public function genauth($url)
	{
		global $roster;

		$UrlPath = '/'.$url;
		$keys = array(
			'public' => $roster->config['api_key_public'],
			'private' => $roster->config['api_key_private']
		);

		$date = date('D, d M Y G:i:s T');//date(DATE_RFC2822);
		$header = "Date: ". $date."\nAuthorization: BNET ". $keys['public'] .":". base64_encode(hash_hmac('sha1', "GET\n".$date."\n".$UrlPath."\n", $keys['private'], true))."\n";
			
			
		return $header;
	}
	*/

    public function curl_exec_follow($ch, &$maxredirect = null) 
    {
        $mr = $maxredirect === null ? 5 : intval($maxredirect);
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) 
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
            curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
        } else 
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            if ($mr > 0) 
            {
                $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);    
                $rch = curl_copy_handle($ch);
                curl_setopt($rch, CURLOPT_HEADER, true);
                curl_setopt($rch, CURLOPT_NOBODY, true);
                curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
                curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
                do 
                {
                    curl_setopt($rch, CURLOPT_URL, $newurl);
                    $header = curl_exec($rch);
                    if (curl_errno($rch)) $code = 0;
                    else 
                    {
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302) 
                        {
                            preg_match('/Location:(.*?)\n/', $header, $matches);
                            $newurl = trim(array_pop($matches));
                        } else $code = 0;
                    }
                } while ($code && --$mr);
                curl_close($rch);
                if (!$mr) 
                {
                    if ($maxredirect === null) 
                        trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
                    else $maxredirect = 0;
                    return false;
                }
                curl_setopt($ch, CURLOPT_URL, $newurl);
            }
        }
        return curl_exec($ch);
    }
	
	public function genauth($path,$method)
	{
		global $roster;

		$keys = array(
			'public' => $roster->config['api_key_public'],
			'private' => $roster->config['api_key_private']
		);
		$headers = '';
		if ($keys['public'] !== null && $keys['private'] !== null) {
            $date = gmdate(DATE_RFC1123);

            //Signed requests don't like urlencoded quotes
            $path = str_replace('%27', '\'',$path); 

            $stringToSign = "$method\n" . $date . "\n".$path."\n";
            $signature = base64_encode(hash_hmac('sha1',$stringToSign, $keys['private'], true));

            $this->headers['Authorization'] =  "BNET" . " " . $keys['public'] . ":" . $signature;
            $this->headers['Date'] = $date;
			$headers .= "\nAuthorization: BNET" . " " . $keys['public'] . ":" . $signature;
            $headers .= "\nDate: ".$date."\n";
        }
		return $headers;

	}
	public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }
	
	public function makeRequest($url, $method='GET', $options=array(),$uri,$method) 
	{
        $open_basedir_value = ini_get('open_basedir');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!isset($open_basedir_value) || empty($open_basedir_value)) 
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,		 isset($options['timeout']) ? $options['timeout'] : 10);
		curl_setopt($ch, CURLOPT_VERBOSE,		 isset($options['verbose']) ? $options['verbose'] : false);

		// Prepare data (if applicable)
		if (isset($options['data']) && !empty($options['data'])) {
			if (isset($options['data_type'])) {
				switch($options['data_type']) {
					case 'json':
						$data = json_encode($options['data']);
						break;
				}
			} else {
				if (is_array($options['data'])) {
					$data = '';
					foreach($options['data'] as $key => $value) {
						$data .= $key.'='.$value.'&';
					}
					$data = rtrim($data, '&');
				} else {
					$data = $options['data'];
				}
			}
		}

		if ($method != 'itemtooltip' && $method != 'talents')
		{
			$headersss = $this->genauth($uri,$method);		
			$options['header'] .= $headersss;
		}
		//var_dump($options);
		// Prepare headers (if applicable)
		if (isset($options['headers'])) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $options['header']);
		}

		// Setup methods
		switch($method) {
			case 'GET':
				if(!empty($data)) {
					curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
				}
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case 'PUT':
				$file_handle = fopen($data, 'r');
				curl_setopt($ch, CURLOPT_PUT, true);
				curl_setopt($ch, CURLOPT_INFILE, $file_handle);
				break;
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}

		// Execute
        if (!isset($open_basedir_value) || empty($open_basedir_value))
            $response = curl_exec($ch);
        else
            $response = $this->curl_exec_follow($ch);
		$headers		= curl_getinfo($ch);
		//Deal with HTTP errors
		$this->errno	= curl_errno($ch);
		$this->error	= curl_error($ch);

		curl_close($ch);
		if ($this->errno) {
			return false;
		}else{

			return array(
				'response'		    => $response,
				'response_headers'  => $headers,
			);
		}
	}
}
