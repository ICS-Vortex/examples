import React, {Suspense, useEffect} from 'react';
import LoadingComponent from "../../../components/Loading/LoadingComponent";

const ServerHeaderComponent = React.lazy(() => import('../../../components/Navbar/ServerHeaderComponent'));
const ServerNavbarComponent = React.lazy(() => import('../../../components/Navbar/ServerNavbarComponent'));
const BanListComponent = React.lazy(() => import('../../../components/BanList/BanListComponent'));

const ServerBanlistPage = (props) => {
    const id = parseInt(props.match.params.id);
    const [server, setServer] = React.useState({});

    useEffect(() => {
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

    return (
        <React.Fragment>
            {server.id && server.showBanList && <main className="main main_gradient text-white">
                <div className="content">
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerHeaderComponent server={server}/>
                    </Suspense>
                    <Suspense fallback={<LoadingComponent/>}>
                        <ServerNavbarComponent server={server} active={'banlist'}/>
                    </Suspense>

                    <div className="main__content">
                        <Suspense fallback={<LoadingComponent/>}>
                            <BanListComponent server={server}/>
                        </Suspense>
                    </div>
                </div>
            </main>}
        </React.Fragment>
    );
}

export default ServerBanlistPage;