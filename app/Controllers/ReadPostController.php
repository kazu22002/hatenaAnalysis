<?php
/**
 * Created by PhpStorm.
 * User: seo
 * Date: 2019-03-24
 * Time: 20:35
 */

namespace App\Controllers;

use App\Usecases\Analysis;
use App\Usecases\GoogleSheetsApi;
use App\Usecases\HatenaGet;

class ReadPostController {

    public function readAndPost(){
        $get = new HatenaGet();
        $data = $get->get();
        if ($data == false) {
            return;
        }
        $analysis = new Analysis();
        $analysis->analysis($data);

        $logic = new GoogleSheetsApi();
        $logic->appendHatenaFeed($logic->appendHatenaFeedTransfer($data));
    }

}


