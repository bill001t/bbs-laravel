<?php

namespace App\Services\emotion\ds\dao;

use App\Services\emotion\ds\relation\Emotion;

class PwEmotionDao extends Emotion
{
    public function getEmotion($emotionId)
    {
        return self::find($emotionId);
    }

    public function fetchEmotion($emotionIds)
    {
        return self::whereIn('emotion_id', $emotionIds)
            ->get();
    }

    public function fetchEmotionByCatid($categoryIds)
    {
        return self::where('isused', 1)
            ->whereIn('category_id', $categoryIds)
            ->orderby('vieworder', 'asc')
            ->get();
    }

    public function getListByCatid($categoryId, $isUsed = null)
    {
        $sql = self::where('category_id', $categoryId);

        if (isset($isUsed)) {
            $sql = $sql->where('isused', $isUsed);
        }

        return $sql->orderby('vieworder', 'asc')
            ->get();
    }

    public function getAllEmotion()
    {
        return self::all();
    }

    public function addEmotion($data)
    {
        return self::create($data);
    }

    public function updateEmotion($emotionId, $data)
    {
        return self::where('emotion_id', $emotionId)
            ->update($data);
    }

    public function deleteEmotion($emotionId)
    {
        return self::destroy($emotionId);
    }

    public function deleteEmotionByCatid($cateId)
    {
        self::where('category_id', $cateId)
            ->delete();

        SimpleHook::getInstance('PwEmotionDao_deleteEmotionByCatid')->runDo($cateId);

        return true;
    }
}

?>