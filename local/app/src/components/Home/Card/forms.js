export const forms = [
    "serial_id|Serial ID",
    "card_id|Card ID|editable",
    "exp|Exp|editable",
    "love|Love|editable",
    "level|Level|editable",
    "step|Step|editable",
    "skill_level|Skill level|editable",
    "game_units|Unit|map:unit_id",
];

export const tagHandlers = {
    map: (value, args) => _.map(value, (obj) => obj[args[1]]).join(", ")
};
