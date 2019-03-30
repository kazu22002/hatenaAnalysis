<?php
/**
 *
 */

namespace App\Entity;

/**
 * Class Entity
 * @package App\Entity
 */
class HatenaFeed {
    public $id;
    public $title;
    public $updated;
    public $published;
    public $summary;
    public $content;
    public $category = [];

    /**
     * Todo: 下書き段階の内容も取得されている
     * @param $object
     */
    public function __construct($object){
        $this->id = (string)$object->id;
        $this->title = (string)$object->title;
        $this->updated = (string)$object->updated;
        $this->published = (string)$object->published;
        $this->summary = (string)$object->summary;
        $this->content = (string)$object->content;
        $attributes = "@attributes";
        foreach( $object->category as $v ){
            // jsonへ
            $json = json_encode($v);
            $jsonCategory = json_decode($json,TRUE);

            if( !isset($jsonCategory[$attributes]["term"]) ){
                continue;
            }
            $this->term($jsonCategory[$attributes]["term"]);
        }
    }

    /**
     * カテゴリ追加
     * @param $name
     */
    protected function term( $name){
        $this->category[] = ["term" => $name];
    }
}
