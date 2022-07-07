import React, {useEffect} from 'react';
import i18next from "i18next";

const PilotGeneralInfoComponent = ({server, pilot}) => {
    const [info, setInfo] = React.useState({});
    useEffect(() => {
        if (server?.id && pilot?.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot?.id}/general?server=${server?.id}`)
                .then(r => r.json())
                .then(data => setInfo(data))
            ;
        }
    }, []);

    return (
        <React.Fragment>
            <table className="rating-table rating-table_center not-hover">
                <thead>
                <tr>
                    <td>
                        <img src="/images/icons/clock.png" title={i18next.t('label.flight_time')}
                             alt={i18next.t('label.flight_time')}/>
                    </td>
                    <td>
                        <img src="/images/icons/star-red.svg" title={i18next.t('label.red_elo')}
                             alt={i18next.t('label.red_elo')}/>
                    </td>
                    <td>
                        <img src="/images/icons/star-blue.svg" title={i18next.t('label.blue_elo')}
                             alt={i18next.t('label.blue_elo')}/>
                    </td>
                    <td>
                        <img src="/images/icons/star.png" title={i18next.t('label.air_wins')}
                             alt={i18next.t('label.air_wins')}/>
                    </td>
                    <td><i title={i18next.t('label.best_air_streak')} className="fa fa-trophy"/></td>
                    <td><i title={i18next.t('label.ground_score')} className="fa fa-truck"/></td>
                    <td><i title={i18next.t('label.died')} className="fa fa-briefcase-medical"/></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{info.hours}</td>
                    <td>{info.redElo}</td>
                    <td>{info.blueElo}</td>
                    <td>{info.killed}</td>
                    <td>{info.bestAirStreak}</td>
                    <td>{info.groundPoints}</td>
                    <td>{info.died}</td>
                </tr>
                </tbody>
            </table>
        </React.Fragment>
    );
}

export default PilotGeneralInfoComponent;