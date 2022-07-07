import React, {useEffect} from 'react';
import i18n from "../../i18n";
import moment from "moment";

const RacingDataComponent = ({plane, tournament}) => {
    const [data, setData] = React.useState([]);

    const formatTime = (seconds) => {
        return moment.utc(seconds * 1000).format('mm:ss.SS');
    };

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/racing/plane-data/${plane.id}?tournament=${tournament?.id}`)
            .then(r => r.json())
            .then(response => {
                setData(response);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    return <React.Fragment>
        <div className="stats__block">
            <div className="stats__title-table">{i18n.t('label.top_ten_pilots')} ({plane.name})</div>
            <table className="table text-white w-100">
                <thead>
                <tr>
                    <td>#</td>
                    <td>{i18n.t('label.callsign')}</td>
                    <td>{i18n.t('label.time')}</td>
                </tr>
                </thead>
                <tbody>
                {data.map((row, i) => (
                    <tr key={i}>
                        <td>{i + 1}</td>
                        <td>
                            <span className={`mr-2 flag-icon flag-icon-${row.country ? row.country : row.ipCountry}`}/>
                            {row.callsign}
                        </td>
                        <td>{formatTime(row.time)}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    </React.Fragment>;
};

export default RacingDataComponent;
