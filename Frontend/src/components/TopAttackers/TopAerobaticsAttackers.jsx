import React, {useEffect} from 'react';
import ReactHtmlParser from "react-html-parser";
import i18next from "../../i18n";

const TopAerobaticsAttackers = ({server, tour, header}) => {
    const [pilots, setPilots] = React.useState([]);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/aerobatics-top-attackers?tour=${tour}?limit=10`)
            .then(r => r.json())
            .then(data => {
                if (data) {
                    setPilots(data);
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, [server, tour]);
    return (
        <React.Fragment>
            <div className="stats__block">
                <div className="stats__title-table">
                    {ReactHtmlParser(header)}
                </div>
                <table className="table text-white">
                    <thead>
                    <tr>
                        <td>№</td>
                        <td>{i18next.t('label.callsign')}</td>
                        <td>{i18next.t('label.destroyed')}</td>
                    </tr>
                    </thead>
                    <tbody>
                    {pilots.map((pilot, i) => (
                        <tr key={i}>
                            <td>{i + 1}</td>
                            <td>
                                <div className="table__name">
                                    <span className={`m-2 flag-icon flag-icon-${pilot?.country?.toLowerCase()}`}/>

                                    <a className={'text-decoration-none'}
                                       href={`/pilot/${pilot.id}?server=${server.id}`}>
                                        {pilot.callsign}
                                    </a>
                                </div>
                            </td>
                            <td>{pilot.destroyed}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        </React.Fragment>
    );
}

export default TopAerobaticsAttackers;