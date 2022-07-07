import React, {useEffect} from 'react';
import ReactHtmlParser from "react-html-parser";
import i18next from "../../../i18n";

const TopAerobaticsPlanes = ({server, tour, header}) => {
    const [planes, setPlanes] = React.useState([]);
    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/aebobatics-top-planes?tour=${tour}?limit=10`)
            .then(r => r.json())
            .then(data => {
                if (data) {
                    setPlanes(data);
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
                        <td>{i18next.t('label.aircraft')}</td>
                        <td>{i18next.t('label.hours')}</td>
                    </tr>
                    </thead>
                    <tbody>
                    {planes.map((plane, i) => (
                        <tr key={i}>
                            <td>{i + 1}</td>
                            <td>
                                <div className="table__name">
                                    <a href="#">{plane.plane}</a>
                                </div>
                            </td>
                            <td>{plane.hours}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        </React.Fragment>
    );
}

export default TopAerobaticsPlanes;