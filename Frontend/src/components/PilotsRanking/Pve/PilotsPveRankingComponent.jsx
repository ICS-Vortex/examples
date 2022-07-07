import React, {useEffect} from 'react';
import {URL_API_SERVERS} from "../../../constants/urls";
import i18next from "../../../i18n";
import history from "../../../history";


const PilotsPveRankingComponent = ({server, tour}) => {
    const [pilots, setPilots] = React.useState([]);

    useEffect(() => {
        if (server.id) {
            const url = URL_API_SERVERS + `/${server.id}/pilots-pve-ranking?tour=${tour}`;
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    setPilots(data);
                })
            ;
        }
    }, [server, tour]);

    const handleClick = (pilot) => {
        history.push(`/pilot/${pilot.id}?server=${server.id}`, {server: server});
    };


    return <React.Fragment>
        <div className="pilot-rating">
            <div className="pilot-rating__title">{i18next.t('label.attackers_ranking')}</div>
            <div className="pilot-rating__table">
                <div className="c-table">
                    <div className="b-table">
                        <table className="rating-table">
                            <thead>
                            <tr>
                                <td>№</td>
                                <td/>
                                <td>{i18next.t('label.callsign')}</td>
                                <td>
                                    <img src="/images/icons/clock.png" title={i18next.t('label.flight_time')}
                                         alt={i18next.t('label.flight_time')}/>
                                </td>
                                <td>
                                    <img src="/images/icons/takeoff.png" title={i18next.t('label.takeoffs')}
                                         alt={i18next.t('label.takeoffs')}/>
                                </td>
                                <td>
                                    <img src="/images/icons/landing.png" title={i18next.t('label.landings')}
                                         alt={i18next.t('label.landings')}/>
                                </td>
                                <td>
                                    <img src="/images/icons/crosshairs.png" title={i18next.t('label.ai_kills')}
                                         alt={i18next.t('label.ai_kills')}/>
                                </td>
                                <td><i title={i18next.t('label.groundKills')} className="fa fa-car-crash"/></td>
                                <td><i title={i18next.t('label.seaKills')} className="fa fa-ship"/></td>
                                <td><i title={i18next.t('label.died')} className="fa fa-briefcase-medical"/></td>
                                <td><i title={i18next.t('label.score')} className="fa fa-trophy"/></td>
                            </tr>
                            </thead>
                            <tbody>
                            {pilots.map((pilot, i) => (
                                <tr key={i} onClick={() => handleClick(pilot)}>
                                    <td>{i + 1}</td>
                                    <td>
                                        <span className={`flag-icon flag-icon-${pilot?.country?.toLowerCase()}`}/>
                                    </td>
                                    <td>
                                        <a className="text-decoration-none"
                                           href={`/pilot/${pilot.id}?server=${server.id}`}>{pilot.callsign}</a>
                                    </td>
                                    <td>{pilot.flightTime}</td>
                                    <td>{pilot.takeoffs}</td>
                                    <td>{pilot.landings}</td>
                                    <td>{pilot.aiKills}</td>
                                    <td>{pilot.groundKills}</td>
                                    <td>{pilot.seaKills}</td>
                                    <td>{pilot.died}</td>
                                    <td className={pilot.bestPointsParam === 'RED' ? 'bg-danger' : 'bg-primary'}>{pilot.bestPoints}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </React.Fragment>;
}

export default PilotsPveRankingComponent;