import React, {ChangeEvent, useEffect} from 'react';
import {URL_API_SERVERS, URL_API_TOURS_LIST} from "../../../constants/urls";
import ServerHeaderComponent from "../../../components/Navbar/ServerHeaderComponent";
import ServerNavbarComponent from "../../../components/Navbar/ServerNavbarComponent";
import i18next from "i18next";
import history from "../../../history";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";

const ServerMissionsPage = (props) => {
    //mission-sessions
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});
    const [tourId, setTourId] = React.useState(0);
    const [tours, setTours] = React.useState([]);
    const [missions, setMissions] = React.useState([]);

    useEffect(() => {
        fetch(URL_API_TOURS_LIST)
            .then(response => response.json())
            .then(data => {
                setTours(data);
            })
            .catch(err => {
                // console.error(err);
            })
        ;
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${id}`)
            .then(r => r.json())
            .then(data => {
                if (data.server) {
                    setServer(data.server);
                }
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    useEffect(() => {
        if (server.id !== undefined) {
            fetch(URL_API_SERVERS + `/${server.id}/mission-sessions?tour=${tourId}`)
                .then(response => response.json())
                .then(data => {
                    setMissions(data);
                })
                .catch(err => {
                    console.error(err);
                })
            ;
        }
    }, [server, tourId])

    const tourChange = (event) => {
        setTourId(parseInt(event.target.value));
    };
    const openMissionPage = (mission) => {
        history.push(`/mission/${mission.id}`, {missionRegistry: mission});
    };

    return (
        <React.Fragment>
            {server.id && <main className="main main_gradient">
                <div className="content">
                    <ServerHeaderComponent server={server}/>
                    <ServerNavbarComponent server={server} active={'missions'}/>

                    <div className="main__content">
                        <div className="main__date">
                            <select className="form-control" onChange={tourChange}>
                                <option key={-1}/>
                                {tours.map(tour => (
                                    <option value={tour.id} key={tour.id} selected={!tour.finished}>
                                        {i18next.language === LANGUAGE_ENGLISH ? tour.titleEn : tour.title}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="c-table">
                            <div className="b-table">
                                <table className="mission-table">
                                    <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>{i18next.t('label.mission_start_time')}</td>
                                        <td>{i18next.t('label.title')}</td>
                                        <td>{i18next.t('label.sorties_made')}</td>
                                        <td>{i18next.t('label.killed')}</td>
                                        <td>{i18next.t('label.ground_points')}</td>
                                        <td>{i18next.t('label.winner')}</td>
                                        <td>{i18next.t('label.duration')}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {missions.map((mission, i) => (
                                        <tr key={i} onClick={() => openMissionPage(mission)}>
                                            <td>{mission.id}</td>
                                            <td>{mission.startTime}</td>
                                            <td>{mission.name}</td>
                                            <td>
                                                <div className="mission-table__stat">
                                                    <div className="mission-table__axis">{mission.redSorties}</div>
                                                    <div className="mission-table__allied">{mission.blueSorties}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div className="mission-table__stat">
                                                    <div className="mission-table__axis">{mission.redDogfights}</div>
                                                    <div className="mission-table__allied">{mission.blueDogfights}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div className="mission-table__stat">
                                                    <div className="mission-table__axis">{mission.redKillsPoints}</div>
                                                    <div
                                                        className="mission-table__allied">{mission.blueKillsPoints}</div>
                                                </div>
                                            </td>
                                            <td>
                                                {mission.winner === 'RED' &&
                                                <div className="mission-table__won mission-table__won_axis"/>}
                                                {mission.winner === 'BLUE' &&
                                                <div className="mission-table__won mission-table__won_allies"/>}
                                            </td>
                                            <td>{mission.endTime && mission.duration}</td>
                                        </tr>
                                    ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>}
        </React.Fragment>
    );
}

export default ServerMissionsPage;