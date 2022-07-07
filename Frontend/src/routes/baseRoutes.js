import i18next from "../i18n";
import {PAGE_FEEDBACK, PAGE_HOME, PAGE_NEWS} from '../constants/routes';

export const baseRoutes = [
    {
        id: 0,
        icon: 'home',
        path: PAGE_HOME,
        description: i18next.t('page.home'),
    },
    {
        id: 1,
        icon: 'news',
        path: PAGE_NEWS,
        description: i18next.t('page.news'),
    },
    {
        id: 2,
        icon: 'feedback',
        path: PAGE_FEEDBACK,
        description: i18next.t('page.feedback')
    },
];
