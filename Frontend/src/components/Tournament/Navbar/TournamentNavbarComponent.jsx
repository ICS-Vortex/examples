import React from 'react';
import i18next from "i18next";
import {Link} from 'react-router-dom';
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import {useAuth} from "../../../providers/authProvider";

const TournamentNavbarComponent = ({tournament, active}) => {
    const [isLogged] = useAuth();

    return (
        <nav className="main__menu">
            <ul className="text-center align-content-center">
                <li>
                    <Link to={`/tournament/${tournament.id}/home`} className={active === 'home' ? 'active' : ''}>
                        {i18next.t('page.home')}
                    </Link>
                </li>
                <li>
                    <Link to={`/tournament/${tournament.id}/statistics`}
                          className={active === 'statistics' ? 'active' : ''}>
                        {i18next.t('page.tournament.statistics')}
                    </Link>
                </li>
                {isLogged && <li>
                    <Link to={`/tournament/${tournament.id}/profile`}
                          className={active === 'profile' ? 'active' : ''}>
                        {i18next.t('page.tournament.profile')}
                    </Link>
                </li>}
                {tournament.customPages?.map((page) => (
                    <li key={page.id}>
                        <Link to={`/tournament/${tournament.id}/pages/${page.url}`}
                              className={active === page.url ? 'active' : ''}>
                            {i18next.language === LANGUAGE_ENGLISH ? page.titleEn : page.titleRu}
                        </Link>
                    </li>
                ))}
                {tournament.provideCoupons && <li>
                    <Link to={`/tournament/${tournament.id}/coupon`}
                          className={active === 'coupon' ? 'active' : ''}>
                        {i18next.t('page.tournament.discount')}
                    </Link>
                </li>}
            </ul>
        </nav>
    );
}

export default TournamentNavbarComponent;