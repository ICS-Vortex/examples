import React, {useEffect} from 'react';
import i18next from '../../i18n';

const PilotSortiesComponent = ({pilot, server, tour}) => {
    const [sorties, setSorties] = React.useState([]);
    const url = process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot.id}/sorties`;

    const getSortieStatusIcon = (sortie) => {
        switch (sortie.status) {
            case 'airfield':
            case 'field':
                return <div title={i18next.t('label.landed')}></div>;
            case 'death':
                return <div title={i18next.t('label.died')}></div>;
            case 'crash':
                return <div title={i18next.t('label.crashed')}></div>;
            default:
                return <div title={i18next.t('label.undefined')}></div>;
        }
    }

    useEffect(() => {
        fetch(url)
            .then(r => r.json())
            .then(data => setSorties(data))
        ;
    }, [pilot]);

    return (
        <React.Fragment>
            <div className="battle-stats__title">{i18next.t('label.flights')}</div>
            <div className="pilot-rating pilot-rating_in">
                <div className="pilot-rating__table pilot-rating__table_p0">
                    <div className="c-table">
                        <div className="b-table">
                            <table className="rating-table rating-table_center not-hover">
                                <thead>
                                <tr>
                                    <td>{i18next.t('label.server')}</td>
                                    <td>{i18next.t('label.start_time')}</td>
                                    <td>{i18next.t('label.takeoff_airfield')}</td>
                                    <td>{i18next.t('label.plane')}</td>
                                    <td>{i18next.t('label.landing_airfield')}</td>
                                    <td>{i18next.t('label.status')}</td>
                                    <td>{i18next.t('label.duration')}</td>
                                </tr>
                                </thead>
                                <tbody>
                                {sorties.map((row, i) => (
                                    <tr key={i}>
                                        <td>{row.server}</td>
                                        <td>{row.startedAt}</td>
                                        <td>{row.takeoffFrom}</td>
                                        <td>{row.plane}</td>
                                        <td>{row.landedAt}</td>
                                        <td>{row.status}</td>
                                        <td>{row.duration}</td>
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

export default PilotSortiesComponent;