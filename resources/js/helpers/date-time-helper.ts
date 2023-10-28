export function formatDate(date: string | null) {
    if (null === date) {
        return 'N/A';
    }
    return new Date(date).toLocaleString();
}

export function spentTime(secs: Number) {
    if (null === secs) {
        return [];
    }
    const parts = [
        {unit: 'day', value: 60 * 60 * 24},
        {unit: 'hour', value: 60 * 60},
        {unit: 'minute', value: 60},
        {unit: 'second', value: 1},
    ];
    return parts.reduce((result, part) => {
        const value = Math.floor(secs / part.value);
        if (value <= 0) {
            return result;
        }
        const unitPostfix = value > 1 ? 's' : '';
        secs -= value * part.value;
        result.push({
            count: value,
            unit: `${part.unit}${unitPostfix}`,
        });
        return result;
    }, []);
}
