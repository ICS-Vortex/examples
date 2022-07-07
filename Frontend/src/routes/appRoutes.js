import {
    PAGE_ABOUT_US,
    PAGE_ARTICLE,
    PAGE_COUPONS,
    PAGE_CUSTOM,
    PAGE_FEEDBACK,
    PAGE_HOME,
    PAGE_LOGIN,
    PAGE_LOGIN_UCID,
    PAGE_MISSION,
    PAGE_PILOT,
    PAGE_PROFILE,
    PAGE_RACING,
    PAGE_RACING_PILOT,
    PAGE_REGISTRATION,
    PAGE_SERVER_ATTACKERS,
    PAGE_SERVER_BANLIST,
    PAGE_SERVER_FAQ,
    PAGE_SERVER_FIGHTERS,
    PAGE_SERVER_HOME,
    PAGE_SERVER_INFO,
    PAGE_SERVER_MISSIONS,
    PAGE_SERVER_STATISTICS,
    PAGE_TOURNAMENT,
    PAGE_TOURNAMENT_COUPON,
    PAGE_TOURNAMENT_CUSTOM_PAGE,
    PAGE_TOURNAMENT_FAQ,
    PAGE_TOURNAMENT_PILOT, PAGE_TOURNAMENT_PROFILE,
    PAGE_TOURNAMENT_STATISTICS
} from '../constants/routes';
import HomePage from '../pages/Home/HomePage';
import FeedbackPage from '../pages/Feedback/FeedbackPage';
import LoginPage from '../pages/Login/LoginPage';
import ProfilePage from '../pages/Profile/ProfilePage';
import PilotPage from '../pages/Pilot/PilotPage';
import CustomPage from "../pages/Custom/CustomPage";
import ServerStatisticsPage from "../pages/Server/Stats/ServerStatisticsPage";
import ServerHomePage from "../pages/Server/Home/ServerHomePage";
import ServerMissionsPage from "../pages/Server/Missions/ServerMissionsPage";
import ServerFightersPage from "../pages/Server/Fighters/ServerFightersPage";
import ServerAttackersPage from "../pages/Server/Attackers/ServerAttackersPage";
import ServerArticlePage from "../pages/Server/Article/ServerArticlePage";
import ServerFaqPage from "../pages/Server/Faq/ServerFaqPage";
import ServerBanlistPage from "../pages/Server/Banlist/ServerBanlistPage";
import ServerInfoPage from "../pages/Server/Info/ServerInfoPage";
import MissionPage from "../pages/Mission/MissionPage";
import TournamentHomePage from "../pages/Tournament/Home/TournamentHomePage";
import TournamentFaqPage from "../pages/Tournament/Faq/TournamentFaqPage";
import RacingPage from "../pages/Racing/RacingPage";
import RacingPilotPage from "../pages/RacingPilotPage";
import RegistrationPage from "../pages/Registration/RegistrationPage";
import TournamentCustomPage from "../pages/Tournament/Page/TournamentCustomPage";
import TournamentStatisticsPage from "../pages/Tournament/Statistics/TournamentStatisticsPage";
import UcidLoginPage from "../pages/Login/UcidLoginPage";
import TournamentPilotPage from "../pages/Tournament/Pilot/TournamentPilotPage";
import TournamentCouponPage from "../pages/Tournament/Coupon/TournamentCouponPage";
import CouponsPage from "../pages/Coupons/CouponsPage";
import TournamentProfilePage from "../pages/Tournament/Profile/TournamentProfilePage";

export const AppRoutes = [
    {
        path: PAGE_HOME,
        component: HomePage,
        exact: true
    },
    {
        path: PAGE_SERVER_STATISTICS,
        component: ServerStatisticsPage,
        exact: false
    },
    {
        path: PAGE_SERVER_MISSIONS,
        component: ServerMissionsPage,
        exact: false
    },
    {
        path: PAGE_SERVER_FAQ,
        component: ServerFaqPage,
        exact: false
    },
    {
        path: PAGE_SERVER_BANLIST,
        component: ServerBanlistPage,
        exact: false
    },
    {
        path: PAGE_SERVER_INFO,
        component: ServerInfoPage,
        exact: false
    },
    {
        path: PAGE_MISSION,
        component: MissionPage,
        exact: false,
    },
    {
        path: PAGE_ARTICLE,
        component: ServerArticlePage,
        exact: false
    },
    {
        path: PAGE_SERVER_FIGHTERS,
        component: ServerFightersPage,
        exact: false
    },
    {
        path: PAGE_SERVER_ATTACKERS,
        component: ServerAttackersPage,
        exact: false
    },
    {
        path: PAGE_SERVER_HOME,
        component: ServerHomePage,
        exact: false
    },
    {
        path: PAGE_PILOT,
        component: PilotPage,
        exact: false
    },
    {
        path: PAGE_FEEDBACK,
        component: FeedbackPage,
        exact: false
    },
    {
        path: PAGE_FEEDBACK,
        component: FeedbackPage,
        exact: false
    },
    {
        path: PAGE_LOGIN,
        component: LoginPage,
        exact: false
    }, {
        path: PAGE_LOGIN_UCID,
        component: UcidLoginPage,
        exact: false
    },
    {
        path: PAGE_PROFILE,
        component: ProfilePage,
        exact: false
    },
    {
        path: PAGE_CUSTOM,
        component: CustomPage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT,
        component: TournamentHomePage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT_FAQ,
        component: TournamentFaqPage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT_CUSTOM_PAGE,
        component: TournamentCustomPage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT_STATISTICS,
        component: TournamentStatisticsPage,
        exact: false
    },
    {
        path: PAGE_RACING,
        component: RacingPage,
        exact: false
    },
    {
        path: PAGE_RACING_PILOT,
        component: RacingPilotPage,
        exact: false
    },
    {
        path: PAGE_REGISTRATION,
        component: RegistrationPage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT_PILOT,
        component: TournamentPilotPage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT_COUPON,
        component: TournamentCouponPage,
        exact: false
    },
    {
        path: PAGE_TOURNAMENT_PROFILE,
        component: TournamentProfilePage,
        exact: false
    },
    {
        path: PAGE_COUPONS,
        component: CouponsPage,
        exact: false
    },
    {
        path: PAGE_ABOUT_US,
        component: CustomPage,
        exact: false
    },
];

export const securedRoutes = [
    {
        path: PAGE_PROFILE,
        component: ProfilePage,
        exact: false
    },
];
