module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    purge: [],
    theme: {
        extend: {
            colors: {
                'light-blue': {
                    400: '#5EE4FF',
                    500: 'var(--light-blue)',
                    600: '#156C7D',
                },
            },
            brown: {
                400: '#C97A36',
                500: 'var(--brown)',
            },
        },
    },
    variants: {},
    plugins: [],
};
