import React from 'react';
import i18next from "i18next";
import {Link} from 'react-router-dom';

const ServerNavbarComponent = (props) => {
    return (
        <nav className="main__menu">
            <ul>
                <li>
                    <Link to={`/server/${props.server.id}/home`}
                          className={props.active === 'home' ? 'active' : ''}>{i18next.t('page.home')}</Link>
                </li>
                <li>
                    <Link to={`/server/${props.server.id}/info`}
                          className={props.active === 'info' ? 'active' : ''}>{i18next.t('page.info')}</Link>
                </li>
                <li>
                    <Link to={`/server/${props.server.id}/stats`}
                          className={props.active === 'stats' ? 'active' : ''}>{i18next.t('page.stats')}</Link>
                </li>
                <li>
                    <Link to={`/server/${props.server.id}/missions`}
                          className={props.active === 'missions' ? 'active' : ''}>{i18next.t('page.missions')}</Link>
                </li>
                {!props.server.isAerobatics && <li>
                    <Link to={`/server/${props.server.id}/fighters`}
                          className={props.active === 'fighters' ? 'active' : ''}>{i18next.t('page.fighters')}</Link>
                </li>}
                {!props.server.isAerobatics && <li>
                    <Link to={`/server/${props.server.id}/attackers`}
                          className={props.active === 'attackers' ? 'active' : ''}>{i18next.t('page.attackers')}</Link>
                </li>}
                <li>
                    <Link to={`/server/${props.server.id}/faq`}
                          className={props.active === 'faq' ? 'active' : ''}>{i18next.t('page.faq')}</Link>
                </li>
                {props.server.showBanList && <li>
                    <Link to={`/server/${props.server.id}/banlist`}
                          className={props.active === 'banlist' ? 'active' : ''}>{i18next.t('page.banlist')}</Link>
                </li>}
            </ul>
        </nav>
    );
}

export default ServerNavbarComponent;