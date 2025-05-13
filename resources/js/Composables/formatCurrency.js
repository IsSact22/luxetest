export default function useFormatCurrency() {
    function formatCurrency(value, locale = 'en-US', currency = 'USD') {
        const formatter = new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: currency,
        });
        return formatter.format(value);
    }

    return {
        formatCurrency,
    };
}
