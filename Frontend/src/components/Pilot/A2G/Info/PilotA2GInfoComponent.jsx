import React, {useEffect} from 'react';
import i18next from "../../../../i18n";

const PilotA2AInfoComponent = ({pilot, server}) => {
    const [planesInfo, setPlanesInfo] = React.useState([]);
    useEffect(() => {
        if (server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot.id}/a2g-by-planes?server=${server?.id}`)
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
            <div className="battle-stats__title">A2G</div>
            <div className="pilot-rating pilot-rating_in">
                <div className="pilot-rating__table pilot-rating__table_p0">
                    <div className="c-table">
                        <div className="b-table">
                            <table className="rating-table rating-table_center not-hover">
                                <thead>
                                <tr>
                                    <td>{i18next.t('label.aircraft')}</td>
                                    <td>
                                        <img src="/images/icons/crosshairs.png" title={i18next.t('label.ai_kills')}
                                             alt={i18next.t('label.ai_kills')}/>
                                    </td>
                                    <td><i title={i18next.t('label.groundKills')} className="fa fa-car-crash"/>
                                    </td>
                                    <td><i title={i18next.t('label.seaKills')} className="fa fa-ship"/></td>
                                    <td><i title={i18next.t('label.score')} className="fa fa-trophy"/></td>

                                </tr>
                                </thead>
                                <tbody>
                                {planesInfo.map(row => (
                                    <tr key={row.id}>
                                        <td>{row.plane}</td>
                                        <td>{row.aiKilled}</td>
                                        <td>{row.groundKills}</td>
                                        <td>{row.seaKills}</td>
                                        <td>{row.points}</td>
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