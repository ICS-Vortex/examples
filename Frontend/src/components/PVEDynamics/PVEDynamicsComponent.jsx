import React, {useEffect} from 'react';
import i18next from "../../i18n";
import {Legend, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis} from "recharts";

const PVEDynamicsComponent = ({server, tour}) => {
    const [seasonKills, setSeasonKills] = React.useState([]);

    useEffect(() => {
        if (server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/kills?tour=${tour}`)
                .then(response => response.json())
                .then(data => {
                    setSeasonKills(data);
                })
            ;
        }
    }, [server, tour]);

    const labelRed = server.isAerobatics ? i18next.t('label.redTeam') : i18next.t('label.axis');
    const labelBlue = server.isAerobatics ? i18next.t('label.blueTeam') : i18next.t('label.allies');
    return (
        <React.Fragment>
            <div style={{height: '300px'}}>
                <ResponsiveContainer>
                    <LineChart data={seasonKills}>
                        <XAxis dataKey="day"/>
                        <YAxis/>
                        <Tooltip contentStyle={{background: '#070C14'}}/>
                        <Legend/>
                        <Line type="monotone" dataKey="redKills" name={labelRed} stroke="#e53935"/>
                        <Line type="monotone" dataKey="blueKills" name={labelBlue} stroke="#3e98c7"/>
                    </LineChart>
                </ResponsiveContainer>
            </div>

        </React.Fragment>
    );
}

export default PVEDynamicsComponent;