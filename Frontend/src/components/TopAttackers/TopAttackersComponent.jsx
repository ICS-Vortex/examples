import React, {useEffect} from 'react';
import i18next from "../../i18n";
import ReactHtmlParser from "react-html-parser";

const TopAttackersComponent = ({server, header, tour, side}) => {
    const [assaults, setAssaults] = React.useState([]);

    useEffect(() => {
        if (server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/top-attackers/${side}/${tour}`)
                .then(r => r.json())
                .then(data => {
                    setAssaults(data);
                })
            ;
        }
    }, [server, tour]);
    return (
        <div className="stats__block">
            <div className="stats__title-table">
                {ReactHtmlParser(header)}
            </div>
            <table className="table text-white table-responsive">
                <thead>
                <tr>
                    <td>№</td>
                    <td>{i18next.t('label.callsign')}</td>
                    <td>{i18next.t('label.score')}</td>
                </tr>
                </thead>
                <tbody>

                {assaults.map((attacker, i) => (
                    <tr key={i}>
                        <td>{i + 1}</td>
                        <td>
                            <span className={`flag-icon flag-icon-${attacker?.country?.toLowerCase()}`}/>

                            <a className={`text-${side?.toLowerCase()} text-decoration-none`}
                               href={`/pilot/${attacker.id}?server=${server.id}`}>
                                {attacker.username}
                            </a>
                        </td>
                        <td>{attacker.points}</td>
                    </tr>
                ))}

                </tbody>
            </table>
        </div>
    );
}

export default TopAttackersComponent;