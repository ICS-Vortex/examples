import React, {useEffect} from 'react';
import i18next from "../../i18n";
import MissionRankingComponent from "../../components/Mission/Ranking/MissionRankingComponent";
import {Link} from 'react-router-dom';

const MissionPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});
    const [missionInfo, setMissionInfo] = React.useState({});

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/missions/${id}`)
            .then(r => r.json())
            .then(data => {
                if (data.server) {
                    setMissionInfo(data);
                    setServer(data.server);
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    const getHalfOrPercent = (obj) => {
        if (!obj) {
            return 50;
        }
        if (obj.redPercent === 0 && obj.bluePercent === 0) {
            return 50;
        }
        return obj.redPercent;
    };

    return (
        <React.Fragment>
            <main className="main main_gradient">
                <div className="content">
                    {missionInfo.id && <div className="main__content">
                        {server?.id && <div className="mb-5">
                            <Link to={`/server/${missionInfo.server?.id}/missions`}>
                                <i title={server.name} className="fa fa-arrow-left fa-2x"/>
                            </Link>
                        </div>}
                        <div className="pilot-rating pilot-rating_mb pilot-rating_in">
                            <div className="pilot-rating__title">{i18next.t('label.mission_statistics')}</div>
                            <div className="pilot-rating__table">
                                <div className="c-table">
                                    <div className="b-table b-table_stat">
                                        <table className="rating-table stat-mission-table">
                                            <thead>
                                            <tr>
                                                <td>#</td>
                                                <td>{i18next.t('label.mission')}</td>
                                                <td><i title={i18next.t('label.flights')}
                                                       className="fa fa-plane-departure text-danger"/></td>
                                                <td><i title={i18next.t('label.flights')}
                                                       className="fa fa-plane-departure text-primary"/></td>
                                                <td>{i18next.t('label.winner')}</td>
                                                <td>{i18next.t('label.duration')}</td>
                                                <td>{i18next.t('label.start_time')}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{missionInfo.id}</td>
                                                <td>{missionInfo.name}</td>
                                                <td>{missionInfo.sortiesBySides?.RED}</td>
                                                <td>{missionInfo.sortiesBySides?.BLUE}</td>
                                                <td>
                                                    {missionInfo.winner === 'RED' &&
                                                    <i title={i18next.t('label.winner')}
                                                       className="fa fa-star fa-2x text-danger"/>}
                                                    {missionInfo.winner === 'BLUE' &&
                                                    <i title={i18next.t('label.winner')}
                                                       className="fa fa-star fa-2x text-primary"/>}
                                                    {missionInfo.winner === 'DRAW' &&
                                                    <span>{i18next.t('label.undefined')}</span>}
                                                </td>
                                                <td>{missionInfo.duration}</td>
                                                <td>{missionInfo.start}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="mission-stats">
                            <div className="mission-stats__col">
                                <div className="stats__block">
                                    <div className="stats__title">{i18next.t('label.destroyed_aircrafts')}</div>
                                    <div className="percent">
                                        <div className="percent__line">
                                            <div className="percent__progress"
                                                 style={{width: `${getHalfOrPercent(missionInfo?.dogfightsPercents)}%`}}/>
                                        </div>
                                        <div className="percent__value">
                                            <div
                                                className="percent__value-axis">{missionInfo.dogfightsBySides?.RED}</div>
                                            <div
                                                className="percent__value-allied">{missionInfo.dogfightsBySides?.BLUE}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="mission-stats__col">
                                <div className="stats__block">
                                    <div className="stats__title">{i18next.t('label.destroyed_ground_targets')}</div>
                                    <div className="percent">
                                        <div className="percent__line">
                                            <div className="percent__progress"
                                                 style={{width: `${getHalfOrPercent(missionInfo.killsPercents)}%`}}/>
                                        </div>
                                        <div className="percent__value">
                                            <div className="percent__value-axis">{missionInfo.killsBySides?.RED}</div>
                                            <div
                                                className="percent__value-allied">{missionInfo.killsBySides?.BLUE}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="mission-stats__col">
                                <div className="stats__block">
                                    <div className="stats__title">{i18next.t('label.points')}</div>
                                    <div className="percent">
                                        <div className="percent__line">
                                            <div className="percent__progress"
                                                 style={{width: `${getHalfOrPercent(missionInfo.pointsPercents)}%`}}/>
                                        </div>
                                        <div className="percent__value">
                                            <div className="percent__value-axis">{missionInfo.pointsBySides?.RED}</div>
                                            <div
                                                className="percent__value-allied">{missionInfo.pointsBySides?.BLUE}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="mission-stats__col">
                                <div className="stats__block">
                                    <div className="stats__title">{i18next.t('label.flight_time')}</div>
                                    <div className="percent">
                                        <div className="percent__line">
                                            <div className="percent__progress"
                                                 style={{width: `${getHalfOrPercent(missionInfo.sortiesPercents)}%`}}/>
                                        </div>
                                        <div className="percent__value">
                                            <div
                                                className="percent__value-axis">{missionInfo.sortiesHoursBySides?.RED}</div>
                                            <div
                                                className="percent__value-allied">{missionInfo.sortiesHoursBySides?.BLUE}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="pilot-rating pilot-rating_in">
                            <div className="pilot-rating__title">{i18next.t('label.rating')}</div>
                            <div className="pilot-rating__table">
                                <div className="c-table">
                                    <div className="b-table">
                                        <MissionRankingComponent server={server} mission={missionInfo}/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>}
                </div>
            </main>
        </React.Fragment>
    );
}

export default MissionPage;
