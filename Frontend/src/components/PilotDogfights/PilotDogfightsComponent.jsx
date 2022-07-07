import React, {useEffect} from 'react'
import i18next from '../../i18n';
import moment from 'moment';

export const PilotDogfightsComponent = ({pilot, server, tour}) => {
    const [dogfights, setDogfights] = React.useState([]);
    const url = process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot.id}/dogfights`;

    useEffect(() => {
        fetch(url)
            .then(r => r.json())
            .then(data => setDogfights(data))
        ;
    }, [pilot]);

    return (
        <React.Fragment>
            <div className="battle-stats__title">{i18next.t('label.dogfights')}</div>
            <div className="pilot-rating pilot-rating_in">
                <div className="pilot-rating__table pilot-rating__table_p0">
                    <div className="c-table">
                        <div className="b-table">
                            <table className="rating-table rating-table_center not-hover">
                                <thead>
                                <tr>
                                    <td>{i18next.t('label.server')}</td>
                                    <td>{i18next.t('label.winner')}</td>
                                    <td>{i18next.t('label.plane')}</td>
                                    <td>{i18next.t('label.loser')}</td>
                                    <td>{i18next.t('label.plane')}</td>
                                    <td>{i18next.t('label.time')}</td>
                                </tr>
                                </thead>
                                <tbody>
                                {dogfights.length > 0 && dogfights.map((row, i) => (
                                    <tr key={i}>
                                        <td>{row.serverName}</td>
                                        <td><a
                                            className={`text-${row.pilotSide?.toLowerCase()}`}>{row.pilotCallsign}</a>
                                        </td>
                                        <td>{row.pilotPlane?.toUpperCase()}</td>
                                        <td><a href="#"
                                               className={`text-${row.victimSide?.toLowerCase()}`}>{row.victimCallsign}</a>
                                        </td>
                                        <td>{row.victimPlane?.toUpperCase()}</td>
                                        <td>{moment(row.killTime).format('DD.MM.YYYY, HH:MM')}</td>
                                    </tr>
                                ))}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </React.Fragment>
    )
}
