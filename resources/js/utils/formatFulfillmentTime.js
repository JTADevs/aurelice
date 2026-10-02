/**
 * Format an order fulfillment time, e.g. 1 -> "1 dzień", 3 -> "3 dni".
 */
export function formatFulfillmentTime(days) {
    return days === 1 ? '1 dzień' : `${days} dni`;
}
