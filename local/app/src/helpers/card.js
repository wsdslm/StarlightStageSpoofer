export function parseRarity(rarity) {
    switch (Number(rarity)) {
        case 1: return "N";
        case 2: return "N+";
        case 3: return "R";
        case 4: return "R+";
        case 5: return "SR";
        case 6: return "SR+";
        case 7: return "SSR";
        default: return "SSR+";
    }
}
