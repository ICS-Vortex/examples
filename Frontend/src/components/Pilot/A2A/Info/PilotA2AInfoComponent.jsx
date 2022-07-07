import React, {useEffect} from 'react';
import i18next from "../../../../i18n";

const PilotA2AInfoComponent = ({pilot, server}) => {
    const [planesInfo, setPlanesInfo] = React.useState([]);
    useEffect(() => {
        if (server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot.id}/a2a-by-planes?server=${server?.id}`)
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
            <div className="battle-stats__title">A2A</div>
            <div className="pilot-rating pilot-rating_in">
                <div className="pilot-rating__table pilot-rating__table_p0">
                    <div className="c-table">
                        <div className="b-table">
                            <table className="rating-table rating-table_center not-hover">
                                <thead>
                                <tr>
                                    <td>{i18next.t('label.aircraft')}</td>
                                    <td>
                                        <img src="/images/icons/crosshairs.png" title={i18next.t('label.air_battles')}
                                             alt={i18next.t('label.air_battles')}/>
                                    </td>
                                    <td>
                                        <img src="/images/icons/star.png" title={i18next.t('label.air_wins')}
                                             alt={i18next.t('label.air_wins')}/>
                                    </td>
                                    <td>
                                        <img src="/images/icons/fire.png" title={i18next.t('label.ground_dogfights')}
                                             alt={i18next.t('label.ground_dogfights')}/>
                                    </td>
                                    <td>
                                        <img src="/images/icons/star.png" title={i18next.t('label.elo')}
                                             alt={i18next.t('label.elo')}/>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                {planesInfo.map(row => (
                                    <tr key={row.id}>
                                        <td>{row.plane}</td>
                                        <td>{row.airLoses + row.airWins + row.groundKills}</td>
                                        <td>{row.airWins}</td>
                                        <td>{row.groundKills}</td>
                                        <td>{row.elo}</td>
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