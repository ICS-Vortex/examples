import React, {useEffect} from 'react';
import i18next from "../../i18n";
import ReactHtmlParser from "react-html-parser";

const TopPlanesComponent = ({header, server, tour, side}) => {
    const [topPlanes, setTopPlanes] = React.useState([]);
    useEffect(() => {
        if(server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/top-flights/${side}/${tour}`)
                .then(r => r.json())
                .then(data => {
                    setTopPlanes(data);
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
                    <td>{i18next.t('label.hours')}</td>
                    <td>{i18next.t('label.plane')}</td>
                </tr>
                </thead>
                <tbody>
                {topPlanes.map((plane, i) => (
                    <tr key={i}>
                        <td>{i + 1}</td>
                        <td>{plane.totalTime}</td>
                        <td>{plane.image.toUpperCase()}</td>
                    </tr>
                ))}

                </tbody>
            </table>
        </div>
    );
}

export default TopPlanesComponent;