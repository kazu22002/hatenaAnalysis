<?php
/**
 * Created by PhpStorm.
 * User: seo
 * Date: 2019-03-24
 * Time: 21:35
 */

namespace App\Entity;

class Analysis {

    public $category = ""; // カテゴリ名
    public $categoryCount = 0; // カテゴリ数
    public $contentCount = 0;  // メッセージ数

    public function __construct()
    {
    }
}