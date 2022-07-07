import React, {ChangeEvent, Suspense, useEffect} from 'react';
import {URL_API_TOURS_LIST} from "../../../constants/urls";
import i18next from "i18next";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const PilotsRankingTableComponent = React.lazy(() => import('../../../components/PilotsRankingTable/PilotsRankingTableComponent'));
const ServerHeaderComponent = React.lazy(() => import('../../../components/Navbar/ServerHeaderComponent'));
const ServerNavbarComponent = React.lazy(() => import('../../../components/Navbar/ServerNavbarComponent'));

const ServerFightersPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});
    const [tourId, setTourId] = React.useState(0);
    const [tours, setTours] = React.useState([]);

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

    const tourChange = (event) => {
        setTourId(parseInt(event.target.value));
    };

    return (
        <React.Fragment>
            {server.id && <main className="main main_gradient text-white">
                <div className="content">
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerHeaderComponent server={server}/>
                    </Suspense>
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerNavbarComponent server={server} active={'fighters'}/>
                    </Suspense>
                    <div className="main__content">
                        <div className="main__date">
                            <select onChange={tourChange}>
                                <option key={-1}/>
                                {tours.map(tour => (
                                    <option value={tour.id} key={tour.id} selected={!tour.finished}>
                                        {i18next.language === LANGUAGE_ENGLISH ? tour.titleEn : tour.title}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <Suspense fallback={<LoadingComponent/>}>
                            <PilotsRankingTableComponent server={server} tour={tourId}/>
                        </Suspense>
                    </div>
                </div>
            </main>}
        </React.Fragment>
    );
}

export default ServerFightersPage;