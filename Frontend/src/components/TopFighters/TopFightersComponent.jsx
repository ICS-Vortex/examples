import React, {useEffect} from 'react';
import i18next from "../../i18n";
import {URL_API_SERVERS} from "../../constants/urls";
import ReactHtmlParser from "react-html-parser";

const TopFightersComponent = ({server, header, tour, side}) => {
    const [fighters, setFighters] = React.useState([]);
    useEffect(() => {
        if (server.id    && side) {
            const url = URL_API_SERVERS + `/${server.id}/top-fighters?side=${side}&tour=${tour}`;
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    setFighters(data);
                })
            ;
        }
    }, [server, side, tour]);

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
                    <td>{i18next.t('label.ranking')}</td>
                </tr>
                </thead>
                <tbody>
                {fighters.map((pilot, i) => (
                    <tr key={i}>
                        <td>{i + 1}</td>
                        <td>
                            <div className="table__name">
                                <span className={`flag-icon flag-icon-${pilot?.country?.toLowerCase()}`}/>
                                <a className={`text-${side?.toLowerCase()} text-decoration-none`}
                                   href={`/pilot/${pilot.id}?server=${server.id}`}>
                                    {pilot.callsign}
                                </a>
                            </div>
                        </td>
                        <td>{pilot.rating}</td>
                    </tr>
                ))}

                </tbody>
            </table>
        </div>
    );
}

export default TopFightersComponent;