import React, {useEffect} from "react";
import {URL_API_SERVERS} from "../../constants/urls";

const KillsPerformanceComponent = ({server, tour}) => {
    const [groundKills, setGroundKills] = React.useState({
        RED: 0,
        BLUE: 0,
        total: 0,
    });
    const [sorties, setSorties] = React.useState({
        RED: 0,
        BLUE: 0,
    });
    const [deaths, setDeaths] = React.useState({
        RED: 0,
        BLUE: 0,
    });

    let sortiesRed = sorties ? sorties.RED : 0;
    let sortiesBlue = sorties ? sorties.BLUE : 0;

    let deathsRed = deaths ? deaths.RED : 0;
    let deathsBlue = deaths ? deaths.BLUE : 0;

    if (sortiesRed === 0) sortiesRed = 1;
    if (sortiesBlue === 0) sortiesBlue = 1;

    if (deathsRed === 0) deathsRed = 1;
    if (deathsBlue === 0) deathsBlue = 1;

    useEffect(() => {
        if (server.id) {
            fetch(URL_API_SERVERS + `/${server.id}/kills-performance?tour=${tour}`)
                .then((r) => r.json())
                .then((data) => {
                    setDeaths(data.deaths);
                    setSorties(data.sorties);
                    setGroundKills(data.kills);
                });
        }
    }, [server, tour]);

    return (
        <div className="stats__row">
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_axis">
                    <div className="ak-gk__title">GK/S</div>
                    <div className="ak-gk__value">{(groundKills?.RED / sortiesRed).toFixed(2)}</div>
                </div>
            </div>
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_axis">
                    <div className="ak-gk__title">GK/D</div>
                    <div className="ak-gk__value">{(groundKills?.RED / deathsRed).toFixed(2)}</div>
                </div>
            </div>
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_allied">
                    <div className="ak-gk__title">GK/S</div>
                    <div className="ak-gk__value">{(groundKills?.BLUE / sortiesBlue).toFixed(2)}</div>
                </div>
            </div>
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_allied">
                    <div className="ak-gk__title">GK/D</div>
                    <div className="ak-gk__value">{(groundKills?.BLUE / deathsBlue).toFixed(2)}</div>
                </div>
            </div>
        </div>
    );
};

export default KillsPerformanceComponent;
