import React, {useEffect} from 'react';
import {CartesianGrid, Legend, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis,} from 'recharts';
import {URL_API_PILOTS} from "../../constants/urls";
import i18next from '../../i18n';
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../constants/languages";

const PilotAirKillsChartComponent = ({serverId, tourId, pilot}) => {
    const [data, setData] = React.useState([]);
    useEffect(() => {
        const url = URL_API_PILOTS + `/${pilot?.id}/airkills?server=${serverId}&tour=${tourId}`;
        fetch(url)
            .then(r => r.json())
            .then(response => {
                setData(response);
            })
        ;
    }, [serverId, tourId]);

    return (
        <React.Fragment>
            <div>
                {i18next.t('label.blue_red_airkills')}
            </div>
            <ResponsiveContainer width='100%' aspect={4.0 / 3.0}>
                <LineChart data={data}>
                    <CartesianGrid strokeDasharray="3 3"/>
                    {i18next.language === LANGUAGE_ENGLISH && <XAxis dataKey="titleEn"/>}
                    {i18next.language === LANGUAGE_RUSSIAN && <XAxis dataKey="title"/>}
                    <YAxis/>
                    <Tooltip/>
                    <Legend/>
                    <Line type="monotone" dataKey="redAirKills" stroke="#e53935" activeDot={{r: 6}}/>
                    <Line type="monotone" dataKey="blueAirKills" stroke="#3e98c7"/>
                </LineChart>
            </ResponsiveContainer>

        </React.Fragment>
    );
}

export default PilotAirKillsChartComponent;