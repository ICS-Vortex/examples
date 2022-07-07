import i18n from "../i18n";

export const LANGUAGE_RUSSIAN = 'ru';
export const LANGUAGE_ENGLISH = 'us';
export const LANGUAGES = [
    {
        title: i18n.t('language.english'),
        code: LANGUAGE_ENGLISH,
    },
    {
        title: i18n.t('language.russian'),
        code: LANGUAGE_RUSSIAN,
    }
];
export const LANGUAGES_ARRAY = [LANGUAGE_RUSSIAN, LANGUAGE_ENGLISH];
