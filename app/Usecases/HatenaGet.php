<?php
/**
 * Created by PhpStorm.
 * User: seo
 * Date: 2019-03-24
 * Time: 20:30
 */

namespace App\Usecases;

use App\Entity\HatenaFeed;

/**
 * Class HatenaPost
 * @package App\Usecases
 */
class HatenaGet{
    /**
     * @param $data
     */
    public function get( ){
        $url = getenv('HATENA_ATOMPUB');
        $buf = $this->getLinkAll($url."/entry");
        $xml = simplexml_load_string($buf);

        $feed = [];
        foreach($xml as $key => $val ){
            $feed[] = new HatenaFeed($val);
        }

        $attributes = "@attributes";
        $nextLink = "";
        // about 5 loop
        for($i = 0; $i < 5;$i++){
            foreach($xml as $key => $val){
                if( $key == "link" ) {
                    // jsonへ
                    $json = json_encode($val);
                    $jsonCategory = json_decode($json,TRUE);

                    if($jsonCategory[$attributes]["rel"] == "next"){
                        $nextLink = $jsonCategory[$attributes]["href"];
                        $nextBuf = $this->getLinkAll( $jsonCategory[$attributes]["href"] );
                        $xml = simplexml_load_string($nextBuf);
                        foreach($xml as $k => $v ){
                            $feed[] = new HatenaFeed($v);
                        }
                    }
                }
            }

            if ($nextLink == "") {
                break;
            }
        }

        return $feed;
    }

    protected function getLinkAll( $url ){
        $name = getenv('HATENA_NAME');
        $password = getenv('HATENA_APIKEY');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, $name . ":" . $password);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $buf = curl_exec($curl);
        curl_close($curl);

        return $buf;
    }

    /**
     * @param $name
     * @param $password
     * @return string
     */
    function basicEncode($name,$password){
        return base64_encode($name.":".$password);
    }
}