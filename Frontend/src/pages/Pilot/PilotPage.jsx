import React, {useEffect} from "react";
import {NumberParam, useQueryParam} from 'use-query-params';
import i18next from "i18next";
import moment from 'moment';
import ServerHeaderComponent from "../../components/Navbar/ServerHeaderComponent";
import PilotGeneralInfoComponent from "../../components/Pilot/General/Info/PilotGeneralInfoComponent";
import PilotA2AInfoComponent from "../../components/Pilot/A2A/Info/PilotA2AInfoComponent";
import PilotA2GInfoComponent from "../../components/Pilot/A2G/Info/PilotA2GInfoComponent";
import PilotFlightDataComponent from "../../components/Pilot/FlightData/PilotFlightDataComponent";
import {Link} from 'react-router-dom';

const PilotPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [serverId] = useQueryParam('server', NumberParam);
    const [server, setServer] = React.useState({});
    const [tourId] = useQueryParam('tour', NumberParam);
    const [pilot, setPilot] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${serverId}`)
            .then(r => r.json())
            .then(data => {
                if (data.server) {
                    setServer(data.server);
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, [serverId]);


    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/pilots/${id}?server=${serverId}&tour=${tourId}`)
            .then(response => response.json())
            .then(data => {
                if (data.pilot) {
                    setPilot(data.pilot);
                }
            }).finally(() => {
        })
        ;
    }, [serverId]);

    return (
        <main className="main">
            <div className="content">
                {server?.id && <ServerHeaderComponent server={server}/>}
                {pilot?.id && <div className="main__content">
                    <div className="pilot">
                        <div className="pilot__head pilot-head">
                            <div className="pilot-head__flag">
                                <span
                                    className={`flag-icon flag-icon-${pilot.country ? pilot.country.toLowerCase() : pilot.ipCountry?.toLowerCase()}`}/>
                            </div>
                            <div className="pilot-head__name">{pilot.username}</div>
                            {pilot.online && <div
                                className="single-server__status single-server__status_online text-uppercase">
                                {i18next.t('status.online')}
                            </div>}
                            {!pilot.online && <div
                                className="single-server__status single-server__status_offline text-uppercase">
                                {i18next.t('status.offline')}
                            </div>}
                            {/*<div className="pilot-head__rank">*/}
                            {/*    <img src="images/table-rating/shevron.png" alt=""/> Ефрейтер*/}
                            {/*</div>*/}
                        </div>
                        <div className="pilot__aboot about-pilot">
                            <div className="about-pilot__avatar">
                                {pilot.showPhotoAtWeb &&
                                <img src={`${process.env.REACT_APP_API_HOST}/uploads/avatars/${pilot?.photo}`} alt=""/>}
                                {!pilot.showPhotoAtWeb &&
                                <img src={`${process.env.REACT_APP_API_HOST}/uploads/avatars/${pilot?.avatar}`}
                                     alt=""/>}
                            </div>
                            <div className="about-pilot__content">
                                <div className="about-pilot__col">
                                    <div className="about-pilot__info">
                                        <strong>{i18next.t('label.name')}: </strong> {pilot?.name}
                                    </div>
                                    <div className="about-pilot__info">
                                        <strong>{i18next.t('label.age')}: </strong>
                                        {pilot.birthday && Math.floor(moment(new Date()).diff(moment(pilot.birthday).format('YYYY-MM-DD'), 'years', true))}
                                        {!pilot.birthday && '---'}
                                    </div>
                                    <div className="about-pilot__info">
                                        <strong>{i18next.t('label.country')}: </strong> <span
                                        className={`flag-icon flag-icon-${pilot.country}`}/>
                                    </div>
                                </div>
                                <div className="about-pilot__col">
                                    <div className="about-pilot__info about-pilot__info_callsing">
                                        <strong>{i18next.t('label.callsign')}: </strong>
                                        <Link to="#" target="_blank">{pilot.username}</Link>
                                    </div>
                                    <div className="about-pilot__info about-pilot__info_youtube d-block">
                                        <strong>{i18next.t('label.youtube')}: </strong>
                                        <Link className="text-break text-decoration-none"
                                              to={pilot.youtubeChannelUrl}
                                              target="_blank">
                                            {pilot.username}
                                        </Link>
                                    </div>
                                    <div className="about-pilot__info about-pilot__info_twitch">
                                        <strong>{i18next.t('label.twitch')}: </strong>
                                        <Link className="text-break text-decoration-none"
                                              to={pilot.twitchChannelUrl}
                                              target="_blank">
                                            {pilot.username}
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {server?.id && <div className="pilot__stats pilot-stats">
                            <div className="pilot-stats__content">
                                <div className="pilot-stats__item active tub-1">
                                    <div className="pilot-rating pilot-rating_in">
                                        <div className="pilot-rating__table pilot-rating__table_p0">
                                            <div className="c-table">
                                                <div className="b-table">
                                                    {server?.id &&
                                                    <PilotGeneralInfoComponent server={server} pilot={pilot}/>}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="pilot-stats__item tub-2">
                                    Tour Stats
                                </div>
                                <div className="pilot-stats__item tub-3">
                                    History
                                </div>
                                <div className="pilot-stats__item tub-4">
                                    Flight Log
                                </div>
                                <div className="pilot-stats__item tub-5">
                                    Kill Board
                                </div>
                                <div className="pilot-stats__item tub-6">
                                    Rewards
                                </div>
                            </div>
                        </div>}
                        {server.id && !server.isAerobatics && <div className="pilot__battle battle-stats">
                            <div className="title">{i18next.t('label.battle_stats')}</div>
                            <div className="battle-stats__row">
                                <div className="battle-stats__col">
                                    <PilotA2AInfoComponent pilot={pilot} server={server}/>
                                </div>
                                <div className="battle-stats__col">
                                    <PilotA2GInfoComponent pilot={pilot} server={server}/>
                                </div>
                            </div>
                        </div>}
                        {server.id && <div className="pilot__battle battle-stats">
                            <div className="title">{i18next.t('label.flight_stats')}</div>
                            <div className="battle-stats__row">
                                <div className="battle-stats__col">
                                    <PilotFlightDataComponent pilot={pilot} server={server}/>
                                </div>
                            </div>
                        </div>}
                    </div>
                </div>}
            </div>
        </main>
    );
}

export default PilotPage;