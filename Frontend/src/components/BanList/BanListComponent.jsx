import React, {useEffect} from 'react';
import i18next from '../../i18n';

const BanListComponent = (props) => {
    const [banList, setBanList] = React.useState([]);
    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${props.server.id}}/banlist`)
            .then(r => r.json())
            .then(data => {
                setBanList(data);
            })
    }, [props.server]);

    return (
        <React.Fragment>
            <div className="stats__block">
                <div className="stats__title-table">
                    {i18next.t('label.banlist')}
                </div>
                <table className="table text-white">
                    <thead>
                    <tr>
                        <td>{i18next.t('label.callsign')}</td>
                        <td>{i18next.t('label.from')}</td>
                        <td>{i18next.t('label.till')}</td>
                        <td>{i18next.t('label.reason')}</td>
                    </tr>
                    </thead>
                    <tbody>

                    {banList.map((banned, i) => (
                        <tr key={i}>
                            <td>{banned.callsign}</td>
                            <td>{banned.from}</td>
                            <td>{banned.until}</td>
                            <td>{banned.reason}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        </React.Fragment>
    );
}

export default BanListComponent;