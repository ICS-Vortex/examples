import React, {useEffect} from 'react';
import {URL_API_SERVERS} from "../../constants/urls";

const DogfightsPerformanceComponent = ({server, tour}) => {
    const [dogfights, setDogfights] = React.useState<KillsInfo>({RED: 0, BLUE: 0, total: 0});
    const [sorties, setSorties] = React.useState<SortiesInfo>({RED: 0, BLUE: 0});
    const [deaths, setDeaths] = React.useState<DeathsInfo>({RED: 0, BLUE: 0});

    let sortiesRed = sorties ? sorties.RED : 0;
    let sortiesBlue = sorties ? sorties.BLUE : 0;

    let deathsRed = deaths ? deaths.RED : 0;
    let deathsBlue = deaths ? deaths.BLUE : 0;

    if (sortiesRed === 0) sortiesRed = 1;
    if (sortiesBlue === 0) sortiesBlue = 1;

    if (deathsRed === 0) deathsRed = 1;
    if (deathsBlue === 0) deathsBlue = 1;

    useEffect(() => {
        if (server.id !== undefined) {
            fetch(URL_API_SERVERS + `/${server.id}/dogfights-performance?tour=${tour}`)
                .then(r => r.json())
                .then(data => {
                    setDeaths(data.deaths);
                    setSorties(data.sorties);
                    setDogfights(data.dogfights);
                })
        }
    }, [server, tour]);

    return (
        <div className="stats__row">
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_axis">
                    <div className="ak-gk__title">AK/S</div>
                    <div className="ak-gk__value">{(dogfights?.RED / sortiesRed).toFixed(2)}</div>
                </div>
            </div>
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_axis">
                    <div className="ak-gk__title">AK/D</div>
                    <div className="ak-gk__value">{(dogfights?.RED / deathsRed).toFixed(2)}</div>
                </div>
            </div>
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_allied">
                    <div className="ak-gk__title">AK/S</div>
                    <div className="ak-gk__value">{(dogfights?.BLUE / sortiesBlue).toFixed(2)}</div>
                </div>
            </div>
            <div className="stats__col">
                <div className="stats__block ak-gk ak-gk_allied">
                    <div className="ak-gk__title">AK/D</div>
                    <div className="ak-gk__value">{(dogfights?.BLUE / deathsBlue).toFixed(2)}</div>
                </div>
            </div>
        </div>
    );
}

export default DogfightsPerformanceComponent;