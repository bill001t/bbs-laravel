<?php

namespace App\Services\emotion\ds\dao;

use App\Services\emotion\ds\relation\EmotionCategory;

class PwEmotionCategoryDao extends EmotionCategory
{
    public function getCategory($categoryId)
    {
        return self::find($categoryId);
    }

    public function fetchCategory($categoryIds)
    {
        return self::whereIn('category_id', $categoryIds);
    }

    public function getCategoryList($app, $isOpen = null)
    {
        $sql = self::whereRaw('1 = 1');

        if ($app) {
            $sql = $sql->where('emotion_apps', 'like', '%' . $app . '%');
        }
        if (isset($isOpen)) {
            $sql = $sql->where('isopen', $isOpen);
        }

        return $sql->orderby('orderid', 'ASC')
            ->get();
    }

    public function addCategory($data)
    {
        return self::create($data);
    }

    public function updateCategory($categoryId, $data)
    {
        return self::where('category_id', $categoryId)
            ->update($data);
    }

    public function deleteCategory($categoryId)
    {
        return self::destroy($categoryId);
    }
}

?>