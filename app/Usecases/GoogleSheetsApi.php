<?php
/**
 * Created by PhpStorm.
 * User: seo
 * Date: 2019-03-28
 * Time: 21:25
 */

namespace App\Usecases;

use App\Entity\HatenaFeed;

class GoogleSheetsApi {

    /**
     * @var Google_Service_Sheets
     */
    protected $service;

    /**
     * @var array|false|string
     */
    protected $spreadsheetId;

    /**
     * GoogleSheetsApi constructor.
     */
    public function __construct()
    {
        $credentialsPath = getenv('SERVICE_KEY_JSON');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . dirname(__FILE__) . '/../../config/' . $credentialsPath);

        $this->spreadsheetId = getenv('SPREADSHEET_ID');

        $client = new \Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
        $client->setApplicationName("test");

        $this->service = new \Google_Service_Sheets($client);
    }

    /**
     * @param string $date
     * @param string $name
     * @param string $comment
     */
    public function append(string $date, string $name, string $comment)
    {
        $value = new \Google_Service_Sheets_ValueRange();
        $value->setValues([ 'values' => [ $date, $name, $comment ] ]);
        $response = $this->service->spreadsheets_values->append(
            $this->spreadsheetId
            , 'シート1!A1'
            , $value
            , [ 'valueInputOption' => 'USER_ENTERED' ]
        );
    }

    /**
     * @param HatenaFeed $data
     */
    public function appendHatenaFeed( array $data )
    {
        $value = new \Google_Service_Sheets_ValueRange();
        for($i = 0 ; $i < count($data); $i++){
            $value->setValues([ 'values' => $data[$i] ]);

            $response = $this->service->spreadsheets_values->append(
                $this->spreadsheetId
                , 'シート1!A1'
                , $value
                , [ 'valueInputOption' => 'USER_ENTERED' ]
            );

            // limit api access
            sleep(1);
        }
    }

    public function appendHatenaFeedTransfer(array $data){
        $ret = [];
        for($i = 0; $i < count($data); $i++){
            $v = $data[$i];
            $ret[] = [
                $v->id
                , $v->title
                , date("Y-m-d H:i:s", strtotime($v->updated))
                , date("Y-m-d H:i:s", strtotime($v->published))
                , mb_strlen($v->content)
            ];
        }
        return $ret;
    }
}