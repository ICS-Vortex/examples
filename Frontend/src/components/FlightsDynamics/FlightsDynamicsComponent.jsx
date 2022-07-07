import React, {useEffect} from 'react';
import i18next from "../../i18n";
import {
    Legend,
    Line,
    LineChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis
} from "recharts";

const FlightsDynamicsComponent = ({server, tour}) => {
    const [flights, setFlights] = React.useState([]);

    useEffect(() => {
        if(server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/flights-data/${tour}`)
                .then(response => response.json())
                .then(data => {
                    setFlights(data);
                })
            ;
        }
    }, [server, tour]);

    return (
        <div className="stats__block">
            <div className="stats__title">{i18next.t('label.flights_dynamics')}</div>
            <div className="graph">
                <div className="p-10" style={{height: '300px'}}>
                    <ResponsiveContainer>
                        <LineChart width={500} height={300} data={flights}>
                            <XAxis dataKey="day"/>
                            <YAxis/>
                            <Tooltip contentStyle={{background: '#070C14'}} />
                            <Legend/>
                            <Line type="monotone" dataKey="emergencyFlights" name={i18next.t('label.accidents')} stroke="#e53935"/>
                            <Line type="monotone" dataKey="flights" name={i18next.t('label.flights')} stroke="#3e98c7"/>
                        </LineChart>
                    </ResponsiveContainer>
                </div>
            </div>
        </div>
    );
}

export default FlightsDynamicsComponent;