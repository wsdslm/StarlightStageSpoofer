<?php

// for placeholder or debugging purposes, use \App\Handlers\Handler
return [
    "load/index" => \App\Handlers\LoadIndex::class,
    "gacha/exec" => \App\Handlers\GachaExec::class,
    "unit/edit" => \App\Handlers\UnitEdit::class,

    "live/end" => \App\Handlers\LiveEnd::class,
    "live/start" => \App\Handlers\LiveStart::class,
    "live/start_view" => \App\Handlers\LiveStartView::class,
    "live/start_rehearsal" => \App\Handlers\Handler::class,

    "training/evolve" => \App\Handlers\TrainingEvolve::class,
    "training/exceed" => \App\Handlers\TrainingExceed::class,
    "training/reinforce" => \App\Handlers\TrainingReinforce::class,

    /*
    "event/medley/load" => \App\Handlers\EventMedleyNext::class,
    "event/medley/live_lot" => \App\Handlers\EventMedleyNext::class,
    "event/medley/start" => \App\Handlers\EventMedleyNext::class,
    "event/medley/live_break" => \App\Handlers\EventMedleyNext::class,
    "event/medley/end" => \App\Handlers\EventMedleyNext::class,
    */

    "event/medley/set_unit" => \App\Handlers\EventMedleySetUnit::class,
    "event/medley/next" => \App\Handlers\EventMedleyNext::class,

    "album/index" => \App\Handlers\AlbumIndex::class,
    "profile/get_profile" => \App\Handlers\Handler::class,

    "name_card/index" => \App\Handlers\Handler::class,
    "name_card/update" => \App\Handlers\Handler::class
];
