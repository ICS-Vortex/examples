import React, {useEffect} from 'react';
import i18next from "../../../i18n";

const PilotA2AInfoComponent = ({pilot, server}) => {
    const [planesInfo, setPlanesInfo] = React.useState([]);

    useEffect(() => {
        if (server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot.id}/flight-data-by-planes?server=${server?.id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.length > 0) {
                        setPlanesInfo(data);
                    }
                })
                .catch(e => {
                    // console.log(e.message);
                    setPlanesInfo([]);
                })
            ;
        }
    }, [server]);

    return (
        <React.Fragment>
            <div className="battle-stats__title">{i18next.t('label.flight_stats')}</div>
            <div className="pilot-rating pilot-rating_in">
                <div className="pilot-rating__table pilot-rating__table_p0">
                    <div className="c-table">
                        <div className="b-table">
                            <table className="rating-table rating-table_center not-hover">
                                <thead>
                                <tr>
                                    <td>{i18next.t('label.aircraft')}</td>
                                    <td>
                                        <img src="/images/icons/clock.png" title={i18next.t('label.flight_time')}
                                             alt={i18next.t('label.flight_time')}/>
                                    </td>
                                    <td>
                                        <img src="/images/icons/takeoff.png" title={i18next.t('label.sorties')}
                                             alt={i18next.t('label.sorties')}/>
                                    </td>
                                    <td>
                                        <img src="/images/icons/landing.png" title={i18next.t('label.landings')}
                                             alt={i18next.t('label.landings')}/>
                                    </td>
                                    <td><i title={i18next.t('label.died')} className="fa fa-briefcase-medical"/>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                {planesInfo.map(row => (
                                    <tr key={row.id}>
                                        <td>{row.plane}</td>
                                        <td>{row.totalTime}</td>
                                        <td>{row.sorties}</td>
                                        <td>{row.landings}</td>
                                        <td>{row.died}</td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </React.Fragment>
    );
}

export default PilotA2AInfoComponent;