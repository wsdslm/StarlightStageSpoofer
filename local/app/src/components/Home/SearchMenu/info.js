import { parseRarity } from 'helpers/card';
import moment from 'moment';

export default [
    {
        title: "Card Info",
        rows: [
            "id|Card ID",
            { label: "Rarity", value: (card) => parseRarity(card.rarity) },
            {
                label: "Vocal",
                value: (card) => {
                    var json = card.card_json;
                    return Math.round((json.vocal_min + json.vocal_max) / 2);
                }
            },
            {
                label: "Dance",
                value: (card) => {
                    var json = card.card_json;
                    return Math.round((json.dance_min + json.dance_max) / 2);
                }
            },
            {
                label: "Visual",
                value: (card) => {
                    var json = card.card_json;
                    return Math.round((json.visual_min + json.visual_max) / 2);
                }
            },
            {
                label: "Health",
                value: (card) => {
                    var json = card.card_json;
                    return Math.round((json.hp_min + json.hp_max) / 2);
                }
            },
            "max_level|Max level",
            "max_love|Max bond",
            "card_json.skill.skill_type|Skill",
            "card_json.lead_skill.explain_en|Leader skill",
            {
                label: "Solo live",
                value: (card) => card.card_json.solo_live == 0 ? "No" : "Yes"
            }
        ]
    },
    {
        title: "Character Info",
        rows: [
            {
                label: "Name",
                value: (card) => {
                    var chara = card.character.chara_json;
                    return chara.name + " (" + chara.conventional + ")";
                }
            },
            {
                label: "Type",
                value: (card) => _.capitalize(card.character.type)
            },
            {
                label: "Height",
                value: (card) => card.character.chara_json.height + " cm"
            },
            {
                label: "Weight",
                value: (card) => card.character.chara_json.weight + " kg"
            },
            {
                label: "Three sizes",
                value: (card) => {
                    var chara = card.character.chara_json;
                    return [
                        chara.body_size_1,
                        chara.body_size_2,
                        chara.body_size_3
                    ].join("-");
                }
            },
            "character.chara_json.age|Age",
            {
                label: "Birthday",
                value: (card) => {
                    var chara = card.character.chara_json;
                    var month = chara.birth_month;
                    var day = chara.birth_day;
                    return moment(month + "-" + day, "M-D").format("D MMMM");
                }
            }
        ]
    }
];
