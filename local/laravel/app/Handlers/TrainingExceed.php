<?php
namespace App\Handlers;

class TrainingExceed extends Training {
    protected function updateData($json, $data) {
        $json->step = $data['after_step'];
        return $json;
    }
}
