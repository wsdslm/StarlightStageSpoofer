import { Schema, arrayOf } from 'normalizr'

export const UserSchema = new Schema('users');
export const GameUserSchema = new Schema('game_users', { idAttribute: 'viewer_id' });
export const GameCardSchema = new Schema('game_cards', { idAttribute: 'serial_id' });

GameUserSchema.define({
    game_cards: arrayOf(GameCardSchema),
});

UserSchema.define({
    game_user: GameUserSchema
});
