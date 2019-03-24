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
    public $title;
    public $updated;
    public $published;
    public $summary;
    public $content;
    public $category = [];

    /**
     * @param $object
     */
    public function __construct($object){
        $this->title = $object->title;
        $this->updated = $object->updated;
        $this->published = $object->published;
        $this->summary = $object->summary;
        $this->content = $object->content;
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
