import React, {ChangeEvent, Suspense, useEffect} from 'react';
import {URL_API_TOURS_LIST} from "../../../constants/urls";
import i18next from "i18next";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerHeaderComponent = React.lazy(() => import('../../../components/Navbar/ServerHeaderComponent'));
const ServerNavbarComponent = React.lazy(() => import('../../../components/Navbar/ServerNavbarComponent'));
const TopFightersComponent = React.lazy(() => import('../../../components/TopFighters/TopFightersComponent'));
const TopAttackersComponent = React.lazy(() => import('../../../components/TopAttackers/TopAttackersComponent'));
const PVPDynamicsComponent = React.lazy(() => import('../../../components/PVPDynamics/PVPDynamicsComponent'));
const PVEDynamicsComponent = React.lazy(() => import('../../../components/PVEDynamics/PVEDynamicsComponent'));
const TopPlanesComponent = React.lazy(() => import('../../../components/TopPlanes/TopPlanesComponent'));
const SidesPercentageComponent = React.lazy(() => import('../../../components/SidesPercentage/SidesPercentageComponent'));
const KillsPerformanceComponent = React.lazy(() => import('../../../components/Perfomance/KillsPerformanceComponent'));
const DogfightsPerformanceComponent = React.lazy(() => import('../../../components/Perfomance/DogfightsPerformanceComponent'));
const FlightsDynamicsComponent = React.lazy(() => import('../../../components/FlightsDynamics/FlightsDynamicsComponent'));
const TopAerobaticsPlanes = React.lazy(() => import('../../../components/TopAerobatics/Planes/TopAerobaticsPlanes'));
const TopAerobaticsPilots = React.lazy(() => import('../../../components/TopAerobatics/Pilots/TopAerobaticsPilots'));
const TopAerobaticsAttackers = React.lazy(() => import('../../../components/TopAttackers/TopAerobaticsAttackers'));

const ServerStatisticsPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});
    const [tourId, setTourId] = React.useState(0);
    const [tours, setTours] = React.useState([]);
    const [activeTour, setActiveTour] = React.useState(0);
    useEffect(() => {
        fetch(URL_API_TOURS_LIST)
            .then(response => response.json())
            .then(data => {
                setTours(data);
                for (let i = 0; i < data.length; i++) {
                    let tour = data[i];
                    if (tour.finished) {
                        setActiveTour(tour.id);
                    }
                }
            })
            .catch(err => {
                console.error(err);
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

    const tourChange = (event) => {
        setTourId(parseInt(event.target.value));
    };

    return <>
        {server.id && <main className="main main_gradient text-white">
            <div className="content">
                <Suspense fallback={<LoadingComponent/>}>
                    <ServerHeaderComponent server={server}/>
                </Suspense>
                <Suspense fallback={<LoadingComponent/>}>
                    <ServerNavbarComponent server={server} active={'stats'}/>
                </Suspense>
                <div className="main__content">
                    <div className="main__date">
                        <select className="form-control" onChange={tourChange} defaultValue={activeTour}>
                            <option key={-1}/>
                            {tours.map(tour => (
                                <option value={tour.id} key={tour.id}>
                                    {i18next.language === LANGUAGE_ENGLISH ? tour.titleEn : tour.title}
                                </option>
                            ))}
                        </select>
                    </div>
                    {server && !server.isAerobatics && <div className="stats">
                        <div className="stats__axis">
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopPlanesComponent server={server} tour={tourId} side="RED"
                                                    header={i18next.t('top.axis_aircrafts')}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopFightersComponent server={server} header={i18next.t('top.axis_fighters')} side="RED"
                                                      tour={tourId}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopAttackersComponent side="RED" server={server} tour={tourId}
                                                       header={i18next.t('top.axis_attackers')}/>
                            </Suspense>
                        </div>
                        <div className="stats__center">
                            <Suspense fallback={<LoadingComponent/>}>
                                <SidesPercentageComponent server={server} tour={tourId}
                                                          header={i18next.t('label.missions_won')} type={'played'}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <SidesPercentageComponent server={server} tour={tourId}
                                                          header={i18next.t('label.pve_score')} type={'ground-kills'}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <SidesPercentageComponent server={server} tour={tourId}
                                                          header={i18next.t('label.aerial_victories')}
                                                          type={`air-kills`}/>
                            </Suspense>
                            <div className="stats__block">
                                <div className="stats__title">{i18next.t('label.pvp_dynamics')}</div>
                                <div className="graph">
                                    <Suspense fallback={<LoadingComponent/>}>
                                        <PVPDynamicsComponent server={server} tour={tourId}/>
                                    </Suspense>
                                </div>
                            </div>
                            <Suspense fallback={<LoadingComponent/>}>
                                <DogfightsPerformanceComponent server={server} tour={tourId}/>
                            </Suspense>

                            <div className="stats__block">
                                <div className="stats__title">{i18next.t('label.pve_dynamics')}</div>
                                <div className="graph">
                                    <Suspense fallback={<LoadingComponent/>}>
                                        <PVEDynamicsComponent server={server} tour={tourId}/>
                                    </Suspense>
                                </div>
                            </div>
                            <Suspense fallback={<LoadingComponent/>}>
                                <KillsPerformanceComponent server={server} tour={tourId}/>
                            </Suspense>
                        </div>
                        <div className="stats__allied">
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopPlanesComponent server={server} tour={tourId} side="BLUE"
                                                    header={i18next.t('top.allied_aircrafts')}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopFightersComponent server={server} header={i18next.t('top.allied_fighters')}
                                                      side="BLUE"
                                                      tour={tourId}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopAttackersComponent side="BLUE" server={server} tour={tourId}
                                                       header={i18next.t('top.allied_attackers')}/>
                            </Suspense>
                        </div>
                    </div>}
                    {server && server.isAerobatics && <div className="stats">
                        <div className="stats__axis">
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopAerobaticsPilots server={server} tour={tourId}
                                                     header={i18next.t('top.aerobatics_pilots_planes')} type={0}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopAerobaticsPilots server={server} tour={tourId}
                                                     header={i18next.t('top.aerobatics_pilots_helicopters')} type={1}/>
                            </Suspense>
                        </div>
                        <div className="stats__center">
                            <Suspense fallback={<LoadingComponent/>}>
                                <FlightsDynamicsComponent server={server} tour={tourId}/>
                            </Suspense>
                            <div className="stats__block">
                                <div className="stats__title">{i18next.t('label.pve_dynamics')}</div>
                                <div className="graph">
                                    <Suspense fallback={<LoadingComponent/>}>
                                        <PVEDynamicsComponent server={server} tour={tourId}/>
                                    </Suspense>
                                </div>
                            </div>
                        </div>
                        <div className="stats__allied">
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopAerobaticsPlanes server={server} tour={tourId}
                                                     header={i18next.t('top.aerobatics_planes')}/>
                            </Suspense>
                            <Suspense fallback={<LoadingComponent/>}>
                                <TopAerobaticsAttackers server={server} tour={tourId}
                                                        header={i18next.t('top.attackers')}/>
                            </Suspense>
                        </div>
                        {/*<FlightsInfoComponent server={server} tour={tourId}/>*/}
                    </div>}
                </div>
            </div>
        </main>}
    </>;
}

export default ServerStatisticsPage;