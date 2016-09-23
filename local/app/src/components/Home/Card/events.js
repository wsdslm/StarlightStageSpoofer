import { traverseObject } from 'helpers';
import { putObject } from 'helpers/ajax';

export function doSave(gameCard) {
    var modified_json = JSON.stringify(gameCard.modified_json);
    return () => {
        putObject('GameCard', gameCard.id, { modified_json });
    };
}

export function doReset(gameCard, callback) {
    return () => {
        gameCard.modified_json = null;
        callback && callback(gameCard);
    };
}
