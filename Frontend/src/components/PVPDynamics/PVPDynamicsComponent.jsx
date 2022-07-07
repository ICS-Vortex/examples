import React, {useEffect} from 'react';
import i18next from "../../i18n";
import {Legend, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis} from "recharts";

const PVPDynamicsComponent = ({server, tour}) => {
    const [seasonDogfights, setSeasonDogfights] = React.useState([]);

    useEffect(() => {
        if(server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/dogfights?tour=${tour}`)
                .then(response => response.json())
                .then(data => {
                    setSeasonDogfights(data);
                })
            ;
        }
    }, [server, tour]);
    return (
        <React.Fragment>
            <div className="p-10" style={{height: 400}}>
                <ResponsiveContainer>
                    <LineChart height={300} data={seasonDogfights}>
                        <XAxis dataKey="day"/>
                        <YAxis />
                        <Tooltip contentStyle={{background: '#070C14'}} />
                        <Legend />
                        <Line type="monotone" dataKey="redKills" name={i18next.t('label.axis')} stroke="#e53935" />
                        <Line type="monotone" dataKey="blueKills" name={i18next.t('label.allies')} stroke="#3e98c7" />
                    </LineChart>
                </ResponsiveContainer>
            </div>
        </React.Fragment>
    );
}

export default PVPDynamicsComponent;