<?php
namespace App\Handlers;

class TrainingReinforce extends Training {
    protected function updateData($json, $data) {
        $json->exp = $data['after_exp'];
        $json->level = $data['after_level'];
        $json->skill_level = $data['after_skill_level'];
        return $json;
    }
}
