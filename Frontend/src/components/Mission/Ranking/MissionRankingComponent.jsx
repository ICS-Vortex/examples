import React, {useEffect} from 'react';
import i18next from "i18next";

const MissionRankingComponent = ({mission, server}) => {
    const [ranking, setRanking] = React.useState([]);
    useEffect(() => {
        if (mission.id) {
            // console.log(mission.id)
            fetch(process.env.REACT_APP_API_HOST + `/api/open/missions/${mission.id}/ranking`)
                .then(r => r.json())
                .then(data => {
                    if (data) {
                        setRanking(data);
                    }
                })
                .catch(e => {
                    // console.log(e.message);
                })
            ;
        }
    }, [mission]);

    return (
        <table className="rating-table">
            <thead>
            <tr>
                <td title={i18next.t('label.rankingen')}>№</td>
                <td title={i18next.t('label.country')}/>
                <td title={i18next.t('label.callsign')}>{i18next.t('label.callsign')}</td>
                <td title={i18next.t('label.flight_time')}>
                    <img src="/images/icons/clock.png" title={i18next.t('label.flight_time')}
                         alt={i18next.t('label.flight_time')}/>
                </td>
                <td title={i18next.t('label.sorties')}>
                    <img src="/images/icons/takeoff.png" title={i18next.t('label.sorties')}
                         alt={i18next.t('label.sorties')}/>
                </td>
                <td title={i18next.t('label.air_battles')}>
                    <img src="/images/icons/crosshairs.png" title={i18next.t('label.air_battles')}
                         alt={i18next.t('label.air_battles')}/>
                </td>
                <td title={i18next.t('label.air_wins')}>
                    <img src="/images/icons/star.png" title={i18next.t('label.air_wins')}
                         alt={i18next.t('label.air_wins')}/>
                </td>
                <td title={i18next.t('label.ejected')}>
                    <img src="/images/icons/question.png" title={i18next.t('label.ejected')}
                         alt={i18next.t('label.ejected')}/>
                </td>
                <td title={i18next.t('label.crashed')}>
                    <img src="/images/icons/fire.png" title={i18next.t('label.crashed')}
                         alt={i18next.t('label.crashed')}/>
                </td>
                <td title={i18next.t('label.landed')}>
                    <img src="/images/icons/landing.png" title={i18next.t('label.landings')}
                         alt={i18next.t('label.landings')}/>
                </td>
                <td title={i18next.t('label.disconnects')}>
                    <img src="/images/icons/disconnect.png" title={i18next.t('label.disconnects')}
                         alt={i18next.t('label.disconnects')}/>
                </td>
            </tr>
            </thead>
            <tbody>
            {ranking.map((pilot, i) => (
                <tr key={i}>
                    <td>{i + 1}</td>
                    <td>
                        <div className="rating-table__flag">
                            <span className={`flag-icon flag-icon-${pilot.country.toLowerCase()}`}/>
                        </div>
                    </td>
                    <td>
                        <div className="rating-table__name">
                            <a className="text-decoration-none"
                               href={`/pilot/${pilot.id}?server=${server.id}`}>{pilot.callsign}</a>
                        </div>
                    </td>
                    <td>{pilot.flightTime}</td>
                    <td>{pilot.flights}</td>
                    <td>{pilot.airBattles}</td>
                    <td>{pilot.airWins}</td>
                    <td>{pilot.ejected}</td>
                    <td>{pilot.crashed}</td>
                    <td>{pilot.landed}</td>
                    <td>{pilot.disconnected}</td>
                </tr>
            ))}
            </tbody>
        </table>
    );
}

export default MissionRankingComponent;