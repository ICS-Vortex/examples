import i18next from 'i18next';
import {initReactI18next} from 'react-i18next';
import en from './translations/en.json';
import ru from './translations/ru.json';

const resources = {
    us: {
        translation: en,
    },
    ru: {
        translation: ru,
    },

}

i18next
    .use(initReactI18next)
    .init({
        lng: localStorage.getItem('locale') || 'us',
        debug: process.env.NODE_ENV === 'development',
        resources: resources,
        keySeparator: false,
        fallbackLng: 'us',

        interpolation: {
            escapeValue: false, // not needed for react!!
            // formatSeparator: ',',
            // format(value, format) {
            //     if (format === 'uppercase') return value.toUpperCase();
            //     return value;
            // },
        },

        react: {
            defaultTransParent: 'div',
            transSupportBasicHtmlNodes: true,
            transKeepBasicHtmlNodesFor: ['br', 'strong', 'i'],
        },
    });

export default i18next;