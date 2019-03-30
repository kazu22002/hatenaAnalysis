<?php
/**
 * Created by PhpStorm.
 * User: seo
 * Date: 2019-03-24
 * Time: 20:30
 */

namespace App\Usecases;

use App\Entity\HatenaFeed;
use App\Entity\Analysis as EntityAnalysis;

/**
 * Class Analysis
 * @package App\Usecases
 */
class Analysis{

    protected $category = [];

    /**
     * @param array $data HatenaFeed
     */
    public function analysis(array $data) {
        for($i = 0; $i < count($data); $i++){
            $v = $this->get($data, $i);

            $this->calcAnalysis($v);
        }
    }

    protected function get($data, $i) : HatenaFeed {
        return $data[$i];
    }

    protected function calcAnalysis(HatenaFeed $param) {
        if( count($param->category) == 0 ) {
            return ;
        }
        foreach( $param->category as $key => $val ) {
            if( !isset($val["term"]) ) {
                return ;
            }
            $categoryName = $val["term"];

            $e = $this->getAnalysis($categoryName);
            // 本文の文字数
            $e->contentCount += mb_strlen($param->content);
            $e->categoryCount++;

            $this->category[$categoryName] = $e;
        }
    }

    protected function getAnalysis($category) : EntityAnalysis {
        if( array_key_exists( $category, $this->category )) {
            return $this->category[$category];
        }

        $e = new EntityAnalysis();
        $e->category = $category;
        return $e;
    }

}
