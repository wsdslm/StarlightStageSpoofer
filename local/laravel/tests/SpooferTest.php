<?php

use App\Helpers\Helper;
use App\User;

use MessagePack\MessagePackFactory;
use Illuminate\Database\QueryException;
use Spoofer\Certification;

class SpooferTest extends TestCase
{
    public function testLoadIndex() {
        Certification::udid('ayy-lmao');
        $this->doTest(\App\Handlers\LoadIndex::class, 'load.index');
    }

    public function testUnbindLoadIndex() {
        Certification::udid('muhdik');
        $this->doTest(\App\Handlers\LoadIndex::class, 'unbind-load.index');
    }

    public function testTrainingEvolve() {
        $this->doTest(\App\Handlers\TrainingEvolve::class, 'training.evolve');
    }

    public function testTrainingReinforce() {
        $this->doTest(\App\Handlers\TrainingReinforce::class, 'training.reinforce');
    }

    public function testLiveEnd() {
        $this->doTest(\App\Handlers\LiveEnd::class, 'live.end');
    }

    public function testGachaExec() {
        $this->doTest(\App\Handlers\GachaExec::class, 'gacha.exec');
        $this->doTest(\App\Handlers\GachaExec::class, 'bulk-gacha.exec');
    }

    public function testEventMedley() {
        $this->doTest(\App\Handlers\EventMedleySetUnit::class, 'event.medley.set_unit');
        $this->doTest(\App\Handlers\EventMedleyNext::class, 'event.medley.next');
    }

    public function testUserSettings() {
        $user = User::first();
        $settings = json_decode($user->gameUser->settings_json);
        $card_id = Helper::traverse($settings, "gacha.card_id");

        /*
        $this->assertNotNull($card_id);
        if (isset($card_id)) {
            $cardPack = $msgpack->iterate('data.card_list.0');
            $card = $cardPack->data;
            $cardPack->iterate('card_id', $card_id);

            $this->cardRepository->insertOrUpdate(['serial_id' => $card['serial_id']], [
                "modified_json" => json_encode([ 'card_id' => $card_id ])
            ]);
        }
        */
    }

    protected function doTest($handlerName, $path) {
        $request = $this->openJSON("$path.request");
        $response = $this->openJSON("$path.response");
        $handler = new $handlerName;
        $handler->request($request);
        try {
            $handler->response($response);
        } catch(QueryException $e) {
            echo $e->getMessage();
        }
    }

    protected function openJSON($filename) {
        $json = json_decode(file_get_contents(__dir__."/mock/$filename.json"), true);
        $msgpack = MessagePackFactory::pack($json);
        $msgpack->packData();
        return $msgpack;
    }
}
