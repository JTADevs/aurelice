const wholeFormatter = new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN', maximumFractionDigits: 0 });
const fractionFormatter = new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN', minimumFractionDigits: 2 });

/**
 * Format a price stored in grosze, e.g. 42000 -> "420 zł", 24999 -> "249,99 zł".
 */
export function formatPrice(grosze) {
    const formatter = grosze % 100 === 0 ? wholeFormatter : fractionFormatter;

    return formatter.format(grosze / 100);
}
